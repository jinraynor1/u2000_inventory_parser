<?php


namespace App\Parsers;


use App\Mappers\MappingColumn;
use App\Mappers\MappingTable;
use DOMDocument;
use XMLReader;
use SplFileInfo;

class ParserDataPacketTable   extends AbstractInventoryParser implements InventoryParserInterface, \Iterator
{
    /**
     * @var SplFileInfo
     */
    private $fileInfo;
    /**
     * @var XMLReader
     */
    private $z;
    /**
     * @var DOMDocument
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

    /**
     * @var array
     */
    private $buffer_row;

    function __construct(\SplFileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;

    }

    public function parse()
    {
        // TODO: Implement parse() method.
    }

    /**
     * @return bool
     */
    public function detect()
    {
        $z = new XMLReader;
        $z->open($this->fileInfo->getRealPath());

        $z->read();

        if($z->name!= 'DATAPACKET'){
            return false;
        }
        while ($z->read() && $z->nodeType != XMLReader::END_ELEMENT) {
        }


        return $z->name == 'ROWDATA';
    }

    public function current()
    {
        if(empty($this->buffer_row)) {
            $simple_xml =  simplexml_import_dom($this->doc->importNode($this->z->expand(), true));

            //converting simplexml to  simple array because unsetting nodes in simplexml its buggy, maybe
            //we should use dom document but we are being lazy

            $json = json_encode($simple_xml);
            $this->buffer_row = json_decode($json,TRUE);

        }

        //get first element of the list
        $current_row = reset($this->buffer_row["ROWDATA"]["ROW"]);

        //remove one element so we decrease the list
        array_shift($this->buffer_row["ROWDATA"]["ROW"]);

        $table = new MappingTable($this->buffer_row["@attributes"]["attrname"]);

        $table->columnMBTS->setValue($this->columnMBTS->getValue());
        $table->columnDate->setValue($this->columnDate->getValue());


        //if no more elements empty the buffer
        if(empty($this->buffer_row["ROWDATA"]["ROW"])){
           $this->buffer_row = array();
       }

        //check if empty attributes it happens when no value is present for all fields
        if(!isset($current_row["@attributes"]))
            $attributes = $current_row;
        else
            $attributes = $current_row["@attributes"];

            foreach ($attributes as $attribute_key => $attribute) {
                $column = new MappingColumn($attribute_key);
                $column->setValue($attribute);
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
        // if the buffer is empty then move to the next table
        if(!$this->buffer_row){
            $this->z->next('TABLE');
        }

        $this->position++ ;
    }

    function valid()
    {
        return $this->z->name === 'TABLE';

    }

    public function rewind()
    {
        $this->z = new \XMLReader;
        $this->doc = new \DOMDocument;

        $this->z->open($this->fileInfo->getRealPath());

        while ($this->z->read() && $this->z->name != 'TABLE') {
            if($this->z->name =='NE' && $this->z->nodeType != \XMLReader::END_ELEMENT){


                // extract mbts column
                $this->columnMBTS = new MappingColumn("mbts");
                $this->columnMBTS->setValue($this->z->getAttribute("NEName"));

            }


        }
        $this->columnDate = new MappingColumn("registerDate");
        $this->columnDate->setValue(date('Y-m-d H:i:s'));
        $this->columnDate->setType(MappingColumn::COLUMN_TYPE_DATE);
        $this->position = 0;
    }
}
