<?php

namespace luismacayo\RacFormater\models;


class Producto
{
    public $nombre;
    public $precio;
    public $stock;
    public $errors = [];

    public function validate()
    {
        $valid = true;
        $this->errors = [];

        if (empty($this->nombre)) {
            $this->errors['nombre'] = 'El nombre no puede estar vacío.';
            $valid = false;
        }

        if ($this->precio <= 0) {
            $this->errors['precio'] = 'El precio debe ser mayor que cero.';
            $valid = false;
        }

        if ($this->stock < 0) {
            $this->errors['stock'] = 'El stock no puede ser negativo.';
            $valid = false;
        }

        return $valid;
    }

    public function calcularTotal()
    {
        return $this->precio * $this->stock;
    }
}