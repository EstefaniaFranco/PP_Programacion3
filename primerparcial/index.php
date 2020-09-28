<?php
require_once __DIR__ . '\vendor\autoload.php';
require_once './lib/Response.php';
require_once './lib/fileManager.php';
require_once './clases/usuario.php';
require_once './clases/login.php';
require_once './clases/precios.php';
require_once './clases/ingreso.php';

use \Firebase\JWT\JWT;

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO']  : '';

$response = new Response();

switch ($path) {
    case '/registro':
        if ($method == 'POST') {
            if (isset($_POST['email']) && isset($_POST['tipo']) && isset($_POST['password'])) {
                $rta = Usuario::CheckRegistro($_POST['email'], $_POST['password'], $_POST['tipo']);

                if ($rta == 'valido') {
                    $usuario = new Usuario($_POST['email'], $_POST['password'], $_POST['tipo']);
                    $response->data = $usuario->SaveRegistro();
                    $response->status = 'SUCCESS';
                } else {
                    $response->data = $rta;
                }
            } else {
                $response->data = 'No se pudo cargar el registro. Ingrese email,password y tipo correctos.';
            }
        } else {
            $response->data = "Metodo no soportado";
        }
        echo json_encode($response);
        break;

    case '/login':
        if ($method == 'POST') {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $response->data = Login::generateJWT($_POST['email'], $_POST['password']);
                $response->status = 'SUCCESS';
            } else {
                $response->data = "Debe cargar Email y Password para ingresar";
            }
        } else {
            $response->data = "Metodo no soportado";
        }
        echo json_encode($response);
        break;

    case '/precio':
        if ($method == 'POST') {
            $usuario = Login::decodeJWT();
            if ($usuario) {
                if (isset($_POST['precio_hora']) && isset($_POST['precio_estadia']) && isset($_POST['precio_mensual'])) {
                    if ($usuario->tipo == 'admin') {
                        $precio = new Precios($_POST['precio_hora'], $_POST['precio_estadia'], $_POST['precio_mensual']);
                        $response->data  = $precio->SavePrecio();
                        $response->status = 'SUCCESS';
                    } else {
                        $response->data = 'No esta autorizado para realizar esta accion.';
                    }
                } else {
                    $response->data = 'No se pudo cargar los precios. Faltan datos.';
                }
            } else {
                $response->data = 'No esta autorizado para realizar esta accion.';
            }
        } else {
            $response->data = "Metodo no soportado";
        }
        echo json_encode($response);
        break;

    case '/ingreso':

        switch ($method) {
            case 'GET':
                $usuario = Login::decodeJWT();
                if ($usuario) {
                    if (isset($_GET['patente'])) {
                        $response->data = Ingreso::Readingreso($_GET['patente']);
                        $response->status = 'SUCCESS';
                    } else {
                        $response->data = Ingreso::Readingreso(1);
                        $response->status = 'SUCCESS';
                    }
                } else {
                    $response->data = 'No esta autorizado para realizar esta accion.';
                }

                break;

            case 'POST':
                $usuario = Login::decodeJWT();
                if ($usuario) {
                    if (isset($_POST['patente']) && isset($_POST['tipo'])) {
                        if ($usuario->tipo == 'user') {
                            $response->data  = Ingreso::SaveIngreso($_POST['patente'], $_POST['tipo']);
                            $response->status = 'SUCCESS';
                        } else {
                            $response->data = 'No esta autorizado para realizar esta accion.';
                        }
                    } else {
                        $response->data = 'No se pudo realizar el ingreso. Faltan datos. ';
                    }
                } else {
                    $response->data = 'No esta autorizado para realizar esta accion.';
                }

                break;

            default:
                $response->data = "Metodo no soportado.";
                break;
        }

        echo json_encode($response);
        break;

    default:
        $response->data = "Path Invalido";
        echo json_encode($response);

        break;
}
