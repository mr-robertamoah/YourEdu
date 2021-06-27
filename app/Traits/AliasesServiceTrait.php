<?php

namespace App\Traits;

trait AliasesServiceTrait
{
    private function makeAlias($dto)
    {
        return $dto->addedby->aliasesAdded()->create([
            'name' => $dto->aliasName,
            'description' => $dto->aliasDescription,
        ]);
    }

    private function createAliases($dto)
    {
        $aliases = [];

        foreach ($dto->aliases as $alias) {
            $dto->setAliasData($alias);
            $aliases[] = $this->createAlias($dto);
        }

        return $aliases;
    }

    public function createAlias($dto)
    {
        if ($dto->aliasable->hasAliasWithName()) {
            return null;
        }

        $alias = $this->makeAlias($dto);

        $alias->aliasable()->associate($dto->aliasable);
        $alias->save();

        if ($dto->increasePoints) {
            $this->increasePointsOfAccount($dto->addedby);
        }

        return $alias;
    }
}
