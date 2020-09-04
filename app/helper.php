<?php

use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

function getAccount($account)
{
    if (Str::contains(strtolower($account), 'learner')) {
        return 'learner';
    } else if (Str::contains(strtolower($account), 'parent')) {
        return 'parent';
    } else if (Str::contains(strtolower($account), 'facilitator')) {
        return 'facilitator';
    } else if (Str::contains(strtolower($account), 'professional')) {
        return 'professional';
    } else if (Str::contains(strtolower($account), 'school')) {
        return 'school';
    } else if (Str::contains(strtolower($account), 'admin')) {
        return 'admin';
    }
}

function uploadYourEduFiles($files)
{
   
    $uploadedFilePaths = [];
    foreach ($files as $file) {
        $uploadedFilePaths[] = uploadYourEduFile($file);
    }
    return $uploadedFilePaths;
}

function getFileDetails($actualFile, $save = true)
{
    $file = $actualFile;
    $fileArray['mime'] = $file->getClientMimeType();
    $fileArray['size'] = $file->getSize();
    if ($save) {
        $fileArray['path'] = uploadYourEduFile($actualFile);
    }

    return $fileArray;
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
        $fileArray['path'] = uploadYourEduFile($newFile);
    }

    return $fileArray;
}

function accountCreateFile(String $mime, $account, $fileDetails, $associate = null)
{
    if(Str::contains($mime, 'image')){
        $file = $account->addedImages()->create($fileDetails);
        if($associate){
            $associate->images()->attach($file);
        }
    } else if(Str::contains($mime, 'video')){
        $file = $account->addedVidoes()->create($fileDetails);
        if($associate){
            $associate->videos()->attach($file);
        }
    } else if(Str::contains($mime, 'audio')){
        $file = $account->addedAudios()->create($fileDetails);
        if($associate){
            $associate->audios()->attach($file);
        }
    } else if(Str::contains($mime, 'file')){
        $file = $account->addedFiles()->create($fileDetails);
        if($associate){
            $associate->files()->attach($file);
        }
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

function uploadYourEduFile($file, $hasThumbnail = false)
{
    if ($file) {
        $originalFileName = $file->getClientOriginalName();
        $originalMime = $file->getClientMimeType();
        $originalFileExtension = $file->getClientOriginalExtension();
        $fileNameOnly = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Str::slug($fileNameOnly . time()) . "." . $originalFileExtension;
        $fileNameSmall = '';
        $fileNameMedium = '';
        if ($hasThumbnail) {
            $fileNameSmall = Str::slug($fileNameOnly. 'small' . time()) . "." . $originalFileExtension;
            $fileNameMedium = Str::slug($fileNameOnly. 'medium' . time()) . "." . $originalFileExtension;
        }
        if (Str::contains($originalMime, 'image')) {
            $dir = 'images';
        } else if (Str::contains($originalMime, 'video')) {
            $dir = 'video';
        } else if (Str::contains($originalMime, 'audio')) {
            $dir = 'audio';
        } else {
            $dir = 'file';
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

function paginate(Collection $collection,$pageSize){
    $page = Paginator::resolveCurrentPage('page');
    $total = $collection->count();
    // dd($collection->forPage($page,$pageSize));

    return paginator($collection->forPage($page,$pageSize),$total,$pageSize,$page,[
        'path' => Paginator::resolveCurrentPath(),
        'pageName' => 'page'
    ]);
}

function paginator($items, $total, $perPage, $currentPage, $options){
    return Container::getInstance()->makeWith(LengthAwarePaginator::class,compact('items',
        'total', 'perPage','currentPage','options'));
}

?>