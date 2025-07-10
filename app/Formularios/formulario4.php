<?php
include '../../components/sidebar-instituto.php';
include '../../components/footer-instituto.php';
require '../../../config/config.php';
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfil de Egreso</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Perfil de Egreso Administración</li>
                </ol>
            </nav>
            <h1 class="mb-4 text-center fw-bold bienvenida">Perfil de Egreso Administración</h1>

            <!-- Sección: Datos Generales del Estudiante -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold">Descargar documento 4</div>
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <a href="/app/users/pdfs/administracion/4 PERFIL EGRESO ADMINISTRACION.pdf"
                            class="btn btn-outline-primary"
                            download>
                            <i class="bx bx-download"></i> Descargar Documento
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php renderFooterInstituto(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/number.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../../js/generar-pdf.js"></script>
    

</body>

</html>