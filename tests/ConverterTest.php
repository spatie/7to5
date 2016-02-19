<?php

namespace Spatie\Php7to5\Test;

use Spatie\Php7to5\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_remove_scalar_type_hints()
    {
        $converter = new Converter($this->getStub('it-can-remove-scalar-type-hints/php7.php'));

        $php5code = $converter->getPhp5Code();

        $this->assertSame($this->getStubContent('it-can-remove-scalar-type-hints/php5.php'), $php5code);
    }

    /** @test */
    public function it_can_remove_return_types()
    {
        $converter = new Converter($this->getStub('it-can-remove-return-types/php7.php'));

        $php5code = $converter->getPhp5Code();

        $this->assertSame($this->getStubContent('it-can-remove-return-types/php5.php'), $php5code);
    }

    protected function getStub(string $name) : string {
        return __DIR__ . '/stubs/' . $name;
    }

    protected function getStubContent(string $name) : string {
        return file_get_contents($this->getStub($name));
    }
}
