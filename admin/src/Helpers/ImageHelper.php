<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Create a thumbnail from an image file.
     */
    public static function createThumbnail(string $sourcePath, string $destPath, int $maxWidth, int $maxHeight): bool
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $info = getimagesize($sourcePath);
        if (!$info) {
            return false;
        }

        [$origWidth, $origHeight, $type] = $info;

        // Calculate new dimensions maintaining aspect ratio
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = (int) round($origWidth * $ratio);
        $newHeight = (int) round($origHeight * $ratio);

        // Create source image
        $source = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG  => imagecreatefrompng($sourcePath),
            IMAGETYPE_GIF  => imagecreatefromgif($sourcePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($sourcePath),
            default        => false,
        };

        if (!$source) {
            return false;
        }

        // Create thumbnail
        $thumb = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG/GIF
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }

        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Save thumbnail
        $result = match ($type) {
            IMAGETYPE_JPEG => imagejpeg($thumb, $destPath, 85),
            IMAGETYPE_PNG  => imagepng($thumb, $destPath, 6),
            IMAGETYPE_GIF  => imagegif($thumb, $destPath),
            IMAGETYPE_WEBP => imagewebp($thumb, $destPath, 85),
            default        => false,
        };

        imagedestroy($source);
        imagedestroy($thumb);

        return $result;
    }
}