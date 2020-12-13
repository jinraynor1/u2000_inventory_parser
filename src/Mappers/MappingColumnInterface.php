<?php


namespace App\Mappers;


interface MappingColumnInterface
{
    public function getName();
    public function getValue();
    public function getType();
    public function setValue($value);
    public function setType($type);

}
