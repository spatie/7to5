<?php

namespace Spatie\Php7to5;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\Spaceship;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\NodeVisitorAbstract;

class SpaceshipOperatorReplacer extends NodeVisitorAbstract
{
    /**
     * @inheritdoc
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Spaceship) {
            return;
        }

        /**
         * Replacing
         * $a <=> $b
         * with
         * $a < $b ? -1 : ($a == $b ? 0 : 1)
         */

        $attributes = $node->getAttributes();

        $smaller = new UnaryMinus(new LNumber(1, $attributes), $attributes);
        $equal = new LNumber(0, $attributes);
        $larger = new LNumber(1, $attributes);

        $isEqual = new Equal($node->left, $node->right, $attributes);
        $isSmaller = new Smaller($node->left, $node->right, $attributes);

        $else = new Ternary($isEqual, $equal, $larger, $attributes);
        return new Ternary($isSmaller, $smaller, $else, $attributes);
    }
}
