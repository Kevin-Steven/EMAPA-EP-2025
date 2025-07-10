<?php
include '../../components/sidebar-instituto.php';
include '../../components/footer-instituto.php';
require '../../../config/config.php';

$carrera_id = isset($_GET['carrera_id']) ? intval($_GET['carrera_id']) : 0;
$nombreCarrera = 'Carrera no especificada';
$nombreRector = 'Rector no especificado';

if ($carrera_id > 0) {
    // Consulta para obtener el nombre de la carrera
    $sql = "SELECT carrera FROM carrera WHERE id = ? AND estado = 'activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carrera_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $nombreCarrera = $row['carrera'];
    }

    // Consulta para obtener el nombre del rector activo
    $sqlRector = "SELECT nombres_completos FROM rector WHERE estado = 'activo' LIMIT 1";
    $resultRector = $conn->query($sqlRector);
    if ($resultRector && $rowRector = $resultRector->fetch_assoc()) {
        $nombreRector = $rowRector['nombres_completos'];
    }
}

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha de la Entidad</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Ficha de la Entidad Receptora</li>
                </ol>
            </nav>
            <h1 class="mb-4 text-center fw-bold bienvenida">Ficha de la Entidad Receptora</h1>
            <form id="formulario-pdf" action="../../pdfs/global/generarPDF6.php" method="POST" enctype="multipart/form-data" target="_blank">
                <!-- Información de la Entidad Receptora -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Información de la Entidad Receptora</div>
                    <div class="card-body row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nombre de la entidad receptora</label>
                            <input type="text" class="form-control" name="nombre_entidad" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">RUC</label>
                            <input type="text" class="form-control" data-tipo="ruc" oninput="validarCampoNumerico(this)" name="ruc" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Actividad económica principal</label>
                            <input type="text" class="form-control" name="actividad_economica" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-control" name="ciudad" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Provincia</label>
                            <input type="text" class="form-control" name="provincia" required>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la Práctica -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Detalles de la Práctica</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Apellidos y Nombres del estudiante</label>
                            <input type="text" name="nombres_estudiante" class="form-control" placeholder="Ingrese sus nombres" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de inicio de la práctica</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de culminación de la práctica</label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                        <div class="col-md-6">
                            <label for="horario_practica" class="form-label">Horario de la práctica:</label>

                            <div class="d-flex gap-2">
                                <input type="time" class="form-control" id="horario_practica_inicio" name="horario_practica_inicio" required>
                                <input type="time" class="form-control" id="horario_practica_fin" name="horario_practica_fin" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="jornada_laboral" class="form-label">Jornada laboral:</label>
                            <select class="form-control" id="jornada_laboral" name="jornada_laboral" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="Lunes a viernes">Lunes a viernes</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Datos del Tutor de la Entidad -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Tutor de la Entidad Receptora</div>
                    <div class="card-body row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nombres y Apellidos del tutor</label>
                            <input type="text" class="form-control" name="nombre_tutor" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cargo del tutor</label>
                            <input type="text" class="form-control" name="cargo_tutor" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Número de prácticas</label>
                            <select class="form-control" id="numero_practicas" name="numero_practicas" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="Primera-Segunda-Tercera">Primera-Segunda-Tercera</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Número de teléfono institucional</label>
                            <input type="text" class="form-control" data-tipo="telefono" oninput="validarCampoNumerico(this)" name="telefono_institucional" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Número de teléfono celular</label>
                            <input type="text" class="form-control" data-tipo="telefono" oninput="validarCampoNumerico(this)" name="telefono_celular" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Dirección de correo electrónico</label>
                            <input type="email" class="form-control" name="correo_tutor" required>
                        </div>
                    </div>
                </div>

                <!-- Botón Generar PDF -->
                <div class="text-end">
                    <button type="button" class="btn btn-danger mt-3" id="btn-generar-pdf">
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
</body>

</html>