<?php


namespace App\Mappers;


interface MappingTableInterface
{
    public function getName();

    /**
     * @return MappingColumn[]
     */
    public function getColumns();
    public function appendColumn(MappingColumnInterface $mappingColumn);


}
