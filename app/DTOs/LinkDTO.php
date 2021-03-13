<?php 

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;

class LinkDTO
{
    public int | null $id;
    public string | null $name;
    public string | null $link;
    public string | null $description;
    public Model | null $linkable;
    public Model | null $addedby;

    public function __invoke
    (
        $id = null,
        $name = null,
        $link = null,
        $description = null,
        $addedby = null,
        $linkable = null, 
    )
    {
        $self = new self();

        $self->id = $id;
        $self->name = $name;
        $self->link = $link;
        $self->description = $description;
        $self->linkable = $linkable;
        $self->addedby = $addedby;

        return $self;
    }

    public static function createFromArray(array|null $linkArray)
    {
        if (is_null($linkArray)) return [];
        $links = [];
        foreach ($linkArray as $link) {
            $links[] = (new LinkDTO)(
                name: $link->name,
                description: $link->description,
                link: $link->link,
            );
        }

        return $links;
    }
}