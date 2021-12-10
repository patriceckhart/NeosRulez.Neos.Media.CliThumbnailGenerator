<?php
namespace NeosRulez\Neos\Media\CliThumbnailGenerator\Domain\Service;

/*
 * This file is part of the NeosRulez.Neos.Media.CliThumbnailGenerator package.
 */

use Neos\Flow\Annotations as Flow;
use mikehaertl\shellcommand\Command;

class DocumentService
{

    /**
     * @param string $temporaryLocalCopyFilename
     * @param string $fileName
     * @param int $resolution
     * @return string
     */
    public function processDocument(string $temporaryLocalCopyFilename, string $fileName, int $resolution):string
    {
        $convertedFile = '/' . sys_get_temp_dir() . '/' . $fileName . '.png';
        $command = new Command('convert -density 60 ' . $temporaryLocalCopyFilename . '[0] -quality 90 -background white -alpha background -alpha off -resize ' . $resolution . 'x ' . $convertedFile);
        if (!$command->execute()) {
            return $command->getError();
        } else {
            return $convertedFile;
        }
    }

}