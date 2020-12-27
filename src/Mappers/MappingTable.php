<?php


namespace App\Mappers;


class MappingTable implements MappingTableInterface
{
    private $tableName;

    /**
     * @var MappingColumnInterface[]
     */
    private $columns = array();

    /**
     * @var MappingIndexInterface[]
     */
    private $indexes = array();

    /**
     * @var MappingColumnInterface
     */
    public $columnMBTS;

    /**
     * @var MappingColumnInterface
     */
    public $columnDate;

    public function __construct($tableName, $columns = array(), $indexes = array())
    {
        $this->tableName = $tableName;


        $this->columnMBTS = new MappingColumn("mbts");
        $this->columnMBTS->setColumnLength(250);


        $this->columnDate = new MappingColumn("registerDate");
        $this->columnDate->setType(MappingColumn::COLUMN_TYPE_DATE);

        $this->appendColumn($this->columnMBTS);
        $this->appendColumn($this->columnDate);

        $this->appendIndex(
            new MappingIndex("idx_Def1", array(
                    $this->columnDate,
                    $this->columnMBTS
                )
            )
        );

        if ($columns)
            foreach ($columns as $column)
                $this->appendColumn($column);

        if ($indexes)
            foreach ($indexes as $index)
                $this->appendIndex($index);

    }

    public function getName()
    {
        return $this->tableName;
    }

    public function appendColumn(MappingColumnInterface $mappingColumn)
    {
        $this->columns[$mappingColumn->getName()] = $mappingColumn;
    }

    public function appendIndex(MappingIndexInterface $mappingIndex)
    {
        $this->indexes[$mappingIndex->getName()] = $mappingIndex;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @return array
     */
    public function getColumnsAsKeyValue()
    {
        $map = array();
        foreach ($this->columns as $column) {
            $map[$column->getName()] = $column->getValue();
        }
        return $map;
    }

    public function columnExist(MappingColumnInterface $mappingColumn)
    {
        return isset($this->columns[$mappingColumn->getName()]);

    }

    public function indexExist(MappingIndexInterface $mappingIndex)
    {
        return isset($this->indexes[$mappingIndex->getName()]);

    }


}
