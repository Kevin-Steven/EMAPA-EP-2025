<?php
include '../../components/sidebar-instituto.php';
include '../../components/footer-instituto.php';
require '../../../config/config.php';

$carrera_id = isset($_GET['carrera_id']) ? intval($_GET['carrera_id']) : 0;

$nombreCarrera = 'Carrera no especificada';
$nombreCoordinador = 'Coordinador no asignado';
$tutores = [];

if ($carrera_id > 0) {
    // Obtener el nombre de la carrera
    $sql = "SELECT carrera FROM carrera WHERE id = ? AND estado = 'activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carrera_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $nombreCarrera = $row['carrera'];

        // Obtener tutores de la carrera
        $sqlTutores = "SELECT ta.*
                       FROM tutor_academico ta
                       INNER JOIN tutor_academico_carrera tac ON ta.id = tac.tutor_id
                       WHERE tac.carrera_id = ? AND ta.estado = 'activo'";
        $stmtTutores = $conn->prepare($sqlTutores);
        $stmtTutores->bind_param("i", $carrera_id);
        $stmtTutores->execute();
        $resultTutores = $stmtTutores->get_result();

        while ($tutor = $resultTutores->fetch_assoc()) {
            $tutores[] = $tutor;
        }
    }

    // Obtener el nombre del coordinador
    $sqlCoord = "
        SELECT cp.nombres_completos 
        FROM coordinador_practicas_carrera cpc
        JOIN coordinador_practicas cp ON cp.id = cpc.coordinador_id
        WHERE cpc.carrera_id = ? AND cp.estado = 'activo'
        LIMIT 1
    ";
    $stmtCoord = $conn->prepare($sqlCoord);
    $stmtCoord->bind_param("i", $carrera_id);
    $stmtCoord->execute();
    $resCoord = $stmtCoord->get_result();

    if ($rowCoord = $resCoord->fetch_assoc()) {
        $nombreCoordinador = $rowCoord['nombres_completos'];
    }
}
?>


<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asignación de Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/styles.css" rel="stylesheet">
    <link rel="icon" href="../../../images/favicon.png" type="image/png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php renderSidebarInstituto(); ?>

    <div class="content mt-3">
        <div class="container mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb rounded">
                    <li class="breadcrumb-item"><a href="/app/users/pages/administracion.php">Documentos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Asignación de Estudiante a Prácticas Laborales</li>
                </ol>
            </nav>
            <h1 class="mb-4 text-center fw-bold bienvenida">Asignación de Estudiante a Prácticas Laborales</h1>
            <form id="formulario-pdf" action="../../pdfs/administracion/generarPDF3.php" method="POST" target="_blank">
                <!-- Datos de la Entidad Receptora -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos de la Entidad Receptora</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres y Apellidos del representante</label>
                            <input type="text" name="nombres_representante" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cargo que ocupa</label>
                            <input type="text" name="cargo_representante" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre de la Entidad Receptora</label>
                            <input type="text" name="nombre_entidad" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Datos del Estudiante -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos del Estudiante</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres y Apellidos del estudiante</label>
                            <input type="text" name="nombres" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cédula de Identidad</label>
                            <input type="text" name="cedula" class="form-control" data-tipo="cedula" oninput="validarCampoNumerico(this)" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Carrera</label>
                            <input type="text" name="carrera" class="form-control desactivado" value="<?php echo htmlspecialchars($nombreCarrera); ?>" readonly>
                        </div>
                    </div>
                </div>

                <!-- Información Académica -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Información Académica</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label for="nombres_tutor" class="form-label">Tutor Académico</label>
                            <select name="nombres_tutor" id="nombres_tutor" class="form-select" required>
                                <option value="" disabled selected>Seleccione un tutor</option>
                                <?php foreach ($tutores as $tutor): ?>
                                    <option
                                        value="<?= $tutor['nombres_completos'] ?>"
                                        data-cedula="<?= $tutor['cedula'] ?>"
                                        data-correo="<?= $tutor['email'] ?>"
                                        data-telefono="<?= $tutor['telefono'] ?>">
                                        <?= $tutor['nombres_completos'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Coordinador de Prácticas</label>
                            <input type="text" name="nombres-coordinador" class="form-control desactivado" value="<?php echo htmlspecialchars($nombreCoordinador); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Horas Prácticas</label>
                            <select class="form-select" name="hora_practicas" required>
                                <option value="">Seleccione...</option>
                                <option value="240">240</option>
                                <option value="192">192</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="carrera_id" value="<?php echo $carrera_id; ?>">

                <!-- Botón Generar PDF -->
                <div class="text-end">
                    <button type="button" class="btn btn-danger" id="btn-generar-pdf">
                        <i class='bx bxs-file-pdf'></i> Generar PDF
                    </button>
                </div>
            </form>


        </div>
    </div>

    <?php renderFooterInstituto(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/number.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../js/generar-pdf.js"></script>
    <script src="../../../js/formulario-localstorage-basico.js"></script>

</body>

</html>