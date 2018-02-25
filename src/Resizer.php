<?php

namespace Habier\JPGResizer;

class Resizer
{
    public static function resize($destinyFile, $originalFile, $newwidth, $newheight, $width = NULL, $height = NULL)
    {
        if (is_null($width) || is_null($height))
            list($width, $height) = getimagesize($originalFile);

        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $source = imagecreatefromjpeg($originalFile);

        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, round($newwidth), round($newheight), $width, $height);

        imagejpeg($thumb, $destinyFile);
        imagedestroy($thumb);
    }

    public static function autoResize($destinyFile, $originalFile, $sizelimit)
    {
        list($width, $height) = getimagesize($originalFile);
        //Check for new sizes
        if ($width >= $height && $width > $sizelimit) {
            $newwidth = $sizelimit;
            $newheight = $height / ($width / $newwidth);
        } elseif ($height >= $sizelimit) {
            $newheight = $sizelimit;
            $newwidth = $width / ($height / $newheight);
        } else {
            return false; //I dont want to upscale the image
        }

        Resizer::resize($destinyFile, $originalFile, $newwidth, $newheight, $width, $height);
        return true;//thumbnail created without upscaling
    }

}
