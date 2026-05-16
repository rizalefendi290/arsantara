<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\CarDetail;
use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\MotorcycleDetail;
use App\Models\PropertyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageWatermarkService;
use Illuminate\Validation\Rule;

class AgentListingController extends Controller
{
    public function create()
    {
        $categories = Category::active()->get();
        $listing = new Listing();

        return view('agent.listings.form', compact('categories', 'listing'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        abort_unless(Category::whereKey($data['category_id'])->where('is_active', true)->exists(), 422);

        $listing = Listing::create([
            'user_id' => $request->user()->id,
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'location' => $data['location'],
            'condition' => $data['condition'],
            'status' => 'pending',
        ]);
        $listing->assignProductCode();

        $this->syncDetails($listing, $request);
        $this->storeImages($listing, $request, app(ImageWatermarkService::class));

        $shareUrl = route('listing.show', $listing->id);

        return redirect()->route('agent.dashboard')
            ->with('success', 'Listing berhasil dikirim dan menunggu review admin.')
            ->with('share_url', $shareUrl)
            ->with('share_title', $listing->title)
            ->with('share_text', 'Lihat listing terbaru di Arsantara: '.$listing->title)
            ->with('share_available', false);
    }

    public function edit(Request $request, Listing $listing)
    {
        $this->authorizeOwner($request, $listing);

        $listing->load(['propertyDetail', 'carDetail', 'motorcycleDetail', 'images']);
        $categories = Category::active()->get();

        return view('agent.listings.form', compact('categories', 'listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        $this->authorizeOwner($request, $listing);

        $data = $this->validatedData($request);
        abort_unless(Category::whereKey($data['category_id'])->where('is_active', true)->exists(), 422);
        $oldCategoryId = $listing->category_id;

        $listing->update([
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'location' => $data['location'],
            'condition' => $data['condition'],
            'status' => 'pending',
        ]);
        $listing->refresh();

        if ((int) $oldCategoryId !== (int) $listing->category_id || blank($listing->product_code)) {
            $listing->assignProductCode(true);
        }

        $this->syncDetails($listing, $request);
        $this->storeImages($listing, $request, app(ImageWatermarkService::class));

        return redirect()->route('agent.dashboard')
            ->with('success', 'Listing berhasil diperbarui dan kembali menunggu review admin.');
    }

    public function destroy(Request $request, Listing $listing)
    {
        $this->authorizeOwner($request, $listing);

        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $listing->delete();

        return redirect()->route('agent.dashboard')
            ->with('success', 'Listing berhasil dihapus.');
    }

    public function markSold(Request $request, Listing $listing)
    {
        $this->authorizeOwner($request, $listing);

        $listing->update(['status' => 'sold']);

        return back()->with('success', 'Listing ditandai terjual/disewa.');
    }

    public function deleteImage(Request $request, ListingImage $image)
    {
        $listing = $image->listing;
        $this->authorizeOwner($request, $listing);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $category = Category::active()->find($request->input('category_id'));

        return $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'condition' => 'required|in:baru,bekas',
            'description' => 'nullable|string',
            'house_type' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'string', 'max:255'],
            'land_area' => [Rule::requiredIf(fn () => $category?->isProperty()), 'nullable', 'numeric'],
            'building_area' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'numeric'],
            'bedrooms' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'integer'],
            'bathrooms' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'integer'],
            'floors' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'integer'],
            'certificate' => [Rule::requiredIf(fn () => $category?->isProperty()), 'nullable', 'string', 'max:255'],
            'is_kpr' => [Rule::requiredIf(fn () => $category?->isHouse()), 'nullable', 'boolean'],
            'facilities' => 'nullable|string',
            'brand' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'string', 'max:100'],
            'model' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'string', 'max:100'],
            'year' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'integer', 'between:1901,2155'],
            'engine' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'integer'],
            'transmission' => [Rule::requiredIf(fn () => $category?->isVehicle()), 'nullable', 'in:manual,matic'],
            'fuel_type' => 'nullable|in:bensin,diesel,listrik,hybrid',
            'color' => 'nullable|string|max:100',
            'kilometer' => 'nullable|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
    }

    private function syncDetails(Listing $listing, Request $request): void
    {
        $category = Category::findOrFail($request->category_id);

        if ($category->isProperty()) {
            $isHouse = $category->isHouse();
            $isLand = $category->isLand();
            $isCommercial = $category->isCommercialProperty();

            PropertyDetail::updateOrCreate(
                ['listing_id' => $listing->id],
                [
                    'house_type' => ($isHouse || $isCommercial) ? $request->house_type : null,
                    'land_area' => $request->land_area,
                    'building_area' => !$isLand ? $request->building_area : null,
                    'bedrooms' => $isHouse ? $request->bedrooms : null,
                    'bathrooms' => !$isLand ? $request->bathrooms : null,
                    'floors' => !$isLand ? $request->floors : null,
                    'certificate' => $request->certificate,
                    'facilities' => !$isLand ? $request->facilities : null,
                    'is_kpr' => $isHouse ? ($request->is_kpr ?? 0) : 0,
                ]
            );
        } else {
            PropertyDetail::where('listing_id', $listing->id)->delete();
        }

        if ($category->isCarLike()) {
            CarDetail::updateOrCreate(
                ['listing_id' => $listing->id],
                [
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'year' => $request->year,
                    'engine' => $request->engine,
                    'transmission' => $request->transmission,
                    'fuel_type' => $request->fuel_type,
                    'color' => $request->color,
                    'kilometer' => $request->kilometer,
                ]
            );
        } else {
            CarDetail::where('listing_id', $listing->id)->delete();
        }

        if ($category->isMotorcycle()) {
            MotorcycleDetail::updateOrCreate(
                ['listing_id' => $listing->id],
                [
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'year' => $request->year,
                    'engine' => $request->engine,
                    'transmission' => $request->transmission,
                ]
            );
        } else {
            MotorcycleDetail::where('listing_id', $listing->id)->delete();
        }
    }

    private function storeImages(Listing $listing, Request $request, ImageWatermarkService $watermarkService): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        foreach ($request->file('images') as $image) {
            if (!$image) {
                continue;
            }

            ListingImage::create([
                'listing_id' => $listing->id,
                'image' => $watermarkService->storeListingImage($image),
            ]);
        }
    }

    private function authorizeOwner(Request $request, Listing $listing): void
    {
        abort_unless($listing->user_id === $request->user()->id, 403);
    }
}
