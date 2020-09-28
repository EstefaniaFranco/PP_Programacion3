<?php
require_once './lib/fileManager.php';

class Precios{
    public $precioHora;
    public $precioEstadia;
    public $precioMensual;


    public function __construct($hora, $estadia, $mensual)
    {
        $this->precioHora = $hora;
        $this->precioMensual = $mensual;
        $this->precioEstadia = $estadia;
    }


    public function SavePrecio(){        
        return FileManager::Save('data/precios.json', $this);
    }


}