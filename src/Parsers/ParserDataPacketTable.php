<?php


namespace App\Parsers;


class ParserDataPacketTable   extends AbstractInventoryParser implements InventoryParserInterface
{
    public function parse()
    {
        // TODO: Implement parse() method.
    }

    /**
     * @param \SplFileInfo $fileInfo
     * @return bool
     */
    public function detect(\SplFileInfo $fileInfo)
    {
        $z = new \XMLReader;
        $z->open($fileInfo->getRealPath());

        $z->read();

        if($z->name!= 'DATAPACKET'){
            return false;
        }
        while ($z->read() && $z->nodeType != \XMLReader::END_ELEMENT) {
        }


        return $z->name == 'ROWDATA';
    }
}
