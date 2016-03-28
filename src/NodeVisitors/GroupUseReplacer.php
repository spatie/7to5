<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class GroupUseReplacer extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Node\Stmt\GroupUse) {
            return;
        }

        $nodePrefixParts = $node->prefix->parts;

        $seperateUseStatements = array_map(function ($useNode) use ($nodePrefixParts) {
            return $this->createUseNode($nodePrefixParts, $useNode);
        }, $node->uses);

        return $seperateUseStatements;
    }

    protected function createUseNode(array $nodePrefixParts, Node $useNode)
    {
        $fullClassName = array_merge($nodePrefixParts, [$useNode->name]);

        $nameNode = new Node\Name($fullClassName);

        $alias = ($useNode->alias == $useNode->name) ? null : $useNode->alias;

        $useNode = new Node\Stmt\Use_([new Node\Stmt\UseUse($nameNode, $alias)], $useNode->type);

        return $useNode;
    }
}
