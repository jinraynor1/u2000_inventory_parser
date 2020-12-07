<?php


namespace App\Parsers;


interface InventoryParserInterface
{
    public function parse();

    public function detect();

}
