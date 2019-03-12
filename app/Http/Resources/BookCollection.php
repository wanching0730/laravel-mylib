<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            // only return attributes stated in BookResource
            'data' => BookResource::collection($this->collection), 
            'meta' => [
                'time' => date('U'),
            ],
        ];
    }
}
