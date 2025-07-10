<?php
include '../../components/sidebar-instituto.php';
include '../../components/footer-instituto.php';
require '../../../config/config.php';

$carrera_id = isset($_GET['carrera_id']) ? intval($_GET['carrera_id']) : 0;
$nombreCarrera = 'Carrera no especificada';

if ($carrera_id > 0) {
    $sql = "SELECT carrera FROM carrera WHERE id = ? AND estado = 'activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carrera_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $nombreCarrera = $row['carrera'];
    }
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compromiso Ético</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Compromiso Ético de Responsabilidad</li>
                </ol>
            </nav>
            <h1 class="mb-4 text-center fw-bold bienvenida"> Compromiso Ético de Responsabilidad para las Prácticas en el Entorno Laboral Real</h1>
            <form id="formulario-pdf" action="../../pdfs/global/generarPDF7.php" method="POST" enctype="multipart/form-data" target="_blank">
                <!-- Datos Generales -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos Generales</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Apellidos y Nombres</label>
                            <input type="text" name="nombres" class="form-control" placeholder="Ingrese sus nombres" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cédula de Identidad</label>
                            <input type="text" name="cedula" class="form-control" placeholder="Ingrese su cédula de identidad" data-tipo="cedula" oninput="validarCampoNumerico(this)" required>
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