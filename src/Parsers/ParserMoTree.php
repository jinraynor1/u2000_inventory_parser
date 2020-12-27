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
     * @var MappingColumn
     */
    private $columnMBTS;

    /**
     * @var MappingColumn
     */
    private $columnDate;

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


        //move to first mo
        while ($this->z->read() && $this->z->name != 'MO') ;

        // move to inner MO
        while ($this->z->read() && $this->z->name != 'MO' ) {

            //loop the <attr> tag before the inner mo
            if($this->z->name =='attr' && $this->z->nodeType != \XMLReader::END_ELEMENT){

                // extract mbts column
                if( $this->z->getAttribute("name")  == 'name'){
                    $this->columnMBTS = new MappingColumn("mbts");
                    $this->columnMBTS->setValue($this->z->readString());
                }

            }
        }
        $this->columnDate = new MappingColumn("registerDate");
        $this->columnDate->setValue(date('Y-m-d H:i:s'));
        $this->columnDate->setType(MappingColumn::COLUMN_TYPE_DATE);

        $this->position = 0;
    }

    /**
     * @return MappingTableInterface
     */
    function current()
    {
        $simple_xml =  simplexml_import_dom($this->doc->importNode($this->z->expand(), true));

        $table = new MappingTable("mo_tree");

        $table->columnMBTS->setValue($this->columnMBTS->getValue());
        $table->columnDate->setValue($this->columnDate->getValue());


        //$table->appendColumn($this->columnMBTS);
        //$table->appendColumn($this->columnDate);

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
        return $this->z->nodeType != \XMLReader::END_ELEMENT &&   $this->z->name === 'MO';
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
