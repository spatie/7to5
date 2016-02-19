<?php

namespace Spatie\Php7to5;

use PhpParser\Node;
use PhpParser\Node\Stmt\DeclareDeclare;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

class StrictTypesDeclarationRemover extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof DeclareDeclare) {
            if ($node->key === 'strict_type') {
                return NodeTraverser::REMOVE_NODE;
            }
        }
    }
}
