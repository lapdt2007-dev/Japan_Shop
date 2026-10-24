<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Lưu ảnh vào disk public (thu nhỏ + WebP). Trả về đường dẫn tương đối, hoặc false nếu lưu lỗi.
     */
    public function store(UploadedFile $file, string $dir = 'products', int $maxSize = 1200): string|false
    {
        try {
            $path = $this->optimize($file, $dir, $maxSize);

            if ($path) {
                return $path;
            }
        } catch (\Throwable $e) {
            report($e);
        }

        // Không tối ưu được (thiếu GD/WebP, ảnh GIF, ảnh quá lớn...) -> lưu nguyên bản
        return $file->store($dir, 'public');
    }

    private function optimize(UploadedFile $file, string $dir, int $maxSize): ?string
    {
        if (!extension_loaded('gd') || !function_exists('imagewebp')) {
            return null;
        }

        $source = $file->getRealPath();
        $info   = @getimagesize($source);

        if (!$info) {
            return null;
        }

        [$width, $height, $type] = $info;

        // Tránh hết RAM với ảnh quá lớn (> ~25 megapixel)
        if ($width * $height > 25000000) {
            return null;
        }

        // GIF (có thể là ảnh động) và các định dạng khác được giữ nguyên bản
        $image = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG  => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => @imagecreatefromwebp($source),
            default        => false,
        };

        if (!$image) {
            return null;
        }

        // Ảnh chụp từ điện thoại hay bị lật theo EXIF
        if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif  = @exif_read_data($source);
            $angle = match ($exif['Orientation'] ?? 1) {
                3       => 180,
                6       => -90,
                8       => 90,
                default => 0,
            };

            if ($angle !== 0 && ($rotated = imagerotate($image, $angle, 0))) {
                $image  = $rotated;
                $width  = imagesx($image);
                $height = imagesy($image);
            }
        }

        // Thu nhỏ nếu cạnh dài hơn $maxSize
        $longest = max($width, $height);
        if ($longest > $maxSize) {
            $ratio  = $maxSize / $longest;
            $scaled = imagescale($image, (int) round($width * $ratio), (int) round($height * $ratio));

            if ($scaled) {
                $image = $scaled;
            }
        }

        imagealphablending($image, false);
        imagesavealpha($image, true); // giữ nền trong suốt của PNG

        ob_start();
        $ok   = imagewebp($image, null, 82);
        $data = ob_get_clean();

        if (!$ok || !$data) {
            return null;
        }

        $path = trim($dir, '/') . '/' . Str::random(24) . '.webp';

        return Storage::disk('public')->put($path, $data) ? $path : null;
    }
}