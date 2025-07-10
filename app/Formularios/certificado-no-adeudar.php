<?php
include '../components/sidebar-emapa.php';
include '../components/footer-emapa.php';

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CERTIFICADO DE NO ADEUDAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link rel="icon" href="../../image/favicon.ico" type="image/ico">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php renderSidebarEmapa(); ?>


    <div class="content mt-3">
        <div class="container mb-4">

            <nav aria-label="breadcrumb" class="bg-white py-2 ">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../index.php" class="text-decoration-none text-dark">
                            <i class='bx bx-home-alt me-1'></i> Formularios
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-emapa fw-semibold" aria-current="page">
                        Certificado de No Adeudar
                    </li>
                </ol>
            </nav>


            <h1 class="mb-4 mt-5 text-center fw-bold bienvenida">CERTIFICADO DE NO ADEUDAR</h1>
            <form id="formulario-pdf" action="../pdfs/global/pdf-no-adeudar.php" method="POST" target="_blank">
                <!-- Fecha y Ciudad -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos del Formulario</div>
                    <div class="card-body row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Institución</label>
                            <input type="text" name="institucion" class="form-control" value="Empresa Pública Municipal de Agua Potable y Alcantarillado de Daule EMAPA-EP" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha</label>
                            <input type="date" name="fecha" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Identificación del Cliente -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Identificación del Cliente</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nombres</label>
                            <input type="text" name="nombres" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">N° de Cédula</label>
                            <input type="text" name="cedula" data-tipo="cedula" class="form-control" oninput="validarCampoNumerico(this)" required>
                        </div>
                    </div>
                </div>

                <div class="text-end my-5">
                    <button type="button" class="btn btn-danger" id="btn-generar-pdf">
                        <i class='bx bxs-file-pdf'></i> Generar PDF
                    </button>
                </div>
            </form>

        </div>
    </div>

    <?php renderFooterEmapa(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/number.js"></script>
    <script src="../js/generar-pdf.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/formulario-localstorage-basico.js"></script>

</body>

</html>