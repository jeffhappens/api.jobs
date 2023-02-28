<?php

namespace App\Services;

use Intervention\Image\Image as Image;

class UploadService {


    public function __construct()
    {
        //
    }



    public function getImageWidth($image): Int
    {
        
        return $image->width();

    }


    public function getImageHeight($image): Int
    {
        return $image->height();

    }


    public function getImageOrientation($image) : String
    {
        $options = [
            'landscape' => $this->getImageWidth($image) > $this->getImageHeight($image),
            'portrait' => $this->getImageWidth($image) < $this->getImageHeight($image),
            'square' => $this->getImageWidth($image) === $this->getImageHeight($image),
        ];

        $option = array_search(true, $options);
        return $option;

    }



    public function squareImage($image)
    {
        switch( $orientation = $this->getImageOrientation($image) ) {

            case 'square':
                return;

            case 'landscape':
                $this->cropImage($image, $image->height(), $image->height());
                break;

            case 'portrait':
                $this->cropImage($image, $image->width(), $image->width());
                break;
        }

        return $image;

    }




    public function resizeImage($image, $width, $height)
    {
        $image->resize($width, $height);
        return $image;

    }


    public function cropImage($image, $width, $height)
    {
        $image->crop($width, $height);
        return $image;

    }







}