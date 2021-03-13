<?php

namespace App\Services;

use App\DTOs\RiddleDTO;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\RiddleException;
use App\YourEdu\Riddle;

class RiddleService
{
    public function createRiddle(RiddleDTO $riddleDTO)
    {
        $riddle = $this->createOrUpdateRiddle($riddleDTO, 'create');

        $riddleDTO = $riddleDTO->withRiddle($riddle);

        $riddle = $this->attachRiddleToItem(
            riddle: $riddle,
            riddleDTO: $riddleDTO
        );

        $riddle = $this->addRiddleFIles($riddle, $riddleDTO);

        return $riddle;
    }

    private function createOrUpdateRiddle
    (
        RiddleDTO $riddleDTO,
        string $method
    ) : Riddle
    {
        $data = [
            'author_names' => $riddleDTO->authorNames,
            'body' => $riddleDTO->body,
            'score_over' => $riddleDTO->scoreOver,
            'published_at' => $riddleDTO->publishedAt?->toDateTimeString(),
        ];

        $riddle = null;

        if ($method === 'create') {
            $riddle = $riddleDTO->addedby->riddlesAdded()
                ->create($data);
        }
        
        if ($method === 'update') {
            $riddle = $this->getRiddleModel($riddleDTO);
                
            $riddle?->update($data);
        }
        
        if (is_null($riddle)) {
            $this->throwActivityException(
                message: "failed to {$method} riddle.",
                data: $riddleDTO
            );
        }

        return $riddle->refresh();
    }

    private function addRiddleFIles
    (
        Riddle $riddle,
        RiddleDTO $riddleDTO,
    )
    {
        foreach ($riddleDTO->files as $file) {

            FileService::createAndAttachFiles(
                account: $riddleDTO->addedby, 
                file: $file,
                item: $riddle
            );
        }

        return $riddle->refresh();
    }

    private function attachRiddleToItem
    (
        Riddle $riddle,
        RiddleDTO $riddleDTO
    ) : Riddle
    {
        if (!$riddleDTO->riddleable) return $riddle;

        $riddle->riddleable()->associate($riddleDTO->riddleable);
        $riddle->save();

        return $riddle->refresh();
    }

    private function throwActivityException
    (
        string $message,
        $data = null
    )
    {
        throw new RiddleException(
            message: $message,
            data: $data
        );
    }

    public function updateRiddle(RiddleDTO $riddleDTO)
    {
        $riddle = $this->createOrUpdateRiddle($riddleDTO, 'update');

        $riddleDTO = $riddleDTO->withRiddle($riddle);
        
        $riddle = $this->addRiddleFIles($riddle, $riddleDTO);
        
        $riddle = $this->removeRiddleFiles($riddle, $riddleDTO);
        
        return $riddle;
    }

    private function getRiddleModel(RiddleDTO $riddleDTO)
    {
        if ($riddleDTO->riddle) {
            return $riddleDTO->riddle;
        }

        $riddle = getYourEduModel('riddle', $riddleDTO->riddleId);
        if (is_null($riddle)) {
            throw new AccountNotFoundException("riddle with id {$riddleDTO->riddleId} not found.");
        }

        return $riddle;
    }

    public function deleteRiddle(RiddleDTO $riddleDTO)
    {
        $riddle = $this->getRiddleModel($riddleDTO);

        $riddle = $this->deleteRiddleFiles($riddle);

        return $riddle->delete();
    }

    private function deleteRiddleFiles
    (
        Riddle $riddle,
    ) : Riddle
    {
        FileService::deleteYourEduItemFiles(
            item: $riddle
        );

        return $riddle->refresh();
    }

    private function removeRiddleFiles
    (
        Riddle $riddle,
        RiddleDTO $riddleDTO
    ) : Riddle
    {
        foreach ($riddleDTO->removedFiles as $file) {

            FileService::deleteAndUnattachFiles(
                file: $file,
                item: $riddle
            );
        }

        return $riddle->refresh();
    }
}
