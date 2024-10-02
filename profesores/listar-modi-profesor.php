<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumno/Alta</title>
    <link rel="stylesheet" href="../CSS/indexmodi.css">
    <link rel="stylesheet" href="../CSS/header.css">

</head>
<body>
<header>
        <div class="prese">
        <h1>Formulario de Registro Profesor</h1>            
        <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </div>
        <div class="menu-buttons">
            <button id="openMenu" class="botone">
                <div class="svg-container">
                    <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0">
                            <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#2b8aaf" stroke-width="0"/>
                        </g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                        <g id="SVGRepo_iconCarrier">
                            <rect x="0" fill="none" width="24" height="24"/>
                            <g>
                                <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z"/>
                            </g>
                        </g>
                    </svg>
                </div>
            </button>
        </div>
        <nav class="nav-list" >
            <div class="menu-buttons">
                <button id="closeMenu" class="botone2">
                    <div class="svg-container">
                        <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#333" stroke-width="0"/>
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                            <g id="SVGRepo_iconCarrier">
                                <rect x="0" fill="none" width="24" height="24"/>
                                <g>
                                    <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                </button>
            </div>
            <ul>
            <h2><li><a href="http://localhost:8080/escuela1/">Principal</a></li></h2>
                <h2><li><a href="http://localhost:8080/escuela1/alumnos/listarAlumnos.php">Alumno</a></li></h2>
                <h2><li><a class="nav" href="http://localhost:8080/escuela1/profesores/listarProfesor.php">Profesor</a></li></h2>
            </ul>
            </ul>
            <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </nav>
    </header>
    <script src="../JavaScript/menu.js"></script>

    <?php
// Incluir archivo de conexión y controlador de profesor

include("../Controladores/ProfesorControlador.php");

$con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION"); // Variables de conexión

$profesor = isset($_GET['profesor']) ? mysqli_real_escape_string($con, $_GET['profesor']) : null;

if ($profesor) {
    // Obtener datos del profesor por DNI
    $query_select = "SELECT * FROM profesor WHERE DNI_profesor = '$profesor'";
    $result_select = mysqli_query($con, $query_select) or die("ERROR DE CONSULTA");

    // Si se encuentran datos, mostrar el formulario con los datos actuales
    if (mysqli_num_rows($result_select) > 0) {
        $row = mysqli_fetch_assoc($result_select); // Tomar solo un registro
        ?>

        <!-- Formulario de modificación -->
        <form method="POST" action="">
            DNI: <input type="text" name="modiDNI" value="<?php echo($row['DNI_profesor']); ?>" readonly> <br>
            Nombre: <input type="text" name="modiNombre" value="<?php echo($row['nombre']); ?>"> <br>
            Apellido: <input type="text" name="modiApellido" value="<?php echo($row['apellido']); ?>"> <br>
            Especialidad: <input type="text" name="modiEspecialidad" value="<?php echo($row['especialidad']); ?>"> <br>
            
            ¿Baja?: <input type="checkbox" name="modiBaja" <?php if ($row['baja'] == 1) echo 'checked'; ?>> <br>

            <input type="submit" value="Actualizar">
        </form>

        <?php
        // Si se ha enviado el formulario de actualización, se procesan los cambios
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modiDNI'])) {
            actualizarProfesor(); // Llamamos la función para actualizar el profesor
        }
    } else {
        echo "No se encontró ningún profesor con ese DNI.";
        ?>
        <h3>Ver listado de profesores</h3>
        <a href="../alumnos/listarProfesor.php"><button>LISTAR</button></a>
        <?php
    }
} else {
    echo "No se ha proporcionado un DNI de profesor.";
}

mysqli_close($con);
?>

<!-- Botón para volver -->
<div class="volvido">
    <a href="../profesores/listarProfesor.php">VOLVER</a>
</div>

