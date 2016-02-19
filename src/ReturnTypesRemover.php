<?php

namespace Spatie\Php7to5;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\NodeVisitorAbstract;

class ReturnTypesRemover extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof Param) {
            if (in_array($node->type, ['int', 'float', 'string', 'bool'])) {
                $node->type = null;
            }
        }
    }
}
