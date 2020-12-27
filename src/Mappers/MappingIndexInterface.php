<?php


namespace App\Mappers;


interface MappingIndexInterface
{
    public function getName();

    public function getColumns();
    public function appendColumn(MappingColumnInterface $column);
    public function setColumns(array $columns);


}
