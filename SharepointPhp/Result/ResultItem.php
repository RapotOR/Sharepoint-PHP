<?php

namespace SharepointPhp\Result;

class ResultItem 
{
    protected $data;
    protected $prefix = '!ows_';
    protected $separator = ';#';
    
    public function __construct($item)
    {
        $this->data = $item;
    }
    
    public function has($key)
    {
        return isset($this->data[ $this->prefix . $key ]);
    }
    
    public function val($value)
    {
        if($i = strpos($value, $this->separator) === false)
            return $value;
        
        return substr($value, $i+1);
    }
    
    public function keys()
    {
        return array_keys($this->data);
    }
    
    public function __get($key)
    {
        if($this->has($key))
            return $this->val( $this->data[ $this->prefix . $key ] );
        
        return false;
    }
}