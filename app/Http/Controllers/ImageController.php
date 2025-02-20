<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    public function genareteImage(Request $request)
    {
        $percent = 33;
        $imageManager = new ImageManager(new Driver());
        $croppedImage = $imageManager->read('images/rex1.png');
        $linePosition = $croppedImage->height() * ($percent / 100);
        $croppedImage = $croppedImage->crop($croppedImage->width(), $linePosition)->save('images/rex2-top.png');

        $blurImage = $imageManager->read('images/rex1.png')
        ->blur(3)
        ->greyscale()
        ->pixelate(3)
        ->blur(5)
        ->save('images/rex3-blur2.png');
        $blurImage->place('images/rex2-top.png', 'top', 0, 40, 100);
        $blurImage->save('images/rex3-blur.png');

        return response()->file('images/rex3-blur.png');
    }
}
