<?php
namespace App\Models;

require_once("DBAbstractModel.php");

class Skills extends DBAbstractModel
{
    //Singleton
    private static $instancia;
    // Atributos de la clase Skills
    private  $id;
    private  $nombre;
    private  $nivel;
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

    public function getAllSkills()
    {
        $this->query = "SELECT * FROM skills";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function getSkillsPorUsuariosId($id = '')
    {
        $this->query = "SELECT * FROM skills WHERE usuarios_id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        return $this->rows;
    }
    public function anadirSkill($habilidades, $usuarios_id,$categorias_skills_categoria)
    {
        // Comprobar primero si hay un usuario con el usuario_id

        $this->query = "SELECT * FROM usuarios WHERE id = :usuarios_id";
        $this->parametros['usuarios_id'] = $usuarios_id;
        $this->get_results_from_query();
        if (count($this->rows) == 0) {
            $this->mensaje = 'Usuario no encontrado';
            return;
        }
        
        
        $this->query = "INSERT INTO skills (habilidades, categorias_skills_categoria, usuarios_id) VALUES (:habilidades, :categorias_skills_categoria, :usuarios_id)";
        $this->parametros['habilidades'] = $habilidades;
        $this->parametros['categorias_skills_categoria'] = $categorias_skills_categoria;
        $this->parametros['usuarios_id'] = $usuarios_id;
        $this->get_results_from_query();
        $this->mensaje = 'Skill añadida correctamente';

    }

    public function eliminarSkill($id)
    {
        

        $this->query = "DELETE FROM skills WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
    }


    public function ocultarSkill($id)
    {
        // Si el trabajo ya está a NULL, es decir que está visible, poner a 0, si no, poner a NULL
        $this->query = "SELECT * FROM skills WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        
        if ($this->rows[0]['visible'] == 1) {
            $this->query = "UPDATE skills SET visible = 0 WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
            $this->mensaje = 'Skill ocultada';
        } else {
            $this->query = "UPDATE skills SET visible = 1 WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
            $this->mensaje = 'Skill mostrada';
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