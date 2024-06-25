<?php
namespace NeosRulez\Neos\Media\CliThumbnailGenerator;

/*
 * This file is part of the NeosRulez.Neos.Media.CliThumbnailGenerator package.
 */

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Media\Domain\Service\ThumbnailService;
use NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Service\GifThumbnailGeneratorService;

class Package extends BasePackage
{

    /**
     * @param Bootstrap $bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(ThumbnailService::class, 'thumbnailCreated', GifThumbnailGeneratorService::class, 'onThumbnailCreation');
    }

}
