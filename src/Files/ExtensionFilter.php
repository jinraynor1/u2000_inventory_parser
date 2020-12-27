<?php


namespace App\Files;


class ExtensionFilter extends \FilterIterator
{
    protected $extensions = array();


    public function __construct($iterator, array $extensions)
    {
        parent::__construct($iterator);
        $this->extensions = $extensions;

    }

    public function accept()
    {
        /**
         * @var $fileinfo \SplFileInfo
         */
        $fileinfo = $this->current();
        return in_array($fileinfo->getExtension(),$this->extensions);

    }
}