<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Filesystem\FilesystemException;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\httpFoundation\File\UploadedFile;

use App\Models\Image;

trait FileStorageHelper {

    public function images(array $files, string $destination)
    {
        $images = [];
        foreach($files as $key => $value){
            if (empty($value)) continue;

            $images[] = $this->images($value, $destination, $key);
        }
        return $images;
    }
    public function imagefile(UploadedFile $uploadFile, string $destination, string $name =null)
    {
        $path = $uploadFile->store($destination);

        $image = new Image([
            'name' => $name ?: $uploadfile->hashName(),
            'originalName' => $uploadfile->getClientoriginalName(),
            'originalExtension' => $uploadfile->getClientoriginalExtension(),
            'mimeType' => $uploadfile->getClientmimeType(),
            'size' => $uploadfile->getSize(),
            'path' => $path()
        ]);

        $image->save();
        return $image;
    }

    public function deleteallfile($model)
    {
        foreach($model->images as $image){
            Storage::delete($image->path);
            $image->delete();
        }
    }
}