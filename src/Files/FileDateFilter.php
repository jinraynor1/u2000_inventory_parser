<?php


namespace App\Files;


class FileDateFilter extends \FilterIterator
{
    protected $from_unix;
    protected $to_unix;

    public function __construct($iterator, $from_unix, $to_unix)
    {
        parent::__construct($iterator);
        $this->from_unix = $from_unix;
        $this->to_unix = $to_unix;
    }

    public function accept()
    {
        /**
         * @var $fileinfo \SplFileInfo
         */
        $fileinfo = $this->current();
        //echo "{$fileinfo->getBasename()} ----  {$fileinfo->getMTime()} >= $this->from_unix && {$fileinfo->getMTime()} <= $this->to_unix \n";
        return $fileinfo->getMTime() >= $this->from_unix && $fileinfo->getMTime() <= $this->to_unix;
    }
}