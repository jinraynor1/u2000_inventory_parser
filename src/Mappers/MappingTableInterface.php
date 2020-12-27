<?php


namespace App\Mappers;


interface MappingTableInterface
{
    public function getName();

    /**
     * @return MappingColumnInterface[]
     */
    public function getColumns();

    /**
     * @return MappingIndexInterface[]
     */
    public function getIndexes();
    public function appendColumn(MappingColumnInterface $mappingColumn);
    public function columnExist(MappingColumnInterface $mappingColumn);
    public function indexExist(MappingIndexInterface $mappingIndex);

}
