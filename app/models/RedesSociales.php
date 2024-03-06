<?php
namespace App\Models;

require_once("DBAbstractModel.php");

class RedesSociales extends DBAbstractModel
{
    //Singleton
    private static $instancia;
    // Atributos de la clase RedesSociales
    private  $id;
    private  $nombre;
    private  $url;
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

    public function getAllRedesSociales()
    {
        $this->query = "SELECT * FROM redes_sociales";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function getRedesSocialesPorUsuariosId($id = '')
    {
        $this->query = "SELECT * FROM redes_sociales WHERE usuarios_id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        return $this->rows;
    }

    public function anadirRedSocial($url, $usuarios_id)
    {
        $this->query = "INSERT INTO redes_sociales (url, usuarios_id) VALUES (:url, :usuarios_id)";
        $this->parametros['url'] = $url;
        $this->parametros['usuarios_id'] = $usuarios_id;
        $this->get_results_from_query();
    }

    public function eliminarRedSocial($id)
    {
        $this->query = "DELETE FROM redes_sociales WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
    }

    public function ocultarRedSocial($id)
    {
        // Si el trabajo ya está a NULL, es decir que está visible, poner a 0, si no, poner a NULL
        $this->query = "SELECT * FROM redes_sociales WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        
        if ($this->rows[0]['visible'] == 1) {
            $this->query = "UPDATE redes_sociales SET visible = 0 WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
            $this->mensaje = 'Red social ocultada';
        } else {
            $this->query = "UPDATE redes_sociales SET visible = 1 WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
            $this->mensaje = 'Red social mostrada';
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

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getUsuariosId()
    {
        return $this->usuarios_id;
    }
}