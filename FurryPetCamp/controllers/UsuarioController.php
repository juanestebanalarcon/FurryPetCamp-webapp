<?php

namespace Controllers;
use MVC\Router;
use Model\Usuario;
use Model\Rol;
use Intervention\Image\ImageManagerStatic as Image;
use Model\Canino;

class UsuarioController {
    public static function index(Router $router) {
        $usuarios = Usuario::all();

        //Muestra mensaje condicional
        $resultado = $_GET["resultado"] ?? null;

        $router->render("usuarios/admin", [
            "usuarios"=>$usuarios,
            "resultado"=>$resultado
        ]);
    }

    public static function crear(Router $router) {
        $usuario = new Usuario;
        $roles = Rol::all();

        $errores = [];

        //Arreglo con mensaje de errores
        $errores = Usuario::getErrores();

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuario = new Usuario($_POST["usuario"]);

            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            if($_FILES["usuario"]["tmp_name"]["imagen"]) {
                $image = Image::make($_FILES["usuario"]["tmp_name"]["imagen"])->fit(800,600);
                $usuario->setImagen($nombreImagen);
            }

            $usuario->hashpassword();
            $errores = $usuario->validar();

            if(empty($errores)) {
                if(!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }

                $image->save(CARPETA_IMAGENES . $nombreImagen);

                $usuario->guardarUsuario();
            }
        }

        $router->render("usuarios/crear", [
            "usuario"=>$usuario,
            "roles"=>$roles,
            "errores"=>$errores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar("/usuarios/admin");
        $usuario = Usuario::find($id);
        $errores = Usuario::getErrores();
        $roles = Rol::all();

        if($_SERVER["REQUEST_METHOD"] === "POST") {

            //Asignar los atributos 
            $args = $_POST["usuario"];
            $usuario->sincronizar($args);
    
            //Validacion
            $errores = $usuario->validar();
    
            //Subida de archivos
            //Generar un nombre unico
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
    
            if($_FILES["usuario"]["tmp_name"]["imagen"]) {
                $image = Image::make($_FILES["usuario"]["tmp_name"]["imagen"])->fit(800,600);
                $usuario->setImagen($nombreImagen);
            }
    
            //Revisar que el array de errores este vacio
            if (empty($errores)) {
                // Almacenar la imagen
                if($_FILES["usuario"]["tmp_name"]["imagen"]) {
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                
                $usuario->guardarUsuario();
            }
        }

        $router->render("usuarios/actualizar", [
            "usuario" => $usuario,
            "errores" => $errores,
            "roles" => $roles
        ]);
    }

    public static function eliminar() {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            //Validar ID
            $id = $_POST["id"];
            $id = filter_var($id, FILTER_VALIDATE_INT);
    
            if($id) {
    
                $tipo = $_POST["tipo"];
    
                if(validarTipoContenido($tipo)) {
                    $usuario = Usuario::find($id);
                    $usuario->eliminarUsuario();
                }
            }
        }
    }

    public static function cliente(Router $router) {
        if(!isset($_SESSION)) {
            session_start();
        }
        $id = $_SESSION["id"];
        $caninos = Canino::caninos($id);
        //debuguear($caninos);

        $router->render("clientes/index", [
            "nombre"=>$_SESSION["nombre"],
            "id"=>$id,
            "caninos"=>$caninos
        ]);
    }

    public static function canino(Router $router) {
        $errores = Canino::getErrores();

        $router->render("clientes/canino", [
            "errores" => $errores
        ]);
    }

}