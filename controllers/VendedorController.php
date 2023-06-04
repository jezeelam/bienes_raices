<?php

namespace Controllers;
use MVC\Router;
use Model\Vendedor;


class VendedorController {
    public static function crear(Router $router) {
        $vendedor = new Vendedor;

        $errores = Vendedor::getErrores(); 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Crear una nueva instancia
            $vendedor = new Vendedor($_POST['vendedor']);
            
            // Validar que no haya campos vacios
            $errores = $vendedor->validar(); 
        
            // No hay errores 
            if(empty($errores)) {
                $vendedor->guardar();
            }
        }
        $router->render('vendedores/crear', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);
    }
    public static function actualizar( Router $router) {

        $errores = Vendedor::getErrores();
        $id = validarORedireccionar('/admin');

        // Obtener datos del vendedor a actualizar 

        $vendedor = Vendedor::find($id);

        // Ejecutar el codigo despues de que el usuario envia formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Asignar los valores
            $args = $_POST['vendedor'];

            $vendedor->sincronizar($args);

            //Validacion
            $errores = $vendedor->validar();

            if(empty($errores)) {
                $vendedor->guardar();
            }

        }

        $router->render('vendedores/actualizar', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }
    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Validar id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                // Valida el tipo a Eliminar
                $tipo = $_POST['tipo'];   
                
                if (validarTipoContenido($tipo)) {
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar();
                }
            }

        }
    }
}