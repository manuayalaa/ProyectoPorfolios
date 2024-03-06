<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolios Web</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <h1>Bienvenido a Nuestro Sitio de Portfolios</h1>
    </header>

    <nav>
        <ul>
            <li><a href="/">Inicio</a></li>
            <li><a href="/login">Loguearse</a></li>
            <li><a href="/registrar">Registrarse</a></li>
        </ul>
    </nav>
    <!-- Formulario de login -->
    <section id="login">
        <h2>Iniciar Sesión</h2>
        <form action="/login" method="post">
                <label for="usuario">Usuario:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" name="submit" value="Iniciar Sesión">
        </form>
    </section>




    <footer>
    <p>Creado por Manuel Ayala</p>
    </footer>
</body>

</html>