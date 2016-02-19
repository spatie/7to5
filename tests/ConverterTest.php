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

    /** @test */
    public function it_can_remove_declarations_statement()
    {
        $converter = new Converter($this->getStub('it-can-remove-declarations-statement/php7.php'));

        $php5code = $converter->getPhp5Code();

        $this->assertSame($this->getStubContent('it-can-remove-declarations-statement/php5.php'), $php5code);
    }

    /** @test */
    public function it_can_replace_null_coalesce_operators()
    {
        $converter = new Converter($this->getStub('it-can-replace-null-coalesce-operators/php7.php'));

        $php5code = $converter->getPhp5Code();

        $this->assertSame($this->getStubContent('it-can-replace-null-coalesce-operators/php5.php'), $php5code);
    }

    /** @test */
    public function it_can_replace_spaceship_operators()
    {
        $converter = new Converter($this->getStub('it-can-replace-spaceship-operators/php7.php'));

        $php5code = $converter->getPhp5Code();

        $this->assertSame($this->getStubContent('it-can-replace-spaceship-operators/php5.php'), $php5code);
    }

    protected function getStub(string $name) : string {
        return __DIR__ . '/stubs/' . $name;
    }

    protected function getStubContent(string $name) : string {
        return trim(file_get_contents($this->getStub($name)));
    }
}
