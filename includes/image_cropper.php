<?php

class ImageCropper
{
    // Constants for aspect ratios
    public const ASPECT_RATIO_SQUARE = '1:1';
    public const ASPECT_RATIO_WIDESCREEN = '16:9';

    /**
     * Crop an image to square (1:1 aspect ratio)
     * 
     * @param string $filePath Path to the image file
     * @return bool Success or failure
     */
    public static function cropToSquare(string $filePath): bool
    {
        return self::cropToAspectRatio($filePath, self::ASPECT_RATIO_SQUARE);
    }

    /**
     * Crop an image to a specific aspect ratio
     * 
     * @param string $filePath Path to the image file
     * @param string $aspectRatio Aspect ratio in format "width:height" (e.g., "1:1", "16:9")
     * @return bool Success or failure
     */
    public static function cropToAspectRatio(string $filePath, string $aspectRatio = self::ASPECT_RATIO_SQUARE): bool
    {
        $imageInfo = getimagesize($filePath);
        if ($imageInfo === false) {
            return false;
        }

        list($width, $height, $type) = $imageInfo;

        // Parse aspect ratio
        $ratioComponents = explode(':', $aspectRatio);
        if (count($ratioComponents) !== 2) {
            return false;
        }

        $ratioWidth = (int)$ratioComponents[0];
        $ratioHeight = (int)$ratioComponents[1];

        if ($ratioWidth <= 0 || $ratioHeight <= 0) {
            return false;
        }

        // Calculate dimensions to maintain the aspect ratio
        $currentRatio = $width / $height;
        $targetRatio = $ratioWidth / $ratioHeight;

        if ($currentRatio > $targetRatio) {
            // Image is wider than target ratio - crop the width
            $newWidth = intval($height * $targetRatio);
            $newHeight = $height;
            $x = intval(($width - $newWidth) / 2);
            $y = 0;
        } else {
            // Image is taller than target ratio - crop the height
            $newWidth = $width;
            $newHeight = intval($width / $targetRatio);
            $x = 0;
            $y = intval(($height - $newHeight) / 2);
        }

        switch ($type) {
            case IMAGETYPE_JPEG:
                $srcImg = imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $srcImg = imagecreatefrompng($filePath);
                break;
            case IMAGETYPE_GIF:
                $srcImg = imagecreatefromgif($filePath);
                break;
            default:
                return false;
        }

        if (!$srcImg) {
            return false;
        }

        $dstImg = imagecreatetruecolor($newWidth, $newHeight);

        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagecolortransparent($dstImg, imagecolorallocatealpha($dstImg, 0, 0, 0, 127));
            imagealphablending($dstImg, false);
            imagesavealpha($dstImg, true);
        }

        imagecopyresampled($dstImg, $srcImg, 0, 0, $x, $y, $newWidth, $newHeight, $newWidth, $newHeight);

        $result = false;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $result = imagejpeg($dstImg, $filePath, 90);
                break;
            case IMAGETYPE_PNG:
                $result = imagepng($dstImg, $filePath);
                break;
            case IMAGETYPE_GIF:
                $result = imagegif($dstImg, $filePath);
                break;
        }

        imagedestroy($srcImg);
        imagedestroy($dstImg);

        return $result;
    }
}
