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

class AgentListingController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $listing = new Listing();

        return view('agent.listings.form', compact('categories', 'listing'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

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
        $categories = Category::all();

        return view('agent.listings.form', compact('categories', 'listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        $this->authorizeOwner($request, $listing);

        $data = $this->validatedData($request);

        $listing->update([
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'location' => $data['location'],
            'condition' => $data['condition'],
            'status' => 'pending',
        ]);

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
        return $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|in:1,2,3,4',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'condition' => 'required|in:baru,bekas',
            'description' => 'nullable|string',
            'house_type' => 'required_if:category_id,1|nullable|string|max:255',
            'land_area' => 'required_if:category_id,1,2|nullable|numeric',
            'building_area' => 'required_if:category_id,1|nullable|numeric',
            'bedrooms' => 'required_if:category_id,1|nullable|integer',
            'bathrooms' => 'required_if:category_id,1|nullable|integer',
            'floors' => 'required_if:category_id,1|nullable|integer',
            'certificate' => 'required_if:category_id,1,2|nullable|string|max:255',
            'is_kpr' => 'required_if:category_id,1|nullable|boolean',
            'facilities' => 'nullable|string',
            'brand' => 'required_if:category_id,3,4|nullable|string|max:100',
            'model' => 'required_if:category_id,3,4|nullable|string|max:100',
            'year' => 'required_if:category_id,3,4|nullable|integer|between:1901,2155',
            'engine' => 'required_if:category_id,3,4|nullable|integer',
            'transmission' => 'required_if:category_id,3,4|nullable|in:manual,matic',
            'fuel_type' => 'nullable|in:bensin,diesel,listrik,hybrid',
            'color' => 'nullable|string|max:100',
            'kilometer' => 'nullable|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
    }

    private function syncDetails(Listing $listing, Request $request): void
    {
        if ((int) $request->category_id === 1 || (int) $request->category_id === 2) {
            PropertyDetail::updateOrCreate(
                ['listing_id' => $listing->id],
                [
                    'house_type' => $request->category_id == 1 ? $request->house_type : null,
                    'land_area' => $request->land_area,
                    'building_area' => $request->category_id == 1 ? $request->building_area : null,
                    'bedrooms' => $request->category_id == 1 ? $request->bedrooms : null,
                    'bathrooms' => $request->category_id == 1 ? $request->bathrooms : null,
                    'floors' => $request->category_id == 1 ? $request->floors : null,
                    'certificate' => $request->certificate,
                    'facilities' => $request->category_id == 1 ? $request->facilities : null,
                    'is_kpr' => $request->category_id == 1 ? ($request->is_kpr ?? 0) : 0,
                ]
            );
        } else {
            PropertyDetail::where('listing_id', $listing->id)->delete();
        }

        if ((int) $request->category_id === 3) {
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

        if ((int) $request->category_id === 4) {
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
