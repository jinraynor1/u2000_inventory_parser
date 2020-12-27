<?php


namespace App\Files;


class SearchLocalFiles implements SearchFilesInterface
{


    public function findFiles($path, \DateTime $fromTime, \DateTime $toTime, $extension)
    {
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);

        $iterator = new FileDateFilter($iterator,$fromTime->getTimestamp(), $toTime->getTimestamp());

        $extensions = array($extension);
        $iterator = new ExtensionFilter($iterator,$extensions);

        return iterator_to_array($iterator);

    }
}