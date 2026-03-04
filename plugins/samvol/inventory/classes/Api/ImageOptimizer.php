<?php namespace Samvol\Inventory\Classes\Api;

use Illuminate\Http\UploadedFile;

class ImageOptimizer
{
    public function optimize(UploadedFile $file, int $maxWidth = 1920, int $maxHeight = 1920): ?string
    {
        if (!function_exists('imagecreatefromstring')) {
            return null;
        }

        $raw = @file_get_contents($file->getRealPath());
        if ($raw === false) {
            return null;
        }

        $source = @imagecreatefromstring($raw);
        if (!$source) {
            return null;
        }

        $srcWidth = imagesx($source);
        $srcHeight = imagesy($source);

        $scale = min($maxWidth / max(1, $srcWidth), $maxHeight / max(1, $srcHeight), 1);
        $targetWidth = max(1, (int) floor($srcWidth * $scale));
        $targetHeight = max(1, (int) floor($srcHeight * $scale));

        $target = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $srcWidth, $srcHeight);

        $path = tempnam(sys_get_temp_dir(), 'inv_img_');
        if ($path === false) {
            imagedestroy($source);
            imagedestroy($target);
            return null;
        }

        $jpegPath = $path . '.jpg';
        imagejpeg($target, $jpegPath, 82);

        imagedestroy($source);
        imagedestroy($target);

        return $jpegPath;
    }
}
