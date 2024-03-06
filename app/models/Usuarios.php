<?php

namespace App\Models;


use App\Models\Trabajos;
use App\Models\RedesSociales;
use App\Models\Skills;
use App\Core\EmailSender;

require_once("DBAbstractModel.php");

class Usuarios extends DBAbstractModel
{
    // Singleton
    private static $instancia;
    public $nombre;
    public $trabajos;



    public static function getInstancia()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function __clone()
    {
        trigger_error("La clonación no está permitida.", E_USER_ERROR);
    }

    public function login($nombre, $password)
    {
        $this->query = "SELECT * FROM usuarios WHERE nombre = :nombre AND password = :password AND cuenta_activa = 1";
        $this->parametros['nombre'] = $nombre;
        $this->parametros['password'] = $password;

        $this->get_results_from_query();
        if (count($this->rows) == 0) {
            $this->mensaje = 'Usuario no encontrado o con cuenta sin activar. Por favor, revisa tu email y activa tu cuenta.';
        } else {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
            // Asegurarse de que la propiedad 'nombre' exista
            $this->nombre = $this->rows[0]['nombre'] ?? null;
        }
        return $this->rows[0] ?? null;
    }

    public function registrar($nombre, $apellidos, $password, $email, $categoria_profesional, $resumen_perfil, $token, $fecha_creacion_token)
    {
        // Comprobar primero si hay un usuario con el mismo nombre
        $this->query = "SELECT * FROM usuarios WHERE nombre = :nombre";
        $this->parametros['nombre'] = $nombre;
        $this->get_results_from_query();
        if (count($this->rows) > 0) {
            $this->mensaje = 'Ya existe un usuario con ese nombre';
            return;
        }

        $this->query = "INSERT INTO usuarios (nombre, apellidos, password, email, categoria_profesional, resumen_perfil, token, fecha_creacion_token) VALUES (:nombre, :apellidos, :password, :email, :categoria_profesional, :resumen_perfil, :token, :fecha_creacion_token)";
        $this->parametros['nombre'] = $nombre;
        $this->parametros['apellidos'] = $apellidos;
        $this->parametros['password'] = $password;
        $this->parametros['email'] = $email;
        $this->parametros['categoria_profesional'] = $categoria_profesional;
        $this->parametros['resumen_perfil'] = $resumen_perfil;
        $this->parametros['token'] = $token;
        $this->parametros['fecha_creacion_token'] = $fecha_creacion_token;

        $this->get_results_from_query();
        $this->mensaje = 'Usuario registrado';

        // Enviar correo de confirmación
        $emailSender = new EmailSender;
        $emailSender->sendConfirmationMail($nombre, $apellidos, $email, $token);
    }

    // Verificar token de usuario y poniendo a 1 cuenta_activa si no ha pasado el tiempo de expiración que son 24 horas desde el registro
    public function verificarToken($token)
    {
        $this->query = "SELECT * FROM usuarios WHERE token = :token";
        $this->parametros['token'] = $token;
        $this->get_results_from_query();
        if (count($this->rows) == 0) {
            $this->mensaje = 'Token no encontrado';
        } else {
            $fecha_creacion_token = $this->rows[0]['fecha_creacion_token'];
            $fecha_creacion_token = strtotime($fecha_creacion_token);
            $fecha_actual = strtotime(date('Y-m-d H:i:s'));
            $diferencia = $fecha_actual - $fecha_creacion_token;
            if ($diferencia > 86400) {
                $this->mensaje = 'Token expirado';
            } else {
                $this->query = "UPDATE usuarios SET cuenta_activa = 1 WHERE token = :token";
                $this->parametros['token'] = $token;
                $this->get_results_from_query();
                $this->mensaje = 'Cuenta activada, puedes volver a la página y loguearte.';
            }
        }
    }

    public function actualizarUsuario($nombre, $apellidos, $categoria_profesional, $resumen_perfil, $foto, $id)
{   
    $this->query = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, categoria_profesional = :categoria_profesional, resumen_perfil = :resumen_perfil, foto = :foto WHERE id = :id";

    $this->parametros['nombre'] = $nombre;
    $this->parametros['foto'] = $foto;
    $this->parametros['apellidos'] = $apellidos;
    $this->parametros['categoria_profesional'] = $categoria_profesional;
    $this->parametros['resumen_perfil'] = $resumen_perfil;
    $this->parametros['id'] = $id;

    $this->get_results_from_query();
    $this->mensaje = 'Usuario actualizado';
}



    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function get($id = '')
    {
        // Implementa tu lógica para obtener un usuario por ID si es necesario
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
    public function getAll()
    {
        // Obtener todos los usuarios
        $this->query = "SELECT * FROM usuarios";
        $this->get_results_from_query();
        $usuarios = $this->rows;

        // Obtener todos los trabajos asociados a cada usuario y agregarlos al resultado
        foreach ($usuarios as &$usuario) {
            $idUsuario = $usuario['id']; // Suponiendo que el ID del usuario está en la columna 'id'
            $trabajosModel = new Trabajos; // Suponiendo que Trabajos es la clase modelo para los trabajos
            $trabajos = $trabajosModel->getTrabajosPorUsuariosId($idUsuario); // Suponiendo que hay un método getByUsuario en la clase Trabajos para obtener los trabajos asociados a un usuario
            $usuario['trabajos'] = $trabajos;
        }

        // Obtener todos los proyectos asociados a cada usuario y agregarlos al resultado
        foreach ($usuarios as &$usuario) {
            $idUsuario = $usuario['id']; // Suponiendo que el ID del usuario está en la columna 'id'
            $proyectosModel = new Proyectos; // Suponiendo que Proyectos es la clase modelo para los proyectos
            $proyectos = $proyectosModel->getProyectosPorUsuariosId($idUsuario); // Suponiendo que hay un método getByUsuario en la clase Proyectos para obtener los proyectos asociados a un usuario
            $usuario['proyectos'] = $proyectos;
        }

        // Obtener todas las redes sociales asociadas a cada usuario y agregarlas al resultado
        foreach ($usuarios as &$usuario) {
            $idUsuario = $usuario['id']; // Suponiendo que el ID del usuario está en la columna 'id'
            $redesSocialesModel = new RedesSociales; // Suponiendo que RedesSociales es la clase modelo para las redes sociales
            $redesSociales = $redesSocialesModel->getRedesSocialesPorUsuariosId($idUsuario); // Suponiendo que hay un método getByUsuario en la clase RedesSociales para obtener las redes sociales asociadas a un usuario
            $usuario['redes_sociales'] = $redesSociales;
        }

        // Obtener todas las habilidades asociadas a cada usuario y agregarlas al resultado
        foreach ($usuarios as &$usuario) {
            $idUsuario = $usuario['id']; // Suponiendo que el ID del usuario está en la columna 'id'
            $skillsModel = new Skills; // Suponiendo que Skills es la clase modelo para las habilidades
            $skills = $skillsModel->getSkillsPorUsuariosId($idUsuario); // Suponiendo que hay un método getByUsuario en la clase Skills para obtener las habilidades asociadas a un usuario
            $usuario['skills'] = $skills;
        }



        return $usuarios;
    }

    public function getUsuario($nombre)
    {
        $this->query = "SELECT * FROM usuarios WHERE nombre = :nombre";
        $this->parametros['nombre'] = $nombre;
        $this->get_results_from_query();
        $usuario = $this->rows[0] ?? null;
        $idUsuario = $usuario['id'];
        $trabajosModel = new Trabajos;
        $trabajos = $trabajosModel->getTrabajosPorUsuariosId($idUsuario);
        $usuario['trabajos'] = $trabajos;
        $redesSocialesModel = new RedesSociales;
        $proyectosModel = new Proyectos;
        $proyectos = $proyectosModel->getProyectosPorUsuariosId($idUsuario);
        $usuario['proyectos'] = $proyectos;
        $redesSociales = $redesSocialesModel->getRedesSocialesPorUsuariosId($idUsuario);
        $usuario['redes_sociales'] = $redesSociales;
        $skillsModel = new Skills;
        $skills = $skillsModel->getSkillsPorUsuariosId($idUsuario);
        $usuario['skills'] = $skills;
        return $usuario;
    }
    public function getTrabajo($nombre)
    {
        $this->query = "SELECT * FROM trabajos WHERE nombre = :nombre";
        $this->parametros['nombre'] = $nombre;
        $this->get_results_from_query();
        $trabajo = $this->rows[0] ?? null;
        return $trabajo;
    }

    public function getProyecto($nombre)
    {
        $this->query = "SELECT * FROM proyectos WHERE nombre = :nombre";
        $this->parametros['nombre'] = $nombre;
        $this->get_results_from_query();
        $proyecto = $this->rows[0] ?? null;
        return $proyecto;
    }

    public function getRedSocial($nombre)
    {
        $this->query = "SELECT * FROM redes_sociales WHERE nombre = :nombre";
        $this->parametros['nombre'] = $nombre;
        $this->get_results_from_query();
        $red_social = $this->rows[0] ?? null;
        return $red_social;
    }

    public function getHabilidad($nombre)
    {
        $this->query = "SELECT * FROM skills WHERE nombre = :nombre";
        $this->parametros['nombre'] = $nombre;
        $this->get_results_from_query();
        $skill = $this->rows[0] ?? null;
        return $skill;
    }

    // get para buscador por nombre email o categoria profesional
    public function search($search)
    {
        $this->query = "SELECT * FROM usuarios WHERE nombre LIKE :search OR email LIKE :search OR categoria_profesional LIKE :search";
        $this->parametros['search'] = '%' . $search . '%';
        $this->get_results_from_query();
        $usuarios = $this->rows;
        // Obtener todos los trabajos asociados a cada usuario y agregarlos al resultado
        foreach ($usuarios as &$usuario) {
            $idUsuario = $usuario['id']; // Suponiendo que el ID del usuario está en la columna 'id'
            $trabajosModel = new Trabajos; // Suponiendo que Trabajos es la clase modelo para los trabajos
            $trabajos = $trabajosModel->getTrabajosPorUsuariosId($idUsuario); // Suponiendo que hay un método getByUsuario en la clase Trabajos para obtener los trabajos asociados a un usuario
            $usuario['trabajos'] = $trabajos;
        }

        // Obtener todos los proyectos asociados a cada usuario y agregarlos al resultado
        foreach ($usuarios as &$usuario) {
            $idUsuario = $usuario['id']; // Suponiendo que el ID del usuario está en la columna 'id'
            $proyectosModel = new Proyectos; // Suponiendo que Proyectos es la clase modelo para los proyectos
            $proyectos = $proyectosModel->getProyectosPorUsuariosId($idUsuario); // Suponiendo que hay un método getByUsuario en la clase Proyectos para obtener los proyectos asociados a un usuario
            $usuario['proyectos'] = $proyectos;
        }

        // Obtener todas las redes sociales asociadas a cada usuario y agregarlas al resultado
        foreach ($usuarios as &$usuario) {
            $idUsuario = $usuario['id']; // Suponiendo que el ID del usuario está en la columna 'id'
            $redesSocialesModel = new RedesSociales; // Suponiendo que RedesSociales es la clase modelo para las redes sociales
            $redesSociales = $redesSocialesModel->getRedesSocialesPorUsuariosId($idUsuario); // Suponiendo que hay un método getByUsuario en la clase RedesSociales para obtener las redes sociales asociadas a un usuario
            $usuario['redes_sociales'] = $redesSociales;
        }

        // Obtener todas las habilidades asociadas a cada usuario y agregarlas al resultado
        foreach ($usuarios as &$usuario) {
            $idUsuario = $usuario['id']; // Suponiendo que el ID del usuario está en la columna 'id'
            $skillsModel = new Skills; // Suponiendo que Skills es la clase modelo para las habilidades
            $skills = $skillsModel->getSkillsPorUsuariosId($idUsuario); // Suponiendo que hay un método getByUsuario en la clase Skills para obtener las habilidades asociadas a un usuario
            $usuario['skills'] = $skills;
        }
        return $usuarios;
    }

}
