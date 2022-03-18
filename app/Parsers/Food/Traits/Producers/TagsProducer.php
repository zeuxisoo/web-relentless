<?php
namespace App\Parsers\Food\Traits\Producers;

use App\Parsers\Food\Ast\Expressions\TagsExpression;

trait TagsProducer {

    protected function produceTagsExpression(TagsExpression $node): array {
        $tags = [];

        foreach($node->values as $tagNode) {
            $tags[] = $this->produce($tagNode);
        }

        return $tags;
    }

}
