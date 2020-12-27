<?php


namespace App\Mappers;


class MappingIndex implements MappingIndexInterface
{
    private $indexName;

    /**
     * @var MappingColumnInterface[]
     */
    private $columns;

    public function __construct($indexName, $columns = array())
    {
        $this->indexName = $indexName;
        $this->columns = $columns;
    }
    public function getName()
    {
        return $this->indexName;
    }

    public function setName($indexName)
    {
        $this->indexName= $indexName;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function appendColumn(MappingColumnInterface $column)
    {
        $this->columns[] = $column;
    }

    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

}