<?php

namespace Spatie\Php7to5;

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

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

        $php7code = file_get_contents($this->pathToPhp7Code);

        $php7Statements = $parser->parse($php7code);

        $traverser = $this->getTraverser();

        $php5Statements = $traverser->traverse($php7Statements);

        return (new \PhpParser\PrettyPrinter\Standard())->prettyPrintFile($php5Statements);
    }

    protected function getTraverser() : NodeTraverser
    {
        $traverser = new NodeTraverser();

        foreach (glob(__DIR__.'/NodeVisitors/*.php') as $nodeVisitorFile) {
            $className = pathinfo($nodeVisitorFile, PATHINFO_FILENAME);

            $fullClassName = '\\Spatie\\Php7to5\\NodeVisitors\\'.$className;

            $traverser->addVisitor(new $fullClassName());
        }

        return $traverser;
    }
}
