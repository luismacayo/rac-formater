<?php

namespace luismacayo\RacFormater\tests\unit\models;

use luismacayo\RacFormater\models\Producto;

class ProductoTest extends \Codeception\Test\Unit
{
    public function testAtributos()
    {
        $producto = new Producto();
        $producto->nombre = 'Producto de prueba';
        $producto->precio = 10.50;
        $producto->stock = 5;

        $this->assertEquals('Producto de prueba', $producto->nombre);
        $this->assertEquals(10.50, $producto->precio);
        $this->assertEquals(5, $producto->stock);
    }

    public function testValidacion()
    {
        $producto = new Producto();
        $producto->nombre = '';
        $producto->precio = -1;
        $producto->stock = -1;

        $this->assertFalse($producto->validate());
        $this->assertArrayHasKey('nombre', $producto->errors);
        $this->assertArrayHasKey('precio', $producto->errors);
        $this->assertArrayHasKey('stock', $producto->errors);
    }

    public function testCalcularTotal()
    {
        $producto = new Producto();
        $producto->precio = 10;
        $producto->stock = 3;

        $this->assertEquals(30, $producto->calcularTotal());
    }
}