<?php

namespace Spatie\Php7to5;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class Converter
{
    /** @var string */
    protected $pathToPhp7Code;

    public function __construct(string $pathToPhp7Code)
    {
        $this->pathToPhp7Code = $pathToPhp7Code;
    }

    public function saveAsPhp5(string $destination)
    {
        file_put_contents($destination, $this->getPhp5Code());
    }

    public function getPhp5Code() : string
    {
        ini_set('xdebug.max_nesting_level', 3000);

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP5);

        $php7code = file_get_contents($this->pathToPhp7Code);

        try {
            $php7Statements = $parser->parse($php7code);

            $traverser = new NodeTraverser();

            $traverser->addVisitor(new NullCoalesceReplacer());
            $traverser->addVisitor(new SpaceshipOperatorReplacer());
            $traverser->addVisitor(new ReturnTypesRemover());
            $traverser->addVisitor(new StrictTypesDeclarationRemover());
            $traverser->addVisitor(new ScalarTypeHintsRemover());
            $traverser->addVisitor(new EmptyDeclareStatementRemover());

            $php5Statements = $traverser->traverse($php7Statements);
        } catch (Error $e) {
            echo 'PARSE ERROR: ', $e->getMessage();
        }

        return (new \PhpParser\PrettyPrinter\Standard())->prettyPrintFile($php5Statements);
    }
}
