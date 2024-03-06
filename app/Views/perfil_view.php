<?php
$usuario = $data['usuario'];
$categorias = $data['categorias'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">

    <title>Perfil</title>

</head>

<body>
    <header>
        <h1>Ajustes del perfil</h1>
    </header>
    <nav>
        <ul>
            <li><a href="/">Inicio</a></li>
            <?php if (!$data['auth']) {
                echo '<li><a href="/login">Loguearse</a></li>';
                echo '<li><a href="/registrar">Registrarse</a></li>';
            }
            ?>
            <?php if ($data['auth']) {
                echo '<li><a href="/cerrarsesion">Cerrar Sesión</a></li>';
            }
            ?>
            <?php
            if ($data['auth']) {
                echo '<li class="perfil"><a href="/perfil">Perfil: ' . $_SESSION['usuario'] . '</a></li>';
            } else {
                echo '<li class="perfil">Perfil: Invitado</li>';
            }
            ?>
        </ul>
    </nav>
    <main>
        <section id="perfil">
            <div class="perfil-container">
                <div class="perfil-card">
                    <div class="perfil-info">
                        <?php
                        if ($usuario['foto']) {
                            echo '<img class="imgperfil" src="' . $usuario['foto'] . '" alt="Foto de perfil">';
                        } else {
                            echo '<img class="imgperfil" src="img/IconoPerfil.png" alt="Foto de perfil">';
                        }
                        ?>
                        <h2><?= $usuario['nombre'] ?></h2>
                        <p><strong>Email:</strong> <?= $usuario['email'] ?></p>
                        <p><strong>Categoría profesional:</strong> <?= $usuario['categoria_profesional'] ?></p>
                        <p><strong>Resumen:</strong> <?= $usuario['resumen_perfil'] ?></p>
                    </div>
                    <div class="perfil-trabajos">
                        <h4>Trabajos:</h4>
                        <ul>
                            <?php foreach ($usuario['trabajos'] as $trabajo) :  ?>
                                <li class='lidatos'><?php if ($trabajo['visible'] == 1) {
                                                        echo '<a id="ocultar" href="/ocultartrabajo/' . $trabajo['id'] . '">Ocultar</a>';
                                                    } else {
                                                        echo '<a id="ocultar" href="/ocultartrabajo/' . $trabajo['id'] . '">Mostrar</a>';
                                                    }
                                                    echo ' <a class="eliminar" href="/eliminartrabajo/' . $trabajo['id'] . '">Eliminar</a>' ?> <?= $trabajo['titulo'] ?> - <?= $trabajo['fecha_inicio'] ?> | <?= $trabajo['fecha_final'] ?? '' ?> - <?= $trabajo['descripcion'] ?? 'Sin descripcion'  ?> </li>
                            <?php endforeach; ?>
                        </ul>

                    </div>
                    <div class="perfil-proyectos">
                        <h4>Proyectos:</h4>
                        <ul>
                            <?php foreach ($usuario['proyectos'] as $proyecto) : ?>
                                <li class='lidatos'><?php if ($proyecto['visible'] == 1) {
                                                        echo '<a id="ocultar" href="/ocultarproyecto/' . $proyecto['id'] . '">Ocultar</a>';
                                                    } else {
                                                        echo '<a id="ocultar" href="/ocultarproyecto/' . $proyecto['id'] . '">Mostrar</a>';
                                                    }
                                                    echo '<a class="eliminar" href="/eliminarproyecto/' . $proyecto['id'] . '">Eliminar</a>' ?> <?= $proyecto['titulo'] ?> - <?= $proyecto['descripcion'] ?> </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="perfil-redes">
                        <h4>Redes Sociales:</h4>
                        <ul>
                            <?php foreach ($usuario['redes_sociales'] as $red_social) : ?>
                                <li class='lidatos'><?php if ($red_social['visible'] == 1) {
                                                        echo '<a id="ocultar" href="/ocultarredsocial/' . $red_social['id'] . '">Ocultar</a>';
                                                    } else {
                                                        echo '<a id="ocultar" href="/ocultarredsocial/' . $red_social['id'] . '">Mostrar</a>';
                                                    }
                                                    echo '<a class="eliminar" href="/eliminarredsocial/' . $red_social['id'] . '">Eliminar</a>' ?> <?= '<a href="' . $red_social['url'] . '">' . $red_social['url'] . '</a>' ?></li>
                            <?php endforeach; ?>
                        </ul>

                    </div>
                    <div class="perfil-skills">
                        <h4>Habilidades:</h4>
                        <ul>
                            <?php foreach ($usuario['skills'] as $skill) : ?>
                                <li class='lidatos'><?php if ($skill['visible'] == 1) {
                                                        echo '<a id="ocultar" href="/ocultarhabilidad/' . $skill['id'] . '">Ocultar</a>';
                                                    } else {
                                                        echo '<a id="ocultar" href="/ocultarhabilidad/' . $skill['id'] . '">Mostrar</a>';
                                                    }
                                                    echo '<a class="eliminar" href="/eliminarhabilidad/' . $skill['id'] . '">Eliminar</a>' ?> <?= $skill['habilidades']  ?> - <?= $skill['categorias_skills_categoria']  ?></li>
                            <?php endforeach; ?>
                        </ul>


                    </div>
                </div>

                <div class="perfil-edit">
                    <h3>Editar Perfil</h3>
                    <form action="/perfil" method="post" enctype="multipart/form-data">
                        <label for="foto_perfil">Foto de Perfil:</label>
                        <input type="file" id="foto_perfil" name="foto_perfil">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?= $usuario['nombre'] ?>" required>
                        <label for="apellidos">Apellidos:</label>
                        <input type="text" id="apellidos" name="apellidos" value="<?= $usuario['apellidos'] ?>">
                        <label for="categoria_profesional">Categoría Profesional:</label>
                        <select name="categoria_profesional" id="categoria_profesional">
                            <option value="Desarrollador">Desarrollador</option>
                            <option value="Diseñador">Diseñador</option>
                            <option value="Tester">Tester</option>
                            <option value="Analista">Analista</option>
                        </select>
                        <label for="resumen_perfil">Resumen Perfil:</label>
                        <textarea name="resumen_perfil" id="resumen_perfil" cols="30" rows="10"><?= $usuario['resumen_perfil'] ?></textarea>
                        <input type="submit" name="actualizar_perfil" value="Actualizar perfil">
                    </form>
                    <h4>Añadir Trabajo:</h4>
                    <form action="/perfil" method="post">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>
                        <label for="fecha_inicio">Fecha Inicio:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                        <label for="fecha_final">Fecha Final:</label>
                        <input type="date" id="fecha_final" name="fecha_final">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
                        <input type="submit" name="anadir_trabajo" value="Añadir Trabajo">
                    </form>
                    <h4>Añadir Proyectos:</h4>
                    <form action="/perfil" method="post">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
                        <input type="submit" name="anadir_proyecto" value="Añadir Proyecto">
                    </form>
                    <h4>Añadir Red Social:</h4>
                    <form action="/perfil" method="post">
                        <label for="url">URL:</label>
                        <input type="text" id="url" name="url" required>
                        <input type="submit" name="anadir_red_social" value="Añadir Red Social">
                    </form>
                    <h4>Añadir Habilidad:</h4>
                    <form action="/perfil" method="post">
                        <label for="habilidad">Habilidad:</label>
                        <input type="text" id="habilidades" name="habilidades" required>
                        <label for="categoria_habilidad">Categoría Habilidad:</label>
                        <select name="categoria_habilidad" id="categoria_habilidad">
                            <?php foreach ($categorias as $categoria) : ?>
                                <option value="<?= $categoria['categoria'] ?>"><?= $categoria['categoria'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="submit" name="anadir_habilidad" value="Añadir Habilidad">
                    </form>

                </div>
            </div>
        </section>


    </main>
    <footer>
    <p>Creado por Manuel Ayala</p>
    </footer>

</body>


</html>