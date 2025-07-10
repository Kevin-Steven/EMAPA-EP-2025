<?php
include '../components/sidebar-emapa.php';
include '../components/footer-emapa.php';

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SOLICITUD DE CERTIFICADO DE NO SER USUARIO</title>
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
                        Solicitud de Certificado de No Ser Usuario
                    </li>
                </ol>
            </nav>


            <h1 class="mb-4 mt-5 text-center fw-bold bienvenida">SOLICITUD DE CERTIFICADO DE NO SER USUARIO</h1>
            <form id="formulario-pdf" action="../pdfs/global/pdf-no-ser-usuario.php" method="POST" target="_blank">
                <!-- Fecha y Ciudad -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Datos del Formulario</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fecha</label>
                            <input type="date" name="fecha" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ciudad</label>
                            <input type="text" name="ciudad" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Institución</label>
                            <input type="text" name="institucion" class="form-control" value="Empresa Pública Municipal de Agua Potable y Alcantarillado de Daule EMAPA-EP" readonly>
                        </div>
                    </div>
                </div>

                <!-- Identificación del Cliente -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">Identificación del Requiriente</div>
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
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Teléfono Fijo o Celular</label>
                            <input type="text" name="telefono" class="form-control" data-tipo="telefono" oninput="validarCampoNumerico(this)" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Dirección Domiciliaria</label>
                            <input type="text" name="direccion" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Especifique El Motivo Por El Cual Está Solicitando El Certificado De No Ser Usuario Ni Adeudar A La Institución</label>
                            <textarea name="motivo_solicitud" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Forma de Recepción de Respuesta -->
                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm h-100">
                            <div class="card-header fw-bold">Forma de Recepción de la Respuesta De La Solicitud</div>
                            <div class="card-body row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="forma_recepcion" value="presencial" required>
                                        <label class="form-check-label">Presencial</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Motivos del Cierre Provisional -->
                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm h-100">
                            <div class="card-header fw-bold">Motivos del Certificado De No Ser Usuario</div>
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="inscripcion_escrituras" value="Inscripción de Escrituras">
                                    <label class="form-check-label">Inscripción de Escrituras</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tramites_procesos_judiciales" value="Trámites por Procesos Judiciales">
                                    <label class="form-check-label">Trámites por Procesos Judiciales</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="otros" value="Otros">
                                    <label class="form-check-label">Otros</label>
                                </div>
                            </div>
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