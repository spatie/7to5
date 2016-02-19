<?php

namespace Spatie\Php7to5;

use PhpParser\Node;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

class EmptyDeclareStatementRemover extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Declare_) {
            return;
        }

        $result = array_filter(array_map(function (DeclareDeclare $declare) {
            return $declare->key !== 'strict_types';
        }, $node->declares));

        if (empty($result)) {
            return NodeTraverser::REMOVE_NODE;
        }
    }
}
