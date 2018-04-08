<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeVisitorAbstract;

/**
 * Removes the class constant visibility modifiers (PHP 7.1)
 */
class ClassConstantVisibilityModifiersRemover extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!($node instanceof ClassConst)) {
            return;
        }

        $node->flags = 0; // Remove constant modifier
    }
}
