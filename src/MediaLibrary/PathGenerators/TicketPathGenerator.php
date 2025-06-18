<?php

namespace nextdev\nextdashboard\MediaLibrary\PathGenerators;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TicketPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $ticketId = $media->model?->id ?? 'unknown';

        return "tickets/{$ticketId}/{$media->id}/";
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive-images/';
    }
}
