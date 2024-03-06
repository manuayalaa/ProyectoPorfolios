<?php

namespace App\Models;
use App\Controllers\BaseController;

require_once("DBAbstractModel.php");

class Proyectos extends DBAbstractModel
{
    //Campos: id	titulo	descripcion	usuarios_id	

    //Singleton
    private static $instancia;
    // Atributos de la clase Proyectos
    private  $id;
    private  $nombre;
    private  $descripcion;
    private  $fecha_inicio;
    private  $fecha_final;
    private  $usuarios_id;
    public static function getInstancia()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function get($id = '')
    {
    }

    public function getAllProyectos()
    {
        $this->query = "SELECT * FROM proyectos";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function getProyectosPorUsuariosId($id = '')
    {
        $this->query = "SELECT * FROM proyectos WHERE usuarios_id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        return $this->rows;
    }

    public function anadirProyecto($titulo, $descripcion, $usuarios_id)
    {
        // Comprobar primero si hay un usuario con el usuario_id

        $this->query = "SELECT * FROM usuarios WHERE id = :usuarios_id";
        $this->parametros['usuarios_id'] = $usuarios_id;
        $this->get_results_from_query();
        if (count($this->rows) == 0) {
            $this->mensaje = 'Usuario no encontrado';
            return;
        }
        
        
        $this->query = "INSERT INTO proyectos (titulo, descripcion, usuarios_id) VALUES (:titulo, :descripcion, :usuarios_id)";
        $this->parametros['titulo'] = $titulo;
        $this->parametros['descripcion'] = $descripcion;
        $this->parametros['usuarios_id'] = $usuarios_id;
        $this->get_results_from_query();
    }

    public function eliminarProyecto($id)
    {
        $this->query = "DELETE FROM proyectos WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
    }

   
    public function ocultarProyecto($id)
    {
        // Si el trabajo ya está a NULL, es decir que está visible, poner a 0, si no, poner a NULL
        $this->query = "SELECT * FROM proyectos WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        
        if ($this->rows[0]['visible'] == 1) {
            $this->query = "UPDATE proyectos SET visible = 0 WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
            $this->mensaje = 'Proyecto ocultado';
        } else {
            $this->query = "UPDATE proyectos SET visible = 1 WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
            $this->mensaje = 'Proyecto mostrado';
        }
    }

    public function set()
    {
        // Implementa tu lógica para agregar un nuevo usuario si es necesario
    }

    public function edit()
    {
        // Implementa tu lógica para editar un usuario si es necesario
    }

    public function delete()
    {
        // Implementa tu lógica para eliminar un usuario si es necesario
    }

}