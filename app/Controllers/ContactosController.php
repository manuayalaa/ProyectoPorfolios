<?php

namespace App\Controllers;

use App\Models\Trabajos;
use App\Models\Usuarios;
use App\Models\CategoriaSkills;
use App\Models\Skills;
use App\Models\RedesSociales;
use App\Models\Proyectos;

class ContactosController extends BaseController
{
    public function indexAction()
    {

        $claseUsuario = Usuarios::getInstancia();
        if (isset($_GET['q'])) {
            $usuarios = $claseUsuario->search($_GET['q']);
        } else {
            // Obtener todos los usuarios (por defecto
        $usuarios = $claseUsuario->getAll();
        }

        $data = ['usuarios' => $usuarios, 'auth' => isset($_SESSION['auth'])];
        $this->renderHTML('../app/Views/index_view.php', $data);

      
        

    }

    public function loginAction()
    {
        $nombre = $_POST['nombre'] ?? null;
        $password = $_POST['password'] ?? null;
        $claseUsuario = Usuarios::getInstancia();
        $claseUsuario->login($nombre, $password);
        if (isset($_POST['submit'])) {
            if ($claseUsuario->getMensaje() != 'Usuario no encontrado o con cuenta sin activar. Por favor, revisa tu email y activa tu cuenta.') {
                echo "<h2>Bienvenido " . $claseUsuario->nombre . "</h2>";
                $_SESSION['auth'] = true;
                $_SESSION['usuario'] = $claseUsuario->nombre;

                $_SESSION['tipo'] = 'Usuario';
                header('Location: /');
            } else {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            }
        } else {
            $this->renderHTML('../app/Views/login_view.php');
        }
    }



    public function cerrarSesionAction()
    {
        session_unset();
        session_destroy();
        header('Location: /');
    }

    public function registrarAction()
    {
        $rb = random_bytes(32);
        $token = base64_encode($rb);
        $secureToken = str_replace('/', '', uniqid('', true) . $token);

        $fecha_creacion_token = date('Y-m-d H:i:s');
        $nombre = $_POST['nombre'] ?? null;
        $apellidos = $_POST['apellidos'] ?? null;
        $password = $_POST['password'] ?? null;
        $email = $_POST['email'] ?? null;
        $categoria_profesional = $_POST['categoria_profesional'] ?? null;
        $resumen_perfil = $_POST['resumen_perfil'] ?? null;
        $claseUsuario = Usuarios::getInstancia();
        if (isset($_POST['submit'])) {
            $claseUsuario->registrar($nombre, $apellidos, $password, $email, $categoria_profesional, $resumen_perfil, $secureToken, $fecha_creacion_token);
            if ($claseUsuario->getMensaje() == 'Usuario registrado') {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
                header('Location: /');
            } else {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            }
        } else {
            $this->renderHTML('../app/Views/registrar_view.php');
        }
    }
    public function verificarAction()
    {
        $token = explode('/', $_SERVER['REQUEST_URI'])[2];
        $claseUsuario = Usuarios::getInstancia();
        $claseUsuario->verificarToken($token);
        if ($claseUsuario->getMensaje() == 'Usuario verificado') {
            echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            $_SESSION['auth'] = true;
            $_SESSION['usuario'] = $claseUsuario->nombre;
            $_SESSION['tipo'] = 'Usuario';
            header('Location: /');
        } else {
            echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
        }
    }

    public function perfilAction()
    {
        // Comprobar si el usuario está logueado
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
        }
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $claseCategoriaSkils = CategoriaSkills::getInstancia();
        $categoriaSkills = $claseCategoriaSkils->getAll();
        if (isset($_POST['anadir_trabajo'])) {
            $titulo = $_POST['titulo'] ?? null;
            $fecha_inicio = $_POST['fecha_inicio'] ?? null;
            $fecha_final = $_POST['fecha_final'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $claseTrabajo = Trabajos::getInstancia();
            $claseTrabajo->set($titulo, $descripcion, $fecha_inicio, $fecha_final, $usuario['id']);
            if ($claseUsuario->getMensaje() == 'Trabajo añadido') {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            } else {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            }
        }

        if (isset($_POST['anadir_proyecto'])) {
            $titulo = $_POST['titulo'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $claseProyecto = Proyectos::getInstancia();
            $claseProyecto->anadirProyecto($titulo, $descripcion, $usuario['id']);
            if ($claseUsuario->getMensaje() == 'Proyecto añadido') {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            } else {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            }
        }

        if (isset($_POST['anadir_habilidad']) && isset($_POST['habilidades']) && isset($_POST['categoria_habilidad'])) {
            $habilidades = $_POST['habilidades'] ?? null;
            $categorias_skills_categoria = $_POST['categoria_habilidad'] ?? null;
            $claseSkill = Skills::getInstancia();
            $claseSkill->anadirSkill($habilidades, $usuario['id'], $categorias_skills_categoria);

            if ($claseUsuario->getMensaje() == 'Skill añadida correctamente') {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            } else {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            }
        }


        if (isset($_POST['anadir_red_social']) && isset($_POST['url'])) {
            $url = $_POST['url'] ?? null;
            $claseRedSocial = RedesSociales::getInstancia();
            $claseRedSocial->anadirRedSocial($url, $usuario['id']);
            if ($claseUsuario->getMensaje() == 'Red Social añadida correctamente') {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            } else {
                echo "<h2>" . $claseUsuario->getMensaje() . "</h2>";
            }
        }


        if (isset($_POST['actualizar_perfil'])) {
            $foto_perfil = $_FILES['foto_perfil'] ?? null;
            $nombre = $_POST['nombre'] ?? null;
            $apellidos = $_POST['apellidos'] ?? null;
            $categoria_profesional = $_POST['categoria_profesional'] ?? null;
            $resumen_perfil = $_POST['resumen_perfil'] ?? null;

            $ruta_destino = 'img/' . $foto_perfil['name']; // Especifica la ruta donde guardar la imagen
            move_uploaded_file($foto_perfil['tmp_name'], $ruta_destino);

            $claseUsuario->actualizarUsuario($nombre, $apellidos, $categoria_profesional, $resumen_perfil, $ruta_destino, $usuario['id']); // Pasar la ruta de la imagen como argumento
            
        }


        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $data = ['usuario' => $usuario, 'categorias' => $categoriaSkills, 'auth' => isset($_SESSION['auth'])];
        $this->renderHTML('../app/Views/perfil_view.php', $data);
    }

    public function eliminarTrabajoAction()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
        }
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $trabajo = $claseUsuario->getTrabajo($_SESSION['usuario']);
        if ($trabajo['usuarios_id'] != $usuario['id']) {
            header('Location: /perfil');
        }

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $claseTrabajo = Trabajos::getInstancia();
        $claseTrabajo->eliminarTrabajo($id);
        if ($claseTrabajo->getMensaje() == 'Trabajo eliminado') {
            echo "<h2>" . $claseTrabajo->getMensaje() . "</h2>";
            header('Location: /perfil');
        } else {
            echo "<h2>" . $claseTrabajo->getMensaje() . "</h2>";
        }
    }

    public function eliminarProyectoAction()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
        }
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $proyecto = $claseUsuario->getProyecto($_SESSION['usuario']);
        if ($proyecto['usuarios_id'] != $usuario['id']) {
            header('Location: /perfil');
        }

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $claseProyecto = Proyectos::getInstancia();
        $claseProyecto->eliminarProyecto($id);
        if ($claseProyecto->getMensaje() == 'Proyecto eliminado') {
            echo "<h2>" . $claseProyecto->getMensaje() . "</h2>";
            header('Location: /perfil');
        } else {
            echo "<h2>" . $claseProyecto->getMensaje() . "</h2>";
        }
    }

    public function eliminarRedSocialAction()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
        }
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $redSocial = $claseUsuario->getRedSocial($_SESSION['usuario']);
        if ($redSocial['usuarios_id'] != $usuario['id']) {
            header('Location: /perfil');
        }

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $claseRedSocial = RedesSociales::getInstancia();
        $claseRedSocial->eliminarRedSocial($id);
        if ($claseRedSocial->getMensaje() == 'Red Social eliminada') {
            echo "<h2>" . $claseRedSocial->getMensaje() . "</h2>";
            header('Location: /perfil');
        } else {
            echo "<h2>" . $claseRedSocial->getMensaje() . "</h2>";
        }
    }

    public function eliminarHabilidadAction()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
        }
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $habilidad = $claseUsuario->getHabilidad($_SESSION['usuario']);
        if ($habilidad['usuarios_id'] != $usuario['id']) {
            header('Location: /perfil');
        }

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $claseSkill = Skills::getInstancia();
        $claseSkill->eliminarSkill($id);
        if ($claseSkill->getMensaje() == 'Skill eliminada') {
            echo "<h2>" . $claseSkill->getMensaje() . "</h2>";
            header('Location: /perfil');
        } else {
            echo "<h2>" . $claseSkill->getMensaje() . "</h2>";
        }
    }

    public function ocultarTrabajoAction()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
        }
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $trabajo = $claseUsuario->getTrabajo($_SESSION['usuario']);
        if ($trabajo['usuarios_id'] != $usuario['id']) {
            header('Location: /perfil');
        }

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $claseTrabajo = Trabajos::getInstancia();
        $claseTrabajo->ocultarTrabajo($id);
        if ($claseTrabajo->getMensaje() == 'Trabajo ocultado') {
            echo "<h2>" . $claseTrabajo->getMensaje() . "</h2>";
            header('Location: /perfil');
        } else {
            echo "<h2>" . $claseTrabajo->getMensaje() . "</h2>";
        }
    }

    public function ocultarRedSocialAction()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
        }
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $redSocial = $claseUsuario->getRedSocial($_SESSION['usuario']);
        if ($redSocial['usuarios_id'] != $usuario['id']) {
            header('Location: /perfil');
        }

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $claseRedSocial = RedesSociales::getInstancia();
        $claseRedSocial->ocultarRedSocial($id);
        if ($claseRedSocial->getMensaje() == 'Red social ocultada') {
            echo "<h2>" . $claseRedSocial->getMensaje() . "</h2>";
            header('Location: /perfil');
        } else {
            echo "<h2>" . $claseRedSocial->getMensaje() . "</h2>";
        }
    }

    public function ocultarHabilidadAction()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
        }
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $habilidad = $claseUsuario->getHabilidad($_SESSION['usuario']);
        if ($habilidad['usuarios_id'] != $usuario['id']) {
            header('Location: /perfil');
        }

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $claseSkill = Skills::getInstancia();
        $claseSkill->ocultarSkill($id);
        if ($claseSkill->getMensaje() == 'Skill ocultada') {
            echo "<h2>" . $claseSkill->getMensaje() . "</h2>";
            header('Location: /perfil');
        } else {
            echo "<h2>" . $claseSkill->getMensaje() . "</h2>";
        }
    }

    public function ocultarProyectoAction()
    {
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
        }
        $claseUsuario = Usuarios::getInstancia();
        $usuario = $claseUsuario->getUsuario($_SESSION['usuario']);
        $proyecto = $claseUsuario->getProyecto($_SESSION['usuario']);
        if ($proyecto['usuarios_id'] != $usuario['id']) {
            header('Location: /perfil');
        }

        $id = explode('/', $_SERVER['REQUEST_URI'])[2];
        $claseProyecto = Proyectos::getInstancia();
        $claseProyecto->ocultarProyecto($id);
        if ($claseProyecto->getMensaje() == 'Proyecto ocultado') {
            echo "<h2>" . $claseProyecto->getMensaje() . "</h2>";
            header('Location: /perfil');
        } else {
            echo "<h2>" . $claseProyecto->getMensaje() . "</h2>";
        }
    }

    


}
