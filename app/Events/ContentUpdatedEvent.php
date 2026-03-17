<?php

namespace App\Events;

use App\Models\Media;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentUpdatedEvent
{
    use Dispatchable, SerializesModels;

    public $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }
}