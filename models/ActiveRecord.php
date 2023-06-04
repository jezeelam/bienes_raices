<?php

namespace Model;

class ActiveRecord {
      
    //Base de DATOS
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    //Errores 
    protected static $errores = [];

    //Definir la conexion a la BD 
    public static function setDB($database) {
        self::$db = $database;
    }
    public function guardar() {
        if(!is_null($this->id)) {
            // Actualizando
            $this->actualizar();
        } else {
            // Creando
            $this->crear();
        }
    }
    
    public function crear() {
        
        // Sanitizar los datos

        $atributos = $this->sanitizarAtributos();

        //Inserta en la BD
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' "; 
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        $resultado = self::$db->query($query);

        // Mensaje de exito o error
        if($resultado) {
            //Redireccionar al usuario luego del registro correcto
            header('Location: /admin?resultado=1');
        }

    }
    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "$key='$value'";
        }
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        $resultado = self::$db->query($query);

        if($resultado) {
            //Redireccionar al usuario luego del registro correcto
            header('Location: /admin?resultado=2');
        }
    }
    // Eliminar un registro
    public function eliminar() {
        
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);

        if($resultado) {
            $this->borrarImagen();
            header('Location: /admin?resultado=3');
        }


    }
    // Iddentificar y unir los atributos de la DB
    public function atributos(){
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos () {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }
    //Subida de Archivos
    public function setImagen($imagen) {
        //Elimina la Imagen previa 
        if(!is_null($this->id)) {
            // Comprobar si existe el archivo
            $this->borrarImagen();
        }
        // Asignar al atributo de imagen el nomobre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }
    //Elimina el archivo
    public function borrarImagen() {
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }
    //Validacion
    public static function getErrores() {
        return static::$errores;
    }

    public function validar() { 
        static::$errores = [];
        return static::$errores;
    }

    // Lista todos los registros
    public static function all() {
        $query = " SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSQL($query);
        
        return $resultado;
    }
    // Obtiene determinado numero de registros
    public static function get($cantidad) {
        $query = " SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

        $resultado = self::consultarSQL($query);
        
        return $resultado;
    }
    //Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = $id";

        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

    public static function consultarSQL($query) {
        //Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }
        //Liberar la Memoria
        $resultado->free();

        // Retornar los resultados
        return $array;
    }
    protected static function crearObjeto($registro) {
        $objeto = new static;
        foreach($registro as $key => $value) {
            if( property_exists($objeto, $key) ) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    //Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = []) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value; 
            }
        }
    }
}