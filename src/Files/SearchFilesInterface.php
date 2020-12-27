<?php


namespace App\Files;


interface SearchFilesInterface
{
    public function findFiles($path, \DateTime  $fromTime, \DateTime $toTime, $extension);
}