<?php

use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
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

function uploadYourEduFile($file)
{
    if ($file) {
        $originalFileName = $file->getClientOriginalName();
        $originalMime = $file->getClientMimeType();
        $originalFileExtension = $file->getClientOriginalExtension();
        $fileNameOnly = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Str::slug($fileNameOnly . time()) . "." . $originalFileExtension;
        if (Str::contains($originalMime, 'image')) {
            $dir = 'images';
        } else if (Str::contains($originalMime, 'video')) {
            $dir = 'video';
        } else if (Str::contains($originalMime, 'audio')) {
            $dir = 'audio';
        } else {
            $dir = 'file';
        }
        
        $path = $file->storeAs($dir,$fileName);

        return $path;
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