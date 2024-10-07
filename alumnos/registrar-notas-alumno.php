<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Notas</title>
    <link rel="stylesheet" href="../CSS/indexmodi.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <header>
        <div class="prese">
            <h1>Registrar Notas del Alumno</h1>
            <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </div>
        <div class="menu-buttons">
            <button id="openMenu" class="botone">
                <div class="svg-container">
                    <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                        <g id="SVGRepo_bgCarrier" stroke-width="0">
                            <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#2b8aaf" stroke-width="0"></rect>
                        </g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <rect x="0" fill="none" width="24" height="24"></rect>
                            <g>
                                <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z"></path>
                            </g>
                        </g>
                    </svg>
                </div>
            </button>
        </div>
        <nav class="nav-list">
            <div class="menu-buttons">
                <button id="closeMenu" class="botone2">
                    <div class="svg-container">
                        <svg width="80px" height="80px" viewBox="-2.4 -2.4 28.80 28.80" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="0" fill="#333" stroke-width="0"></rect>
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <rect x="0" fill="none" width="24" height="24"></rect>
                                <g>
                                    <path d="M4 19h16v-2H4v2zm16-6H4v2h16v-2zM4 9v2h16V9H4zm16-4H4v2h16V5z"></path>
                                </g>
                            </g>
                        </svg>
                    </div>
                </button>
            </div>
            <ul>
                <h2>
                    <li><a href="http://localhost:8080/escuela1/">Principal</a></li>
                </h2>
                <h2>
                    <li><a href="http://localhost:8080/escuela1/alumnos/listarAlumnos.php">Alumno</a></li>
                </h2>
                <h2>
                    <li><a class="nav" href="http://localhost:8080/escuela1/profesores/listarProfesor.php">Profesor</a></li>
                </h2>
            </ul>
            <div class="logo">
                <img src="../Imagenes/sanmiguel.png" alt="Logo San Miguel">
            </div>
        </nav>
    </header>

    <script src="../JavaScript/menu.js"></script>

    <?php
    include("../conexion.php");

    $con = mysqli_connect($host, $user, $pwd, $BD) or die("FALLO DE CONEXION");

    // Verificar si se recibió el DNI del alumno
    $alumno_dni = isset($_GET['alumno']) ? mysqli_real_escape_string($con, $_GET['alumno']) : null;

    // Verificar si se seleccionó un DNI de profesor
    $dni_profesor = isset($_GET['DNI_profesor']) ? mysqli_real_escape_string($con, $_GET['DNI_profesor']) : null;

    // Consulta para verificar si el DNI del alumno ya existe en la base de datos
    $check_query = "SELECT DNI_alumno, nombre, apellido FROM alumnos WHERE DNI_alumno = '$alumno_dni'";
    $check_result = mysqli_query($con, $check_query);

    // Obtener lista de profesores
    $profesor_query = "SELECT DNI_profesor, nombre FROM profesor";
    $profesores = mysqli_query($con, $profesor_query);

    // Si hay un profesor seleccionado, obtener las materias asociadas a ese profesor
    $materias = [];
    if ($dni_profesor) {
        $materia_query = "SELECT materias.ID_materia, materias.nombreMateria
                      FROM profesor_materias
                      JOIN materias ON profesor_materias.ID_materia = materias.ID_materia
                      WHERE profesor_materias.DNI_profesor = '$dni_profesor'";
        $materias = mysqli_query($con, $materia_query);
    }

    if (mysqli_num_rows($check_result) > 0) {
        $alumno = mysqli_fetch_assoc($check_result);
        $nombre_completo = $alumno['nombre'] . " " . $alumno['apellido'];

        // Si se enviaron las notas
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['notaTP'], $_POST['notaConcepto'], $_POST['notaExamen'], $_POST['ID_materia'])) {
            $notaTP = mysqli_real_escape_string($con, $_POST['notaTP']);
            $notaConcepto = mysqli_real_escape_string($con, $_POST['notaConcepto']);
            $notaExamen = mysqli_real_escape_string($con, $_POST['notaExamen']);
            $ID_materia = mysqli_real_escape_string($con, $_POST['ID_materia']);

            $promedio = ($notaTP + $notaConcepto + $notaExamen) / 3;

            $insert_query = "INSERT INTO boletin (notaTP, notaExamen, notaConcepto, promedio, DNI_alumno, ID_materia) VALUES (
            '$notaTP', '$notaExamen', '$notaConcepto', '$promedio', '$alumno_dni', '$ID_materia')";
            $resultado = mysqli_query($con, $insert_query);

            echo "<script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Nota registrada con éxito.',
                html: '<a href=\"vista-boletin.php?alumno=$alumno_dni\" class=\"btn btn-success\">VOLVER</a>',
                showConfirmButton: false,
            });
        </script>";
        } else {
    ?>
            <div class="container mt-4">
                <form method="POST" action="" class="form-group">
                    <label>Alumno: <?php echo $nombre_completo; ?> </label>
                    <br>

                    <label>DNI Alumno:</label>
                    <input class="form-control" type="text" name="DNI" value="<?php echo $alumno_dni; ?>" readonly>

                    <label>Seleccionar Profesor:</label>
                    <select class="form-control" name="DNI_profesor" onchange="location = this.value;">
                        <option value="">Seleccione un profesor</option>
                        <?php while ($row = mysqli_fetch_assoc($profesores)) { ?>
                            <option value="?alumno=<?php echo $alumno_dni; ?>&DNI_profesor=<?php echo $row['DNI_profesor']; ?>"
                                <?php if ($dni_profesor == $row['DNI_profesor']) echo 'selected'; ?>>
                                <?php echo $row['DNI_profesor'] . " - " . $row['nombre']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <?php if ($dni_profesor) { ?>
                        <label>Seleccionar Materia:</label>
                        <select class="form-control" name="ID_materia" required>
                            <option value="">Seleccione una materia</option>
                            <?php while ($row = mysqli_fetch_assoc($materias)) { ?>
                                <option value="<?php echo $row['ID_materia']; ?>">
                                    <?php echo $row['ID_materia'] . " - " . $row['nombreMateria']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php } ?>

                    <label>Nota TP:</label>
                    <input class="form-control" type="number" name="notaTP" min="1" max="10" required>

                    <label>Nota Concepto:</label>
                    <input class="form-control" type="number" name="notaConcepto" min="1" max="10" required>

                    <label>Nota Examen:</label>
                    <input class="form-control" type="number" name="notaExamen" min="1" max="10" required>

                    <br>
                    <input class="btn btn-primary" type="submit" value="Registrar Nota">
                </form>
            </div>
    <?php
        }
    } else {
        echo "<div class='container'>
            <div class='alert alert-danger' role='alert'>
                No se encontró ningún alumno con ese DNI.
            </div>
            <a href='../alumnos/listarAlumnos.php' class='btn btn-warning'>Volver</a>
        </div>";
    }

    mysqli_close($con);
    ?>

</body>

</html>