<?php

class ImageCropper
{
    public static function cropToSquare(string $filePath): bool
    {
        $imageInfo = getimagesize($filePath);
        if ($imageInfo === false) {
            return false;
        }
        list($width, $height, $type) = $imageInfo;
        $side = min($width, $height);
        $x = ($width - $side) / 2;
        $y = ($height - $side) / 2;

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
        $dstImg = imagecreatetruecolor($side, $side);
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagecolortransparent($dstImg, imagecolorallocatealpha($dstImg, 0, 0, 0, 127));
            imagealphablending($dstImg, false);
            imagesavealpha($dstImg, true);
        }
        imagecopyresampled($dstImg, $srcImg, 0, 0, $x, $y, $side, $side, $side, $side);
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
