<?php


namespace App\Files;


class ControlFilesDetailedResult
{
    private $table_name = null;
    private $skipped = 0;
    private $success = 0;
    private $failed = 0;


    public function __construct( $table_name , $skipped, $success, $failed  )
    {

        $this->table_name = $table_name;
        $this->skipped = $skipped;
        $this->success = $success;
        $this->failed = $failed;
    }

    /**
     * @return null
     */
    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * @return int
     */
    public function getSkipped()
    {
        return $this->skipped;
    }

    /**
     * @return int
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @return int
     */
    public function getFailed()
    {
        return $this->failed;
    }



}