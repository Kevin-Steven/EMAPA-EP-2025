<?php
include '../../components/sidebar-instituto.php';
include '../../components/footer-instituto.php';
require '../../../config/config.php';

$carrera_id = isset($_GET['carrera_id']) ? intval($_GET['carrera_id']) : 0;
$nombreCarrera = 'Carrera no especificada';
$tutores = [];

if ($carrera_id > 0) {
    // Obtener nombre de la carrera
    $sql = "SELECT carrera FROM carrera WHERE id = ? AND estado = 'activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carrera_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $nombreCarrera = $row['carrera'];

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
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plan de Aprendizaje Práctico</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Plan de Aprendizaje Práctico</li>
                </ol>
            </nav>
            <h1 class="mb-4 text-center fw-bold bienvenida">Plan de Aprendizaje Práctico y de Rotación del Estudiante</h1>
            <form id="formulario-pdf" action="../../pdfs/administracion/generarPDF2.php" method="POST" enctype="multipart/form-data" target="_blank">

                <!-- Sección: Datos Generales del Estudiante -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos Generales del Estudiante</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Apellidos y Nombres</label>
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
                        <div class="col-md-6">
                            <label class="form-label">Grupo (Paralelo)</label>
                            <input type="text" name="paralelo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nivel de Estudio</label>
                            <select class="form-select" name="periodo_academico" id="periodo" required>
                                <option selected value="" disabled>Selecciona una opción</option>
                                <option value="4TO NIVEL">4TO NIVEL</option>
                                <option value="5TO NIVEL">5TO NIVEL</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sección: Periodo de Prácticas -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Periodo de Práctica Preprofesional</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control" name="hora_inicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora de Fin</label>
                            <input type="time" class="form-control" name="hora_fin" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Horas Prácticas</label>
                            <select class="form-select" name="hora_practicas" required>
                                <option value="">Seleccione...</option>
                                <option value="240">240</option>
                                <option value="192">192</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sección: Tutor Académico -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos del Tutor Académico</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label for="tutor_academico" class="form-label">Tutor Académico</label>
                            <select name="tutor_academico" id="tutor_academico" class="form-select" required>
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

                        <!-- Cédula del Tutor -->
                        <div class="col-md-6">
                            <label for="cedula_tutor" class="form-label">Cédula del Tutor</label>
                            <input type="text" name="cedula_tutor" id="cedula_tutor" class="form-control desactivado" readonly>
                        </div>

                        <!-- Correo del Tutor -->
                        <div class="col-md-6">
                            <label for="correo_tutor" class="form-label">Correo del Tutor</label>
                            <input type="email" name="correo_tutor" id="correo_tutor" class="form-control desactivado" readonly>
                        </div>

                        <!-- Teléfono del Tutor -->
                        <div class="col-md-6">
                            <label for="telefono_tutor" class="form-label">Teléfono del Tutor</label>
                            <input type="text" name="telefono_tutor" id="telefono_tutor" class="form-control desactivado" readonly>
                        </div>

                    </div>
                </div>

                <!-- Sección: Diagnóstico EVA-S -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Resultados Extraídos del EVA-S</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Subir archivo EVA-S</label>
                            <input type="file" class="form-control" name="eva_s" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nota EVA-S</label>
                            <input type="text" class="form-control" name="nota_eva-s" data-tipo="promedio" oninput="validarCampoNumerico(this)" required>
                        </div>
                    </div>
                </div>

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
    <script src="../../../js/tutor_academico.js"></script>
    <script src="../../../js/formulario-localstorage-basico.js"></script>


</body>

</html>