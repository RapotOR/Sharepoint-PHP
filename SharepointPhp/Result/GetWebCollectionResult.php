<?php

namespace SharepointPhp\Result;

class GetWebCollectionResult extends AbstractResult 
{
    public function parseData($result)
    {
        foreach( $result['GetWebCollectionResult']['Webs']['Web'] as $item)
        {
            $this->data[] = new ResultItem($item, '!');
        }
    }
}