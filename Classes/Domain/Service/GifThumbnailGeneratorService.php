<?php
namespace NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Service;

/*
 * This file is part of the NeosRulez.Neos.Media.CliThumbnailGenerator package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Thumbnail;
use NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Model\ThumbnailGenerator\GifThumbnailGenerator;

/**
 * @Flow\Scope("singleton")
 */
class GifThumbnailGeneratorService
{

    /**
     * @Flow\Inject
     * @var GifThumbnailGenerator
     */
    protected $gifThumbnailGenerator;

    /**
     * @param Thumbnail $thumbnail
     * @return void
     */
    public function onThumbnailCreation(Thumbnail $thumbnail): void
    {
        if($this->gifThumbnailGenerator->canRefresh($thumbnail)) {
            $this->gifThumbnailGenerator->refresh($thumbnail);
        }
    }

}
