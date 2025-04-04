<?php

namespace luismacayo\RacFormater\models;


class Producto
{
    public $nombre;
    public $precio;
    public $stock;

    public function calcularTotal()
    {
        return $this->precio * $this->stock;
    }
}