<?php


namespace App\Mappers;


interface InventoryTableColumnMapper
{
    public function getTableExists(MappingTableInterface $table);

    public function getColumnExists(MappingTableInterface $table, MappingColumnInterface $column);


}
