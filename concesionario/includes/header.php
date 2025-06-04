<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionario de Autos</title>
    <link rel="stylesheet" href="/concesionario/css/style.css">
</head>
<body>
<header>
    <h1>Concesionario XYZ</h1>
   <!-- <span class="welcome-user">Bienvenido,<?= htmlspecialchars($_SESSION['username']); ?></span>-->
    <nav>
        <a href="/concesionario/index.php">Inicio</a>
        <a href="/concesionario/vehiculos/index.php">Vehículos</a>
        <a href="/concesionario/clientes/index.php">Clientes</a>
        <a href="/concesionario/ventas/index.php">Ventas</a>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
            <a href="/concesionario/auth/logout.php" class="logout-btn">Cerrar Sesión</a>
        <?php else: ?>
            <a href="/concesionario/auth/login.php" class="logout-btn">Iniciar Sesión</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">