<?php
require_once 'app/config/config.php';
include 'app/components/sidebar-emapa.php';
include 'app/components/footer-emapa.php';
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formularios | EMAPA-EP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../image/favicon.ico" type="image/ico">
    <link href="app/css/styles.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php renderSidebarEmapa(); ?>

    <main class="content mt-4 mb-5">
        <div class="container">
            <h1 class="text-center fw-bold mb-2 ">Formularios de Servicios</h1>
            <p class="text-center text-muted mb-3">
                Selecciona el formulario que deseas completar.
            </p>

            <div class="row g-4 justify-content-center mt-4 mb-5">

                <!-- Card: Cierre Provisional -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 hover-shadow transition text-center px-3 py-4 position-relative">
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-1 rounded-1 fw-semibold">Nuevo</span>
                        <div class="mb-3">
                            <i class='bx bx-lock-alt fs-1 icon-emapa'></i>
                        </div>
                        <h5 class="fw-bold text-uppercase mb-2 text-emapa">Solicitud de Cierre <br>Provisional</h5>
                        <p class="text-muted small mb-4">Suspende temporalmente el servicio de agua por causas personales o técnicas justificadas.</p>
                        <a href="<?php echo $base_url; ?>/app/formularios/cierre-provisional.php" class="btn btn-emapa-outline w-100">
                            <i class='bx bx-pencil'></i> Llenar Formulario
                        </a>
                    </div>
                </div>

                <!-- Card: Cierre Definitivo -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 hover-shadow transition text-center px-3 py-4 position-relative">
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-1 rounded-1 fw-semibold">Nuevo</span>
                        <div class="mb-3">
                            <i class='bx bx-file fs-1 icon-emapa'></i>
                        </div>
                        <h5 class="fw-bold text-uppercase mb-2 text-emapa">Solicitud de Cierre <br>Definitivo</h5>
                        <p class="text-muted small mb-4">Realiza el cierre total de tu cuenta de agua potable de manera formal y documentada.</p>
                         <a href="<?php echo $base_url; ?>/app/formularios/cierre-definitivo.php" class="btn btn-emapa-outline w-100">
                            <i class='bx bx-pencil'></i> Llenar Formulario
                        </a>
                    </div>
                </div>

                <!-- Card: Certificado de No Adeudar -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 hover-shadow transition text-center px-3 py-4 position-relative">
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-1 rounded-1 fw-semibold">Nuevo</span>
                        <div class="mb-3">
                            <i class='bx bx-envelope fs-1 icon-emapa'></i>
                        </div>
                        <h5 class="fw-bold text-uppercase mb-2 text-emapa">Solicitud de Certificado de no Ser Usuario</h5>
                        <p class="text-muted small mb-4">Solicita constancia que confirme que no eres usuario registrado en nuestra base de datos.</p>
                        <a href="<?php echo $base_url; ?>/app/formularios/certificado-no-ser-usuario.php" class="btn btn-emapa-outline w-100">
                            <i class='bx bx-pencil'></i> Llenar Formulario
                        </a>
                    </div>
                </div>

                <!-- Card: Solicitud de Servicio de Agua Potable -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 hover-shadow transition text-center px-3 py-4 position-relative">
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-1 rounded-1 fw-semibold">Nuevo</span>
                        <div class="mb-3">
                            <i class='bx bx-droplet fs-1 icon-emapa'></i>
                        </div>
                        <h5 class="fw-bold text-uppercase mb-2 text-emapa">Solicitud de Servicio de Agua Potable</h5>
                        <p class="text-muted small mb-4">Inicia el trámite para contar con el servicio de agua potable en tu propiedad de forma rápida y segura.</p>
                        <a href="#" class="btn btn-emapa-outline w-100">
                            <i class='bx bx-pencil'></i> Llenar Formulario
                        </a>
                    </div>
                </div>

                <!-- Card: Acceso a la Información Pública -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 hover-shadow transition text-center px-3 py-4 position-relative">
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-1 rounded-1 fw-semibold">Nuevo</span>
                        <div class="mb-3">
                            <i class='bx bx-data fs-1 icon-emapa'></i>
                        </div>
                        <h5 class="fw-bold text-uppercase mb-2 text-emapa">Solicitud de Acceso a la Información Pública</h5>
                        <p class="text-muted small mb-4">Solicita datos institucionales en cumplimiento con la Ley de Acceso a la Información.</p>
                        <a href="#" class="btn btn-emapa-outline w-100">
                            <i class='bx bx-pencil'></i> Llenar Formulario
                        </a>
                    </div>
                </div>


                <!-- Card: Cambio de Razón Social -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 hover-shadow transition text-center px-3 py-4 position-relative">
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-1 rounded-1 fw-semibold">Nuevo</span>
                        <div class="mb-3">
                            <i class='bx bx-pencil fs-1 icon-emapa'></i>
                        </div>
                        <h5 class="fw-bold text-uppercase mb-2 text-emapa">Solicitud de Cambio de Razón Social</h5>
                        <p class="text-muted small mb-4">Actualiza los datos legales del titular del servicio con este formulario de modificación.</p>
                        <a href="#" class="btn btn-emapa-outline w-100">
                            <i class='bx bx-pencil'></i> Llenar Formulario
                        </a>
                    </div>
                </div>

                <!-- Card: Certificado de No Adeudar -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 hover-shadow transition text-center px-3 py-4 position-relative">
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-1 rounded-1 fw-semibold">Nuevo</span>
                        <div class="mb-3">
                            <i class='bx bx-certification fs-1 icon-emapa'></i>
                        </div>
                        <h5 class="fw-bold text-uppercase mb-2 text-emapa">Certificado de No <br>Adeudar</h5>
                        <p class="text-muted small mb-4">Obtén un documento oficial que certifica que no mantienes deudas con EMAPA EP Daule.</p>
                        <a href="#" class="btn btn-emapa-outline w-100">
                            <i class='bx bx-pencil'></i> Llenar Formulario
                        </a>
                    </div>
                </div>


            </div>



        </div>
    </main>

    <?php renderFooterEmapa(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>