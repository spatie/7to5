<?php

namespace Spatie\Php7to5;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\DeclareDeclare;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

class NodeVisitor extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof DeclareDeclare) {
            if ($node->key === 'strict_type') {
                return NodeTraverser::REMOVE_NODE;
            }
        }

        if ($node instanceof Param) {

            if ($this->isScalar($node->type)) {
                $node->type = null;
            }

        }

        if ($node instanceof ClassMethod) {
            $node->returnType = null;
        }
    }

    public function isScalar( $string) {
        return in_array($string, ['int', 'integer', 'string', 'float', 'bool', 'boolean']);
    }
}
