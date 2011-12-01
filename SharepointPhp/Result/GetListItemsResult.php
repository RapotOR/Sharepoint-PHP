<?php

namespace SharepointPhp\Result;

class GetListItemsResult extends AbstractResult
{
    public function parseData($result)
    {
        foreach( $result['GetListItemsResult']['listitems']['data']['row'] as $item)
        {
            $this->data[] = new ResultItem( $item );
        }
    }
}