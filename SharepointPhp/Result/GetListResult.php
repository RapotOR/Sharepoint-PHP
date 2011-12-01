<?php

namespace SharepointPhp\Result;

class GetListResult extends AbstractResult 
{
    public function parseData($result)
    {
        foreach( $result['GetListResult'] as $item)
        {
            $this->data[] = new ResultItem( $item );
        }
    }
}