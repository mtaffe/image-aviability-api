<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    public function generateImage(Request $request)
    {
        // Validação da requisição
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'percent' => 'required|integer|min:1|max:100',
        ]);

        // Pegando a imagem e a porcentagem do request
        $percent = (int) $request->input('percent');
        $imageFile = $request->file('file');

        // Criando instância do ImageManager
        $imageManager = new ImageManager(new Driver());

        // Lendo a imagem recebida
        $originalImage = $imageManager->read($imageFile->getPathname());

        // Calculando a posição do corte
        $linePosition = (int) ($originalImage->height() * ($percent / 100));

        // Criando a parte superior cortada
        $croppedImage = $originalImage->crop($originalImage->width(), $linePosition);
        $croppedPath = storage_path('app/public/rex-top.png');
        $croppedImage->save($croppedPath);

        // Criando a imagem desfocada
        $blurImage = $imageManager->read($imageFile->getPathname()) //$originalImage->blur(3)
            ->greyscale();

        // Salvando a imagem desfocada
        $blurPath = storage_path('app/public/rex-blur.png');
        $blurImage->save($blurPath);

        // Colocando a parte cortada sobre a imagem desfocada

        $finalImage = $imageManager->read($blurPath);
        $finalImage->place($croppedPath, 'top', 0, 0, 100);

        // Salvando a imagem final
        $finalPath = storage_path('app/public/rex3-blur.png');
        $finalImage->save($finalPath);

        // Retornando a imagem editada
        return response()->download($finalPath)->deleteFileAfterSend(true);
    }
}
