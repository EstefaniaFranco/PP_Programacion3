<?php
require_once '.\vendor\autoload.php';
require_once './clases/usuario.php';

use \Firebase\JWT\JWT;

class Login{
    private static $key = 'primerparcial';

    public static function generateJWT($email, $clave){       
        $retorno = "Email o Clave Invalidos.";
        $user = Usuario::Find($email, $clave);

        if(! is_null($user))
        {
            $payload = array (                        
                "email" => $email,                        
                "tipo" => $user->tipoUsuario,                        
            );
            $retorno = JWT::encode($payload,Login::$key);
        }

        return $retorno;
    }

    public static function decodeJWT()
    {
        $token = $_SERVER['HTTP_TOKEN'];

        try {
            $jwt = JWT::decode($token, Login::$key, array("HS256"));
            return $jwt;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
  
}