<?php


namespace App\Loaders;


use App\Files\ControlFilesDetailedResult;
use App\Mappers\MappingTableInterface;

class LoaderResult
{
    private $result = array();

    private $skipped = 0;
    private $success = 0;
    private $failed = 0;



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

    private function initTableResult(MappingTableInterface $table)
    {
        if (!isset($this->result[$table->getName()]))
            $this->result[$table->getName()] = array();

        if (!isset($this->result[$table->getName()]['success']))
            $this->result[$table->getName()]['success'] = 0;

        if (!isset($this->result[$table->getName()]['failed']))
            $this->result[$table->getName()]['failed'] = 0;

        if (!isset($this->result[$table->getName()]['skipped']))
            $this->result[$table->getName()]['skipped'] = 0;

    }

    public function addSkipeed(MappingTableInterface $table)
    {
        $this->initTableResult($table);
        $this->result[$table->getName()]['skipped']++;
        $this->skipped ++;
    }

    public function addSuccess(MappingTableInterface $table)
    {
        $this->initTableResult($table);
        $this->result[$table->getName()]['success']++;
        $this->success ++;
    }

    public function addFailed(MappingTableInterface $table)
    {
        $this->initTableResult($table);
        $this->result[$table->getName()]['failed']++;
        $this->failed ++;
    }

    /**
     * @return ControlFilesDetailedResult[]
     */
    public function getResultsDetailed()
    {
        $results = array();

        foreach ($this->result as $table_name => $table_results) {
            $results[] = new ControlFilesDetailedResult(
                $table_name, $table_results['skipped'], $table_results['success'], $table_results['failed']
            );
        }

        return $results;
    }




}