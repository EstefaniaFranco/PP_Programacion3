<?php
class Usuario{
    public $email;
    public $password;
    public $tipoUsuario;

    public function __construct($email, $pass, $tipo)
    {
        $this->email = $email;
        $this->password = $pass;
        $this->tipoUsuario = $tipo;
    }

    public function SaveRegistro(){        
        return FileManager::Save('data/users.json', $this);
    }

    public static function Find($email, $password)
    {
        $return = null;
        $array = FileManager::Read('data/users.json');  
        foreach ($array as $registro) {
            if($registro->email == $email && $registro->password == $password){      
                $return = $registro;
                break;
            }
        }
        return $return;
    }


    public static function CheckRegistro($email, $clave, $tipo){
        $retorno = 'valido';
        $user = Usuario::Find($email, $clave);

        if (! is_null($user)){
            if($user->email == $email){
                $retorno = 'El email ya se encuentra en el registro.';
            }
        }
        elseif ($tipo != 'admin' && $tipo != 'user') { 
            $retorno = 'Tipo de Registro Invalido. (Ingrese admin o user).';    
        } 
    
        return $retorno;
    }


}