<?php
include '../components/sidebar-emapa.php';
include '../components/footer-emapa.php';

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SOLICITUD DE CIERRE DEFINITIVO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link rel="icon" href="../../../images/favicon.png" type="image/png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php renderSidebarEmapa(); ?>


    <div class="content mt-3">
        <div class="container mb-4">

            <nav aria-label="breadcrumb" class="bg-white py-2 ">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../index.php" class="text-decoration-none text-emapa fw-medium">
                            <i class='bx bx-home-alt me-1'></i> Formularios
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                        Solicitud de Cierre Definitivo
                    </li>
                </ol>
            </nav>


            <h1 class="mb-4 mt-5 text-center fw-bold bienvenida">SOLICITUD DE CIERRE DEFINITIVO</h1>
            <form id="formulario-pdf" action="../pdfs/global/pdf-cierre-definitivo.php" method="POST" target="_blank">
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
                            <input type="text" name="cedula" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">N° de Cuenta de la Factura o Notificación de Pago</label>
                            <input type="text" name="numero_cuenta" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Dirección Domiciliaria</label>
                            <input type="text" name="direccion" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Teléfono Fijo o Celular</label>
                            <input type="text" name="telefono" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Especifique el Motivo del Cierre Definitivo</label>
                            <textarea name="motivo_cierre" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Forma de Recepción de Respuesta -->
                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm h-100">
                            <div class="card-header fw-bold">Forma de Recepción de la Respuesta</div>
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

                    <!-- Motivos del Cierre Definitivo -->
                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm h-100">
                            <div class="card-header fw-bold">Motivos del Cierre Definitivo</div>
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="venta_predio" value="✔">
                                    <label class="form-check-label">Venta del Predio</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="cuenta_fallecido" value="✔">
                                    <label class="form-check-label">Titular de la Cuenta Fallecido</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="casa_deshabitada" value="✔">
                                    <label class="form-check-label">Casa Deshabitada</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="otros" value="✔">
                                    <label class="form-check-label">Otros</label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="text-end">
                    <button type="submit" class="btn btn-danger">
                        <i class='bx bxs-file-pdf'></i> Generar PDF
                    </button>
                </div>
            </form>

        </div>
    </div>

    <?php renderFooterEmapa(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/number.js"></script>
    <script src="../../../js/generar-pdf.js"></script>
    <script src="../../../js/formulario-localstorage-basico.js"></script>

</body>

</html>