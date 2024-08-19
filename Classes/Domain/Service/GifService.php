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
     * @param int|null $height
     * @return string
     */
    public function processImage(string $temporaryLocalCopyFilename, string $fileName, int $width, int|null $height = null): string
    {
        $convertedFile = FLOW_PATH_TEMPORARY_BASE . '/' . $fileName;
        if($height !== null) {
            $command = new Command('convert ' . $temporaryLocalCopyFilename . ' -coalesce -resize ' . $width . 'x' . $height . ' -layers optimize -loop 0 ' . $convertedFile);
        } else {
            $command = new Command('convert ' . $temporaryLocalCopyFilename . ' -coalesce -resize ' . $width . ' -layers optimize -loop 0 ' . $convertedFile);
        }
        if (!$command->execute()) {
            return $command->getError();
        }
        return $convertedFile;
    }

}
