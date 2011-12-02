<?php

namespace SharepointPhp\Result;

class ResultItem 
{
    protected $data;
    protected $prefix;
    protected $separator;
    
    public function __construct($item, $prefix = '!ows_', $separator = ';#')
    {
        $this->data = $item;
        $this->prefix = $prefix;
        $this->separator = $separator;
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
        return str_replace($this->prefix, '', array_keys($this->data));
    }
    
    public function __get($key)
    {
        if($this->has($key))
            return $this->val( $this->data[$this->prefix . $key] );
        
        throw new \Exception( "Parameter " . $key . " does not exist. Parameters are : ". implode(',', $this->keys()), 0 );
    }
    
    public function __call($name, $arguments = false)
    {
        return $this->__get($name);
    }
}