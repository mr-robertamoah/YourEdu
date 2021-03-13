<?php

namespace App\Services;

use App\DTOs\PoemDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\PoemException;
use App\YourEdu\Poem;

class PoemService
{
    public function createPoem(PoemDTO $poemDTO)
    {
        $poem = $this->createOrUpdatePoem($poemDTO, 'create');

        $poemDTO = $poemDTO->withPoem($poem);

        $poem = $this->attachPoemToItem(
            poem: $poem,
            poemDTO: $poemDTO
        );

        $this->checkRequiredData($poem, $poemDTO);

        $poem = $this->addPoemSections($poem, $poemDTO);

        $poem = $this->addPoemFiles($poem, $poemDTO);

        return $poem;
    }

    private function checkRequiredData(Poem $poem = null, PoemDTO $poemDTO)
    {
        if (count($poemDTO->sections)) {
            return;
        }

        if ($poem->notRemovingAllSections($poemDTO->removedSections)) {
            return;
        }

        $poemFilesDTO = FileService::countPossibleItemFiles($poem, $poemDTO);

        if ($poemFilesDTO->totalFiles() > 0) {
            return;
        }

        $this->throwPoemException(
            message: "a poem requires at least one section or file (image, video or audio)",
            data: $poemDTO
        );
    }

    private function addPoemSections
    (
        Poem $poem,
        PoemDTO $poemDTO
    ) : Poem
    {
        foreach ($poemDTO->sections as $section) {
            $poem->poemSections()->create([
                'body' => $section->body
            ]);
        }

        return $poem->refresh();
    }

    private function createOrUpdatePoem
    (
        PoemDTO $poemDTO,
        string $method
    ) : Poem
    {
        $data = [
            'author_names' => $poemDTO->authorNames,
            'title' => $poemDTO->title,
            'about' => $poemDTO->about,
            'published_at' => $poemDTO->publishedAt?->toDateTimeString(),
        ];

        $poem = null;

        if ($method === 'create') {
            $poem = $poemDTO->addedby->poemsAdded()
                ->create($data);
        }
        
        if ($method === 'update') {
            $poem = $this->getPoemModel($poemDTO);
            
            $poem?->update($data);
        }
        
        if (is_null($poem)) {
            $this->throwPoemException(
                message: "failed to {$method} poem.",
                data: $poemDTO
            );
        }

        return $poem->refresh();
    }

    private function getPoemModel(PoemDTO $poemDTO)
    {
        if ($poemDTO->poem) {
            return $poemDTO->poem;
        }

        $poem = getYourEduModel('poem', $poemDTO->poemId);
        if (is_null($poem)) {
            throw new AccountNotFoundException("poem with id {$poemDTO->poemId} not found.");
        }

        return $poem;
    }

    private function addPoemFiles
    (
        Poem $poem,
        PoemDTO $poemDTO,
    )
    {
        foreach ($poemDTO->files as $file) {

            FileService::createAndAttachFiles(
                account: $poemDTO->addedby, 
                file: $file,
                item: $poem
            );
        }

        return $poem->refresh();
    }

    private function removePoemFiles
    (
        Poem $poem,
        PoemDTO $poemDTO,
    )
    {
        foreach ($poemDTO->removedFiles as $file) {

            FileService::deleteAndUnattachFiles(
                file: $file,
                item: $poem
            );
        }

        return $poem->refresh();
    }

    private function attachPoemToItem
    (
        Poem $poem,
        PoemDTO $poemDTO
    ) : Poem
    {
        if (!$poemDTO->poemable) return $poem;

        $poem->poemable()->associate($poemDTO->poemable);
        $poem->save();

        return $poem->refresh();
    }

    private function throwPoemException
    (
        string $message,
        $data = null
    )
    {
        throw new PoemException(
            message: $message,
            data: $data
        );
    }

    public function updatePoem(PoemDTO $poemDTO)
    {
        $poem = $this->createOrUpdatePoem($poemDTO, 'update');

        $poemDTO = $poemDTO->withPoem($poem);

        $this->checkRequiredData($poem, $poemDTO);

        $poem = $this->addPoemSections($poem, $poemDTO);

        $poem = $this->updatePoemSections($poem, $poemDTO);

        $poem = $this->removePoemSections($poem, $poemDTO);

        $poem = $this->addPoemFiles($poem, $poemDTO);

        $poem = $this->removePoemFiles($poem, $poemDTO);

        return $poem;
    }

    private function removePoemSections
    (
        Poem $poem,
        PoemDTO $poemDTO
    ) : Poem
    {
        foreach ($poemDTO->removedSections as $section) {
            $poem->poemSections()
                ->where('id',$section->poemSectionId)
                ->first()
                ?->delete();
        }

        return $poem->refresh();
    }

    private function updatePoemSections
    (
        Poem $poem,
        PoemDTO $poemDTO
    ) : Poem
    {
        foreach ($poemDTO->editedSections as $section) {
            $poem->poemSections()
                ->where('id',$section->poemSectionId)
                ->first()
                ?->update([
                    'body' => $section->body
                ]);
        }

        return $poem->refresh();
    }

    public function deletePoem(PoemDTO $poemDTO)
    {
        $poem = $this->getPoemModel($poemDTO);

        $poem = $this->deletePoemFiles($poem);

        $poem = $this->deletePoemSections($poem);

        return $poem->delete();
    }

    public function deletePoemSections
    (
        Poem $poem
    ) : Poem
    {
        if ($poem->doenstHaveSections()) {
            return $poem;
        }

        foreach ($poem->poemSections as $poemSection) {
            $poemSection->delete();
        }

        return $poem->refresh();
    }

    private function deletePoemFiles
    (
        Poem $poem,
    ) : Poem
    {
        FileService::deleteYourEduItemFiles(
            item: $poem,
        );

        return $poem->refresh();
    }
}
