<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BookResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, 
            'isbn' => $this->isbn, 
            'title' => $this->title, 
            'year' => $this->year,
            'authors' => new AuthorCollection($this->whenLoaded('authors')),
            'publisher' => new PublisherResource($this->whenLoaded('publisher'))
        ];
    }
}
