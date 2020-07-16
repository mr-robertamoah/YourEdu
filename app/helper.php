<?php


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

function uploadYourEduFiles($files)
{
   
    $uploadedFilePaths = [];
    foreach ($files as $file) {
        $uploadedFilePaths[] = uploadYourEduFile($file);
    }
    return $uploadedFilePaths;
}

function uploadYourEduFile($file, $dir = '')
{
    if ($file) {
        $originalFileName = $file->getClientOriginalName();
        $originalFileExtension = $file->getClientOriginalExtension();
        $fileNameOnly = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Str::slug($fileNameOnly . time()) . $originalFileExtension;
        if ($dir === '') {
            $dir = 'images';
        }
        
        $path = $file->storeAs($dir,$fileName);

        return $path;
    }
}

?>