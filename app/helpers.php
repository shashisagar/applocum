<?php

if (!function_exists('uploadImageToStoragePath')) {
    function uploadImageToStoragePath($image, $folderName = null, $fileName = null, $imageWidth = 1024, $imageHeight = 1024)
    {
        $destinationFolder = 'uploads/';
        if ($folderName != '') {
            $folderNames = explode('_', $folderName);
            $folderPath = implode('/', array_map(function ($value) {
                return $value;
            }, $folderNames));
            $destinationFolder .= $folderPath . '/';
        }
        $destinationPath = storage_path($destinationFolder);
        if (!File::exists($destinationPath)) File::makeDirectory($destinationPath, 0777, true, true);
        $filename = ($fileName != '') ? $fileName : $folderNames[sizeof($folderNames)-1] . time() . '.jpg';
        $imageResult = Image::make($image)->resize($imageWidth, $imageHeight, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . $filename, imageQuality($image));
        if ($imageResult) return '/image/uploads_'.$folderName.'_'. $filename;
        return false;
    }
}
if (!function_exists('imageQuality')) {
    function imageQuality($image)
    {
        $imageSize = filesize($image) / (1024 * 1024);
        if ($imageSize < 0.5) return 70;
        elseif ($imageSize > 0.5 && $imageSize < 1) return 60;
        elseif ($imageSize > 1 && $imageSize < 2) return 50;
        elseif ($imageSize > 2 && $imageSize < 5) return 40;
        elseif ($imageSize > 5) return 30;
        else return 50;
    }
}
