<?php


namespace App\Parsers;


abstract class AbstractInventoryParser implements InventoryParserInterface
{
    /**
     * @var InventoryTableColumnMapper
     */
    private $inventoryTableColumnMapper;

    public function __construct(InventoryTableColumnMapper $inventoryTableColumnMapper)
    {
        $this->inventoryTableColumnMapper = $inventoryTableColumnMapper;
    }

    public function validate()
    {

        $table = new MappingTable("test");
        $column = new MappingColumn("test");


        if ($this->inventoryTableColumnMapper->getTableExists($table)) {
            return false;
        }


        if ($this->inventoryTableColumnMapper->getColumnExists($table, $column)) {
            return false;
        }


    }


}
