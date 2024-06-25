<?php
namespace NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Service;

/*
 * This file is part of the NeosRulez.Neos.Media.CliThumbnailGenerator package.
 */

use Neos\Flow\Annotations as Flow;
use mikehaertl\shellcommand\Command;

class GifService
{

    /**
     * @param string $temporaryLocalCopyFilename
     * @param string $fileName
     * @param int $width
     * @param int $height
     * @return string
     */
    public function processImage(string $temporaryLocalCopyFilename, string $fileName, int $width, int $height): string
    {
        $convertedFile = FLOW_PATH_TEMPORARY_BASE . '/' . $fileName;
        $command = new Command('convert ' . $temporaryLocalCopyFilename . ' -coalesce -resize ' . $width . 'x' . $height . ' -layers optimize -loop 0 ' . $convertedFile);
        if (!$command->execute()) {
            return $command->getError();
        } else {
            return $convertedFile;
        }
    }

}
