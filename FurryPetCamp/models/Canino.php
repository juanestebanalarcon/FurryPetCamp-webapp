<?php

namespace Model;

class Canino extends ActiveRecord {
    protected static $tabla = "caninos";
    protected static $columnasDB = ["id", "nombre", "edad", "descripcion", 
    "usuario_id"];

    public $id;
    public $nombre;
    public $edad;
    public $descripcion;
    public $usuario_id;

    public function __construct($args = []) {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->edad = $args["edad"] ?? "";
        $this->descripcion = $args["descripcion"] ?? "";
        $this->usuario_id = $args["usuario_id"] ?? "";
    }

}