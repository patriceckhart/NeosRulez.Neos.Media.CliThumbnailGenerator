<?php
namespace NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Model\ThumbnailGenerator;

/*
 * This file is part of the NeosRulez.Neos.Media.CliThumbnailGenerator package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\ThumbnailGenerator\AbstractThumbnailGenerator;
use Neos\Media\Domain\Model\Document;
use Neos\Media\Domain\Model\Thumbnail;
use Neos\Media\Exception;

/**
 * A system-generated preview version of a Document (PDF, AI and EPS)
 */
class DocumentThumbnailGenerator extends AbstractThumbnailGenerator
{

    /**
     * @var NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Service\DocumentService
     * @Flow\Inject
     */
    protected $documentService;

    /**
     * The priority for this thumbnail generator.
     *
     * @var integer
     * @api
     */
    protected static $priority = 5;

    /**
     * @param Thumbnail $thumbnail
     * @return boolean
     */
    public function canRefresh(Thumbnail $thumbnail)
    {
        return (
            $thumbnail->getOriginalAsset() instanceof Document &&
            $this->isExtensionSupported($thumbnail)
        );
    }

    /**
     * @param Thumbnail $thumbnail
     * @return void
     * @throws Exception\NoThumbnailAvailableException
     */
    public function refresh(Thumbnail $thumbnail)
    {
        try {
            $filenameWithoutExtension = pathinfo($thumbnail->getOriginalAsset()->getResource()->getFilename(), PATHINFO_FILENAME);

            $temporaryLocalCopyFilename = $thumbnail->getOriginalAsset()->getResource()->createTemporaryLocalCopy();

            $width = $thumbnail->getConfigurationValue('width') ?: $thumbnail->getConfigurationValue('maximumWidth');
            $height = $thumbnail->getConfigurationValue('height') ?: $thumbnail->getConfigurationValue('maximumHeight');

            $convertedFile = $this->documentService->processDocument($temporaryLocalCopyFilename, rawurlencode($filenameWithoutExtension), $this->getOption('resolution'));

            $resource = $this->resourceManager->importResource($convertedFile);
            $thumbnail->setResource($resource);
            $thumbnail->setWidth($width);
            $thumbnail->setHeight($height);
            unlink($convertedFile);
        } catch (\Exception $exception) {
            $filename = $thumbnail->getOriginalAsset()->getResource()->getFilename();
            $sha1 = $thumbnail->getOriginalAsset()->getResource()->getSha1();
            $message = sprintf('Unable to generate thumbnail for the given document (filename: %s, SHA1: %s)', $filename, $sha1);
            throw new Exception\NoThumbnailAvailableException($message, 1433109652, $exception);
        }
    }
}