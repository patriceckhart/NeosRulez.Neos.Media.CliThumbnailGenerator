<?php
namespace NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Model\ThumbnailGenerator;

/*
 * This file is part of the NeosRulez.Neos.Media.CliThumbnailGenerator package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Model\ImageInterface;
use Neos\Media\Domain\Model\ThumbnailGenerator\AbstractThumbnailGenerator;
use Neos\Media\Domain\Model\Document;
use Neos\Media\Domain\Model\Thumbnail;
use Neos\Media\Exception;
use NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Service\GifService;

/**
 * A Thumbnailgenerator that force Imagick cli for animated Gif
 */
class GifThumbnailGenerator extends AbstractThumbnailGenerator
{

    /**
     * @var GifService
     * @Flow\Inject
     */
    protected $gifService;

    /**
     * The priority for this thumbnail generator.
     *
     * @var integer
     * @api
     */
    protected static $priority = 6;

    /**
     * @param Thumbnail $thumbnail
     * @return bool
     */
    public function canRefresh(Thumbnail $thumbnail): bool
    {
        return $this->isExtensionSupported($thumbnail);
    }

    /**
     * @param Thumbnail $thumbnail
     * @return void
     * @throws Exception\NoThumbnailAvailableException
     */
    public function refresh(Thumbnail $thumbnail): void
    {
        try {
            $width = $thumbnail->getConfigurationValue('width') ?: $thumbnail->getConfigurationValue('maximumWidth');
            $height = $thumbnail->getConfigurationValue('height') ?: $thumbnail->getConfigurationValue('maximumHeight');

            $temporaryLocalCopyFilename = $thumbnail->getOriginalAsset()->getResource()->createTemporaryLocalCopy();
            $fileName = str_replace(('.' . $thumbnail->getOriginalAsset()->getResource()->getFileExtension()), '', $thumbnail->getOriginalAsset()->getResource()->getFilename()) . '-' . $width . 'x' . $height . '.' . $thumbnail->getOriginalAsset()->getResource()->getFileExtension();

            $convertedFile = $this->gifService->processImage($temporaryLocalCopyFilename, $fileName, $width, $height);

            $resource = $this->resourceManager->importResource($convertedFile);
            $thumbnail->setResource($resource);
            $thumbnail->setWidth($width);
            $thumbnail->setHeight($height);
            unlink($convertedFile);
        } catch (\Exception $exception) {
            $filename = $thumbnail->getOriginalAsset()->getResource()->getFilename();
            $sha1 = $thumbnail->getOriginalAsset()->getResource()->getSha1();
            $message = sprintf('Unable to generate thumbnail for the given gif (filename: %s, SHA1: %s)', $filename, $sha1);
            throw new Exception\NoThumbnailAvailableException($message, 1433109652, $exception);
        }
    }
}
