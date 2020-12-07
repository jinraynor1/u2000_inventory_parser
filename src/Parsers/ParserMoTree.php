<?php


namespace App\Parsers;


use App\Mappers\MappingColumn;
use App\Mappers\MappingTable;
use App\Mappers\MappingTableInterface;

class ParserMoTree extends AbstractInventoryParser implements InventoryParserInterface, \Iterator
{
    /**
     * @var \SplFileInfo
     */
    private $fileInfo;
    /**
     * @var \XMLReader
     */
    private $z;
    /**
     * @var \DOMDocument
     */
    private $doc;

    /**
     * @var int
     */
    private $position=0;

    function __construct(\SplFileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;

    }

    function rewind()
    {
        $this->z = new \XMLReader;
        $this->doc = new \DOMDocument;
        $this->z->open($this->fileInfo->getRealPath());


        while ($this->z->read() && $this->z->nodeType != \XMLReader::END_ELEMENT) {
        }

        while ($this->z->read() && $this->z->name != 'MO') {
        }

        $this->position = 0;
    }

    /**
     * @return MappingTableInterface
     */
    function current()
    {
        $simple_xml =  simplexml_import_dom($this->doc->importNode($this->z->expand(), true));
        $table = new MappingTable("mo_tree");
        foreach ($simple_xml as $item){
           // $childrens = $item->children();

            $column = new MappingColumn((string)$item->attributes()->name);
            $column->setValue((string)$item[0]);
            $table->appendColumn($column);

        }

        return $table;

    }

    function key()
    {
        return $this->position;
    }

    function next()
    {
        $this->z->next('MO');
        $this->position++ ;
    }

    function valid()
    {
        return $this->z->name === 'MO';
    }

    public function parse()
    {

    }

    /**
     * @return bool
     */
    public function detect()
    {
        $z = new \XMLReader;
        $z->open($this->fileInfo->getRealPath());


        while ($z->read() && $z->nodeType != \XMLReader::END_ELEMENT) {
        }

        while ($z->read() && $z->name != 'MO') {
        }

        return $z->name == 'MO';
    }

}
