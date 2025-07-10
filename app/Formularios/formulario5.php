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
    <title>Carta de Compromiso</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Carta de Compromiso</li>
                </ol>
            </nav>
            <h1 class="mb-4 text-center fw-bold bienvenida">Carta de Compromiso</h1>
            <form id="formulario-pdf" action="../../pdfs/global/generarPDF5.php" method="POST" enctype="multipart/form-data" target="_blank">
                <!-- Datos de la Entidad Receptora -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos de la Entidad Receptora</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre de la Entidad</label>
                            <input type="text" class="form-control" name="nombre_entidad" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-control" name="ciudad" required>
                        </div>
                        <div class="col-md-6">
                            <label for="logo-entidad" class="form-label">Logo de la Entidad Receptora</label>
                            <input type="file" class="form-control" id="logo-entidad" name="logo-entidad" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección de la Entidad Receptora</label>
                            <input type="text" class="form-control" name="direccion-entidad" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RUC</label>
                            <input type="text" class="form-control" data-tipo="ruc" oninput="validarCampoNumerico(this)" name="ruc" required>
                        </div>
                    </div>
                </div>

                <!-- Datos Académicos -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos Académicos</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres del Rector</label>
                            <input type="text" class="form-control desactivado" name="rector" value="<?php echo htmlspecialchars($nombreRector); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Carrera</label>
                            <input type="text" name="carrera" class="form-control desactivado" value="<?php echo htmlspecialchars($nombreCarrera); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                    </div>
                </div>

                <!-- Datos del Estudiante -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos del Estudiante</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres y Apellidos del Estudiante</label>
                            <input type="text" class="form-control" name="nombres" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cédula de Identidad</label>
                            <input type="text" name="cedula" class="form-control" data-tipo="cedula" oninput="validarCampoNumerico(this)" required>
                        </div>
                    </div>
                </div>

                <!-- Representante de Talento Humano -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Representante de Talento Humano</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres y Apellidos</label>
                            <input type="text" class="form-control" name="nombres-representante-rrhh" placeholder="Ej. Ing. Juan Perez" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Institucional</label>
                            <input type="email" class="form-control" name="correo-institucional" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Número Telefónico Institucional</label>
                            <input type="text" class="form-control" data-tipo="telefono" oninput="validarCampoNumerico(this)" name="numero_institucional" required>
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
    <script src="../../../js/formulario-localstorage-basico.js"></script>

</body>

</html>