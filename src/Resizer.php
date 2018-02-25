<?php

namespace Habier\JPGResizer;

class Resizer
{
    /**
     * Resize and save a jpg to the given sizes
     * @param string $destinyFile
     * @param string $originalFile
     * @param int $newwidth
     * @param int $newheight
     * @param int|null $OriginalWidth if left null it will calculate itself
     * @param int|null $originalHeight if left null it will calculate itself
     */
    public static function resize($destinyFile, $originalFile, $width, $height, $OriginalWidth = NULL, $originalHeight = NULL)
    {
        if (is_null($OriginalWidth) || is_null($originalHeight))
            list($OriginalWidth, $originalHeight) = getimagesize($originalFile);

        $thumb = imagecreatetruecolor($width, $height);
        $source = imagecreatefromjpeg($originalFile);

        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $width, $height, $OriginalWidth, $originalHeight);

        imagejpeg($thumb, $destinyFile);
        imagedestroy($thumb);
    }

    /**
     * Create a thumbnail limiting its bigger side but preserving aspect ratio.
     * @param string $destinyFile
     * @param string $originalFile
     * @param int $sizelimit
     * @return bool True if thumbnail created, false if thumbnail would be bigger than original
     */
    public static function autoResize($destinyFile, $originalFile, $sizelimit)
    {
        list($width, $height) = getimagesize($originalFile);

        if ($width >= $height && $width > $sizelimit) {
            $newwidth = $sizelimit;
            $newheight = $height / ($width / $newwidth);
        } elseif ($height >= $sizelimit) {
            $newheight = $sizelimit;
            $newwidth = $width / ($height / $newheight);
        } else {
            return false; //thumbnail must not be bigger than original
        }

        self::resize($destinyFile, $originalFile, round($newwidth), round($newheight), $width, $height);
        return true;
    }

}
