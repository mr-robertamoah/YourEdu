<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public static function createAndAttachFiles($account,$file,$item)
    {
        if (!is_null($file)) {
            $file = self::accountCreateFile(
                $account, 
                self::getFileDetails($file),
                $item
            );
            return $file;
        }
        return null;
    }

    public static function deleteAndUnattachFilesFromObject($file,$item)
    {
        $actualFile = getYourEduModel($file->type,$file->id);
        if (!is_null($actualFile)) {
            $method = "{$file->type}s";
            $item->$method()->detach($file->id);
            Storage::delete($actualFile->path);
            $actualFile->delete();
        }
    }

    public static function getFileDetails($actualFile, $save = true)
    {
        $fileArray['mime'] = $actualFile->getClientMimeType();
        $fileArray['name'] = $actualFile->getClientOriginalName();
        $fileArray['size'] = $actualFile->getSize();
        if ($save) {
            $fileArray['path'] = self::uploadYourEduFile($actualFile);
        }
    
        return $fileArray;
    }

    public static function uploadYourEduFile($file, $hasThumbnail = false)
    {
        if ($file) {
            $originalFileName = $file->getClientOriginalName();
            $originalMime = $file->getClientMimeType();
            $originalFileExtension = $file->getClientOriginalExtension();
            $fileNameOnly = pathinfo($originalFileName, PATHINFO_FILENAME);
            $fileName = Str::slug($fileNameOnly . time()) . "." . $originalFileExtension;
            if ($hasThumbnail) {
                $fileNameSmall = Str::slug($fileNameOnly. 'small' . time()) . "." . $originalFileExtension;
                $fileNameMedium = Str::slug($fileNameOnly. 'medium' . time()) . "." . $originalFileExtension;
            }
            if (Str::contains($originalMime, 'image')) {
                $dir = 'images';
            } else if (Str::contains($originalMime, 'video')) {
                $dir = 'videos';
            } else if (Str::contains($originalMime, 'audio')) {
                $dir = 'audio';
            } else {
                $dir = 'files';
            }
            
            if ($hasThumbnail) {
    
                $paths = [];
                
                $paths['main'] = $file->storeAs($dir,$fileName);
                $paths['small'] = $file->storeAs($dir,$fileNameSmall);
                $paths['medium'] = $file->storeAs($dir,$fileNameMedium);
    
                return $paths;
            } else {
    
                $path = $file->storeAs($dir,$fileName);
    
                return $path;
            }
        }
    }

    public static function uploadYourEduFiles($files)
    {
       
        $uploadedFilePaths = [];
        foreach ($files as $file) {
            $uploadedFilePaths[] = self::uploadYourEduFile($file);
        }
        return $uploadedFilePaths;
    }
    
    function imageResize($file, $save = true)
    {
        $width = imagesx($file);
        $height = imagesy($file);
        $newWidth = 100;
        $newHeight = $newWidth/$width * $height;
    
        $newFile = imagescale($file,$newWidth,$newHeight);
    
        $fileArray['mime'] = $file->getClientMimeType();
        $fileArray['size'] = $file->getSize();
        if ($save) {
            $fileArray['path'] = self::uploadYourEduFile($newFile);
        }
    
        return $fileArray;
    }
    
    public static function accountCreateFile($account, $fileDetails, $associate = null)
    {
        $file = null;
        if(Str::contains($fileDetails['mime'], 'image')){
            $file = $account->addedImages()->create($fileDetails);
            $method = 'images';
        } else if(Str::contains($fileDetails['mime'], 'video')){
            $file = $account->addedVideos()->create($fileDetails);
            $method = 'videos';
        } else if(Str::contains($fileDetails['mime'], 'audio')){
            $file = $account->addedAudio()->create($fileDetails);
            $method = 'audios';
        } else {
            $file = $account->addedFiles()->create($fileDetails);
            $method = 'files';
        }
        if(!is_null($associate)){
            $associate->$method()->attach($file);
        }
    
        return $file;
    }

    // function createThumbnail($path, $width, $height)
    // {
    //     $img = Image::make($path)->resize($width, $height, function ($constraint) {
    //         $constraint->aspectRatio();
    //     });
    //     $img->save($path);
    // }
    
    function saveFileWithDetails($file, $newFile)
    {
        if ($file) {
            $originalFileName = $file->getClientOriginalName();
            $originalFileExtension = $file->getClientOriginalExtension();
            $fileNameOnly = pathinfo($originalFileName, PATHINFO_FILENAME);
            $fileName = Str::slug($fileNameOnly . time()) . "." . $originalFileExtension;
    
            $path = $newFile->save(public_path("assets/images/{$fileName}"));
    
            return $path;
        }
    }

    public static function deleteYourEduFiles($item)
    {
        if (!is_null($item)) {
            $files = $item->files;
            $files = $files->merge($item->audios);
            $files = $files->merge($item->videos);
            $files = $files->merge($item->images);
            
            foreach ($files as $file) {
                self::deleteYourEduFile($file);
            }
        }
    }
    
    public static function deleteYourEduFile($file)
    {
        if (!is_null($file)) {
            Storage::delete($file->path);
            $file->delete();
        }
    }

    public static function deleteFile($fileId, $fileType)
    {
        $file = null;
        if (Str::contains($fileType,'image')) {
            $file = getYourEduModel('image', $fileId);
        } else if (Str::contains($fileType,'video')) {
            $file = getYourEduModel('video', $fileId);
        } else if (Str::contains($fileType,'audio')) {
            $file = getYourEduModel('audio', $fileId);
        } else if (Str::contains($fileType,'file')) {
            $file = getYourEduModel('file', $fileId);
        }
    
        if (!is_null($file)) {
            Storage::delete($file->path);
            $file->delete();
        }
    }
}