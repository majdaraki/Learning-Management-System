<?php

namespace App\Traits;

trait Media
{

    // set images name
    public function setMediaName(array $images): array
    {
        return array_map(function ($image) {
            return uniqid() . '_' . substr(str_shuffle(
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_',
            ), 0, 50) . '.' . $image->getClientOriginalExtension();
        }, $images);
    }

    // save images
    public function saveMedia(array $images, array $names, string $path)
    {
        for ($i = 0; $i < count($images); $i++) {
            $images[$i]->storeAs($path, $names[$i]);
        }
    }

    // delete images
    public static function deleteMedia(string $path, array $names)
    {
        foreach ($names as $name) {
            $filePath = storage_path('app/public/' . $path . '/' . $name);
            if (file_exists($filePath)) {
       
                unlink($filePath);
            }
        }
    }

}
