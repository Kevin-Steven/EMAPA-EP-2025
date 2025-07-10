<?php
function renderSidebarEmapa()
{
?>
    <!-- Navbar Moderno -->
    <nav class="navbar emapa-navbar shadow-sm py-2">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <!-- Botón Hamburguesa -->
            <button class="btn text-white d-flex align-items-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <i class='bx bx-menu fs-3'></i>
            </button>

            <!-- Logo y Marca -->
            <div class="d-flex align-items-center gap-3">
                <img src="../../image/logo-emapa.png" alt="Logo EMAPA-EP" height="50">
            </div>
        </div>
    </nav>

    <!-- Sidebar Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header" style="background: var(--color-principal);">
            <img src="../../image/logo-emapa.png" alt="Logo EMAPA-EP" height="50">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>

        <div class="offcanvas-body sidebar-nav-modern d-flex flex-column gap-3 px-3">
            <!-- Inicio -->
            <a class="nav-link d-flex align-items-center gap-2" href="/">
                <i class='bx bx-home-alt'></i> Inicio
            </a>

            <!-- Formularios con submenú -->
            <div class="accordion" id="accordionFormularios">
                <div class="accordion-item border-0 bg-transparent">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed d-flex align-items-center gap-2 nav-link bg-transparent"
                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                            <i class='bx bx-folder'></i> Formularios
                        </button>
                    </h2>
                    <div id="collapseForm" class="accordion-collapse collapse" data-bs-parent="#accordionFormularios">
                        <div class="accordion-body px-0 pt-2">
                            <ul class="nav flex-column ps-3 gap-2">
                                <li>
                                    <a class="nav-link d-flex align-items-center gap-2" href="/app/formularios/cierre-provisional.php">
                                        <i class='bx bx-right-arrow-alt'></i> Cierre Provisional
                                    </a>
                                </li>
                                 <li>
                                    <a class="nav-link d-flex align-items-center gap-2" href="/app/formularios/cierre-definitivo.php">
                                        <i class='bx bx-right-arrow-alt'></i> Cierre Definitivo
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link d-flex align-items-center gap-2" href="/app/formularios/certificado-no-ser-usuario.php">
                                        <i class='bx bx-right-arrow-alt'></i> Certificado De No Ser Usuario
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <i class='bx bx-right-arrow-alt'></i> Acceso a la Información Pública
                                    </a>
                                </li>
                                 <li>
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <i class='bx bx-right-arrow-alt'></i> Servicio de Agua Potable
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <i class='bx bx-right-arrow-alt'></i> Cambio de Razón Social
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <i class='bx bx-right-arrow-alt'></i> Certificado De No Adeudar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Enlace Externo -->
            <a class="nav-link d-flex align-items-center gap-2" href="https://emapa.institutobolivariano.online/" target="_blank">
                <i class='bx bx-link-external'></i> Ir al Portal EMAPA
            </a>
        </div>
    </div>
<?php
}
?>