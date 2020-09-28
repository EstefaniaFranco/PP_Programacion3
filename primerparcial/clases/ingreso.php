<?php
require_once './lib/fileManager.php';

class Ingreso{
    public $patente;
    public $fecha_ingreso;
    public $tipo;

    public function __construct( $patente, $tipo)
    {
        $this->patente = $patente;
        $this->tipo = $tipo;

        $fecha = getdate();
        $this->fecha_ingreso = $fecha['mday'] . '-' . $fecha['month'] . '-' . $fecha['year'] . '   ' . $fecha['hours'] . ':' . $fecha['minutes'];

    }

    public static function SaveIngreso($patente, $tipo){  
        $retorno = '';
        $auto = new Ingreso($patente, $tipo);

        if($tipo != 'hora' && $tipo != 'estadia' && $tipo != 'mensual'){
            $retorno = 'EL tipo no es valido.';
        }else{
            $retorno = FileManager::Save('data/autos.json', $auto);
        }
        return $retorno;
    }

    public static function Readingreso($patente){
        $lista = FileManager::Read('data/autos.json');
        $retorno = '';

        if($patente == 1){
            $retorno = $lista;
        }else{
            foreach ($lista as $value)
             {
                 if ($value->patente == $patente)
                 {
                    $retorno = $value;
                    break;
                 }
            }
        }
        return $retorno;
    }

}