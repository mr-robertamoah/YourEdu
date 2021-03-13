<?php

namespace App\Services;

use App\DTOs\ItemFilesDTO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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

    public static function deleteAndUnattachFiles($file,$item)
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
            $fileArray['path'] = self::uploadYourEduFile(
                file: $actualFile,
                fileDetails: $fileArray
            );
        }
    
        return $fileArray;
    }

    public static function deleteAllRelatedFilesBeforeRollback(Model $item)
    {
        $paths = [];

        if ($item->files) {
            array_push(
                $paths,
                $item->files->pluck('path')->toArray()
            );
        }

        if ($item->audios) {
            array_push(
                $paths,
                $item->audios->pluck('path')->toArray()
            );
        }

        if ($item->videos) {
            array_push(
                $paths,
                $item->videos->pluck('path')->toArray()
            );
        }
        
        if ($item->images) {
            array_push(
                $paths,
                $item->images->pluck('path')->toArray()
            );
        }

        foreach ($paths as $path) {
            Storage::delete($path);    
        }
    }

    public static function uploadYourEduFile
    (
        $file, 
        $hasThumbnail = false,
        $fileDetails = []
    )
    {
        if (is_null($file)) return;

        if (count($fileDetails) > 1) {
            $originalFileName = $fileDetails['name'];
            $originalMime = $fileDetails['mime'];
        } else {
            $originalFileName = $file->getClientOriginalName();
            $originalMime = $file->getClientMimeType();
        }
        
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

    private static function isMimeType($fileMime, $fileType)
    {
        return str_contains(
            $fileMime,
            $fileType
        );
    }

    private static function isFileType($type, $fileType)
    {
        return $type === $fileType;
    }

    private static function doesntExistInFiles
    (
        $searchFile,
        $files
    ) : bool
    {
        if (!is_array($files)) {
            return true;
        }

        $incidence = count(
            array_filter($files, function($file) use ($searchFile) {
                return $file->type === $searchFile->type && 
                    $file->id === $searchFile->id;
            })
        );

        if ($incidence === 0) {
            return true;
        }

        return false;
    }

    public static function updatetItemFilesCountUsingMimeType
    (
        $fileMime, 
        $itemFileDTO
    )
    {
        if (self::isMimeType($fileMime, 'image')) {
            $itemFileDTO->imagesCount++;
            return;
        }

        if (self::isMimeType($fileMime, 'video')) {
            $itemFileDTO->videosCount++;
            return;
        }

        if (self::isMimeType($fileMime, 'audio')) {
            $itemFileDTO->audiosCount++;
            return;
        }

        $itemFileDTO->filesCount++;
    }

    public static function updatetItemFilesCountUsingFileType
    (
        $fileType, 
        $itemFileDTO
    )
    {
        if (self::isFileType($fileType, 'image')) {
            $itemFileDTO->imagesCount--;
            return;
        }

        if (self::isFileType($fileType, 'video')) {
            $itemFileDTO->videosCount--;
            return;
        }

        if (self::isFileType($fileType, 'audio')) {
            $itemFileDTO->audiosCount--;
            return;
        }

        if (self::isFileType($fileType, 'file')) {
            $itemFileDTO->filesCount--;
        }
    }

    public static function countPossibleItemFiles
    (
        Model | null $item, 
        $dto
    ) : ItemFilesDTO
    {
        $itemFileDTO = new ItemFilesDTO;

        if ($item) {
            foreach ($item->allFiles() as $file) {
    
                if (self::doesntExistInFiles($file, $dto->removedFiles ?? [])) {
                    self::updatetItemFilesCountUsingMimeType(
                        $file->mime,
                        $itemFileDTO
                    );
                }
            }
        }

        if (property_exists($dto,'files')) {
            foreach ($dto->files as $file) {

                self::updatetItemFilesCountUsingMimeType(
                    $file->getClientMimeType(),
                    $itemFileDTO
                );
            }
        }

        if (property_exists($dto,'removedFiles')) {
            foreach ($dto->removedFiles as $file) {
                
                self::updatetItemFilesCountUsingFileType(
                    $file->type,
                    $itemFileDTO
                );
            }
        }

        return $itemFileDTO;
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

    // private function createThumbnail($path, $width, $height)
    // {
    //     $img = Image::make($path)->resize($width, $height, function ($constraint) {
    //         $constraint->aspectRatio();
    //     });
    //     $img->save($path);
    // }

    public static function deleteYourEduItemFiles($item)
    {
        if (is_null($item)) return;

        $files = new Collection();
        if ($item->files) {
            $files = $files->merge($item->files);
        }
        if ($item->audios) {
            $files = $files->merge($item->audios);
        }
        if ($item->videos) {
            $files = $files->merge($item->videos);
        }
        if ($item->images) {
            $files = $files->merge($item->images);
        }
        
        foreach ($files as $file) {
            self::deleteYourEduFile($file);
        }
    }
    
    public static function deleteYourEduFile($file)
    {
        if (is_null($file)) {
            return;
        }
        
        Storage::delete($file->path);
        $file->delete();
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