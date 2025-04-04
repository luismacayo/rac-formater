<?php

namespace luismacayo\RacFormater\tests\unit\models;

use luismacayo\RacFormater\models\Producto;

class ProductoTest extends \Codeception\Test\Unit
{
    public function testAtributos()
    {
        $modelo = new Producto();
        $modelo->nombre = 'Prueba';
        $this->assertEquals('Prueba', $modelo->nombre);
    }
}