<?php
namespace App\Models;

require_once("DBAbstractModel.php");

class CategoriaSkills extends DBAbstractModel
{
    //Singleton
    private static $instancia;
    // Atributos de la clase CategoriaSkills
    private  $id;
    private  $nombre;
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

    public function getAll()
    {
        $this->query = "SELECT * FROM categorias_skills";
        $this->get_results_from_query();
        return $this->rows;
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
