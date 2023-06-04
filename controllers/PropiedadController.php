<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController {
    public static function index(Router $router) {

        $propiedades = Propiedad::all();

        $vendedores = Vendedor::all();
        // Muestra Mensaje condicional
        $resultado = $_GET['resultado'] ?? null;
        
        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
    }
    public static function crear(Router $router) {
        
        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        // Arreglo con mensaje de errores
        $errores = Propiedad::getErrores();

        
        // Ejecutar el codigo despues de que el usuario envia formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Crea una nueva instancia 
            $propiedad = new Propiedad($_POST['propiedad']);
            
            // Generar nombre unico
            $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg"; 
            // Settear la imagen
            // Realiza un resize a la imagen con intervention 
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
                $propiedad->setImagen($nombreImagen);
            }         

            // Valiadar errores
            $errores = $propiedad->validar();

            // Revisar que el array este vacio
            if (empty($errores)) {
                // Subida de Archivos //
                if (!is_dir(CARPETA_IMAGENES)) {
                    mkdir(CARPETA_IMAGENES);
                }
                //Guardar la imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);
                // Guarda en la Base de Datos
                $propiedad->guardar();
            } 
        }

        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }
    public static function actualizar(Router $router) {
        
        $id = validarORedireccionar('/admin');
        $propiedad = Propiedad::find($id);
        $vendedores = Vendedor::all();

        $errores = Propiedad::getErrores();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asignar los atributos
            $args = $_POST['propiedad'];
    
            $propiedad->sincronizar($args);
    
            //Validacion
            $errores = $propiedad->validar();
    
            // Generar nombre unico
            $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg"; 
    
            //Subida de Archivos
            if($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
                $propiedad->setImagen($nombreImagen);
            }         
    
    
            if (empty($errores)) {
                // Almacenar en Disco Duro
                if ($_FILES['propiedad']['tmp_name']['imagen']) {
                    # code...
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $propiedad->guardar();
    
            }
      
        }

        $router->render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
    }
    public static function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
    
            if ($id) {
                $tipo = $_POST['tipo'];
    
                if(validarTipoContenido($tipo)) {
                    //Compara lo que vamos a eliminar
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                } 
            }
        }
    }

}