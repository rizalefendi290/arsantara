<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageWatermarkService
{
    public function storeListingImage(UploadedFile $file): string
    {
        if (!extension_loaded('gd')) {
            throw new RuntimeException('Extension PHP GD belum aktif. Aktifkan extension=gd di php.ini untuk memakai watermark gambar.');
        }

        $source = $this->createImage($file->getRealPath(), $file->getMimeType());
        $watermarkPath = public_path(config('watermark.image'));

        if (!is_file($watermarkPath)) {
            throw new RuntimeException('File watermark tidak ditemukan: '.$watermarkPath);
        }

        $watermark = $this->createImage($watermarkPath, mime_content_type($watermarkPath));
        $this->placeWatermark($source, $watermark);

        $extension = $this->extensionFor($file->getMimeType());
        $path = 'listings/'.Str::random(40).'.'.$extension;
        $absolutePath = Storage::disk('public')->path($path);

        if (!is_dir(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0755, true);
        }

        $this->saveImage($source, $absolutePath, $extension);

        imagedestroy($source);
        imagedestroy($watermark);

        return $path;
    }

    private function createImage(string $path, ?string $mime)
    {
        return match ($mime) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            'image/gif' => imagecreatefromgif($path),
            default => throw new RuntimeException('Format gambar tidak didukung untuk watermark.'),
        };
    }

    private function placeWatermark($source, $watermark): void
    {
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $watermarkWidth = imagesx($watermark);
        $watermarkHeight = imagesy($watermark);

        imagepalettetotruecolor($source);
        imagepalettetotruecolor($watermark);
        imagealphablending($source, true);
        imagesavealpha($source, true);
        imagesavealpha($watermark, true);

        $targetWidth = max(90, (int) ($sourceWidth * config('watermark.width_ratio')));
        $targetWidth = min($targetWidth, (int) ($sourceWidth * 0.45), $watermarkWidth);
        $targetHeight = (int) round($targetWidth * ($watermarkHeight / $watermarkWidth));

        $resizedWatermark = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($resizedWatermark, false);
        imagesavealpha($resizedWatermark, true);
        imagefill($resizedWatermark, 0, 0, imagecolorallocatealpha($resizedWatermark, 0, 0, 0, 127));

        imagecopyresampled(
            $resizedWatermark,
            $watermark,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $watermarkWidth,
            $watermarkHeight
        );

        $this->applyOpacity($resizedWatermark, max(0, min(100, (int) config('watermark.opacity'))));

        [$x, $y] = $this->coordinates($sourceWidth, $sourceHeight, $targetWidth, $targetHeight);

        imagecopy($source, $resizedWatermark, $x, $y, 0, 0, $targetWidth, $targetHeight);
        imagedestroy($resizedWatermark);
    }

    private function coordinates(int $sourceWidth, int $sourceHeight, int $watermarkWidth, int $watermarkHeight): array
    {
        $margin = max(12, (int) ($sourceWidth * config('watermark.margin_ratio')));

        return match (config('watermark.position')) {
            'bottom-left' => [$margin, $sourceHeight - $watermarkHeight - $margin],
            'top-left' => [$margin, $margin],
            'top-right' => [$sourceWidth - $watermarkWidth - $margin, $margin],
            'center' => [
                (int) (($sourceWidth - $watermarkWidth) / 2),
                (int) (($sourceHeight - $watermarkHeight) / 2),
            ],
            default => [$sourceWidth - $watermarkWidth - $margin, $sourceHeight - $watermarkHeight - $margin],
        };
    }

    private function applyOpacity($image, int $opacity): void
    {
        $width = imagesx($image);
        $height = imagesy($image);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgba = imagecolorat($image, $x, $y);
                $alpha = ($rgba & 0x7F000000) >> 24;
                $red = ($rgba >> 16) & 0xFF;
                $green = ($rgba >> 8) & 0xFF;
                $blue = $rgba & 0xFF;
                $newAlpha = 127 - (int) round((127 - $alpha) * ($opacity / 100));
                $color = imagecolorallocatealpha($image, $red, $green, $blue, $newAlpha);

                imagesetpixel($image, $x, $y, $color);
            }
        }
    }

    private function extensionFor(?string $mime): string
    {
        return match ($mime) {
            'image/png', 'image/gif' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };
    }

    private function saveImage($image, string $path, string $extension): void
    {
        match ($extension) {
            'png' => imagepng($image, $path, 8),
            'webp' => imagewebp($image, $path, 85),
            default => imagejpeg($image, $path, 85),
        };
    }
}
