<?php

namespace SharepointPhp\Result;

abstract class AbstractResult  implements \Iterator 
{
    private $position = 0;
    
    protected $data;
    
    public function __construct(Array $result)
    {
        $this->parseData($result);
        
        $this->position = 0;
    }
    
    abstract function parseData($result);
    
    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->data[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->data[$this->position]);
    }    
    
}