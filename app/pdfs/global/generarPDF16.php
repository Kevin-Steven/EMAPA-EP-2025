<?php
require_once('../../../../TCPDF-main/tcpdf.php');

$nombre_doc = '16 PLAN DE APRENDIZAJE PRÁCTICO Y DE ROTACIÓN DEL ESTUDIANTE-complete';
// Datos de la Entidad Receptora
$nombre_entidad_receptora = isset($_POST['entidad_formadora']) ? trim($_POST['entidad_formadora']) : 'NO APLICA';
$actividad_economica = isset($_POST['actividad_economica']) ? trim($_POST['actividad_economica']) : 'NO APLICA';
$nombres_tutor_receptor = isset($_POST['tutor_entidad_receptora']) ? ucwords(strtolower(trim($_POST['tutor_entidad_receptora']))) : 'NO APLICA';
$area_asignacion = isset($_POST['area_asignacion']) ? trim($_POST['area_asignacion']) : 'NO APLICA';

// Datos de la Entidad Formadora
$nombre_tutor_academico = isset($_POST['tutor_entidad_formadora']) ? ucwords(strtolower(trim($_POST['tutor_entidad_formadora']))) : 'NO APLICA';

// Datos del Estudiante
$nombres = isset($_POST['nombres']) ? ucwords(strtolower(trim($_POST['nombres']))) : 'NO APLICA';
$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : 'NO APLICA';
$correo = isset($_POST['correo']) ? trim($_POST['correo']) : 'NO APLICA';

// Observaciones por niveles
$nivel_institucional = isset($_POST['nivel_institucional']) ? trim($_POST['nivel_institucional']) : 'NO APLICA';
$nivel_funcional = isset($_POST['nivel_funcional']) ? trim($_POST['nivel_funcional']) : 'NO APLICA';
$nivel_practico = isset($_POST['nivel_practico']) ? trim($_POST['nivel_practico']) : 'NO APLICA';

$carrera = $_POST['carrera'] ?? 'NO APLICA';

// Fechas de la práctica
$fecha_inicio = !empty($_POST['fecha_inicio']) ? formato_fecha_larga($_POST['fecha_inicio']) : 'NO APLICA';
$fecha_fin = !empty($_POST['fecha_fin']) ? formato_fecha_larga($_POST['fecha_fin']) : 'NO APLICA';


function formato_fecha_larga($fecha)
{
    $meses = [
        'enero',
        'febrero',
        'marzo',
        'abril',
        'mayo',
        'junio',
        'julio',
        'agosto',
        'septiembre',
        'octubre',
        'noviembre',
        'diciembre'
    ];

    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$fecha_obj) return 'N/A';

    $dia = $fecha_obj->format('d');
    $mes = $meses[(int)$fecha_obj->format('m') - 1];
    $anio = $fecha_obj->format('Y');

    return "$dia de $mes del $anio";
}

class CustomPDF extends TCPDF
{
    public function Header() {
        $margen_derecha = 10;

        $this->Image('../../../images/index.png', 15, 9.7, 20);

        $this->SetTextColor(120, 120, 120);
        // Fuente y alineación
        $this->SetFont('times', 'B', 11);
        $this->SetY(10);
        $this->SetX($margen_derecha + 20); // Ajuste de margen derecho
        $this->Cell(0, 1, 'INSTITUTO SUPERIOR TECNOLÓGICO BOLIVARIANO DE TECNOLOGÍA', 0, 1, 'C');

        $this->SetFont('times', '', 11);
        $this->SetX($margen_derecha + 20);

        $html = '<strong>Dirección:</strong> Víctor Manuel Rendón 236 y Pedro Carbo, Guayaquil';
        $this->writeHTMLCell(0, 1, '', '', $html, 0, 1, false, true, 'C');

        $this->SetX($margen_derecha + 20);
        $html = '<strong>Teléfonos:</strong> (04) 5000175 – 1800 ITB-ITB';
        $this->writeHTMLCell(0, 1, '', '', $html, 0, 1, false, true, 'C');

        $this->SetX($margen_derecha + 20);
        $html = '<strong>Correo:</strong> <span style="text-decoration: underline;">info@bolivariano.edu.ec</span> &nbsp;<strong>Web:</strong> <span style="text-decoration: underline;">www.itb.edu.ec</span>';
        $this->writeHTMLCell(0, 1, '', '', $html, 0, 1, false, true, 'C');


        $this->Ln(5);

        if ($this->PageNo() == 1) {
            $this->SetY(25);
        } else {
            $this->SetY(30);
        }

        $this->SetTextColor(0, 0, 0);
    }

    public function Footer() {}

    // Helper para filas de 2 celdas
    public function MultiCellRow($data, $widths, $height)
    {
        $nb = 0;
        foreach ($data as $key => $value) {
            $nb = max($nb, $this->getNumLines($value, $widths[$key]));
        }
        $h = $height * $nb;
        $this->CustomCheckPageBreak($h);

        foreach ($data as $key => $value) {
            $w = $widths[$key];
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->setCellPaddings(1, 0, 1, 0);
            $this->MultiCell($w, $height, trim($value), 0, 'L', 0, 0, '', '', true, 0, false, true, $h, 'M', true);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    public function CustomCheckPageBreak($h)
    {
        if ($this->GetY() + $h > ($this->getPageHeight() - $this->getBreakMargin())) {
            $this->AddPage($this->CurOrientation);
            $this->SetY(25);
        }
    }
}

// Inicializar TCPDF
$pdf = new CustomPDF();
$pdf->AddPage();
$pdf->SetMargins(20, 20, 20);
$pdf->SetY(35);

$pdf->SetFont('dejavusans', 'B', 11.5);
$pdf->Cell(0, 1, 'PLAN DE APRENDIZAJE PRÁCTICO Y ROTACIÓN DEL ESTUDIANTE EN EL', 0, 1, 'C');
$pdf->Cell(0, 1, 'ENTORNO LABORAL (Indicaciones metodológicas) ', 0, 1, 'C');
$pdf->Cell(0, 1, '(Formación Práctica en el entorno Laboral Real)', 0, 1, 'C');
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 1, 'Documento habilitante para la realización y ejecución de las prácticas laborales en el entorno laboral', 0, 1, 'L');
$pdf->Cell(0, 1, 'real por parte del estudiante hacia la entidad receptora.', 0, 1, 'L');

$pdf->Ln(3);
$html_tabla1 = '
<table border="0.5" cellpadding="4" cellspacing="0" width="100%">
    <tr bgcolor="#9CC3E5">
        <td colspan="2"><strong>    1. DATOS GENERALES</strong></td>
    </tr>

    <tr>
        <td colspan="2" align="center"><strong>DE LA ENTIDAD RECEPTORA</strong></td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Entidad Formadora:</strong></td>
        <td width="82%" style="font-size: 10px;"> <strong>' . $nombre_entidad_receptora . '</strong></td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Actividad Económica:</strong></td>
        <td width="82%" style="font-size: 10px;"> <strong>' . $actividad_economica . '</strong></td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Tutor Entidad Receptora:</strong></td>
        <td width="82%" style="font-size: 10px;">' . $nombres_tutor_receptor . '</td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Área de asignación del estudiante:</strong></td>
        <td width="82%" style="font-size: 10px;">' . $area_asignacion . '</td>
    </tr>

    <tr>
        <td colspan="2" align="center"><strong>DATOS GENERALES DE LA ENTIDAD FORMADORA</strong></td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Entidad Formadora:</strong></td>
        <td width="82%" style="font-size: 10px;"><strong>Instituto Superior Tecnológico Bolivariano de Tecnología.</strong></td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Facultad Académica:</strong></td>
        <td width="82%" style="font-size: 10px;"><strong>Ciencias Empresariales y Sistemas.</strong></td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Tutor Entidad Formadora:</strong></td>
        <td width="82%" style="font-size: 10px;">' . $nombre_tutor_academico . '</td>
    </tr>

    <tr>
        <td colspan="2" align="center"><strong>DEL ESTUDIANTE</strong></td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Apellidos y Nombres:</strong></td>
        <td width="82%" style="font-size: 10px;">' . $nombres . '</td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Cédula de identidad:</strong></td>
        <td width="82%" style="font-size: 10px;">' . $cedula . '</td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Correo electrónico:</strong></td>
        <td width="82%" style="font-size: 10px;">' . $correo . '</td>
    </tr>
    <tr>
        <td width="18%" style="font-size: 10px;"><strong>Carrera:</strong></td>
        <td width="82%" style="font-size: 10px;">' . $carrera . '</td>
    </tr>
    <tr bgcolor="#9CC3E5">
        <td colspan="2"><strong>    2. OBJETIVO DEL PLAN DE APRENDIZAJE</strong></td>
    </tr>
    <tr>
  <td colspan="2" style="font-size: 11px; font-family: freesans; line-height: 1.1; text-align: justify;">
    Desarrollar en los estudiantes capacidades profesionales previstas en el diseño curricular de la carrera, a través de la realización de
    <i>actividades de aprendizaje principalmente de carácter práctico- experimental, de producción y/o de prestación de servicios en las entidades formadoras, orientadas a la aplicación de conocimientos y/o al desarrollo de competencias profesionales,
    en integración con factores tecnológicos y sociolaborales propios del entorno laboral real, que coadyuve al fomento de la cultura laboral y a una mejor inserción en el campo profesional.</i>
    </td>
</tr>

</table>
';
$pdf->writeHTML($html_tabla1, true, false, true, false, '');

$pdf->AddPage();
$pdf->SetY(35);

$html_tabla2 = '
<table border="0.5" cellpadding="4" cellspacing="0" width="100%">
    <tr bgcolor="#9CC3E5" style="font-family: dejavusans; font-size: 11px;" align="center">
        <td colspan="2"><strong>    3. CRITERIOS DE EVALUACIÓN EN LA FORMACIÓN PRÁCTICA EN EL ENTORNO LABORAL REAL (ESTUDIANTE)</strong></td>
    </tr>

    <tr>
        <td width="18%" style="font-size: 10px; line-height: 1.1;"><strong>CRITERIO 1:</strong> <span style="font-size: 12px;">Actitud y comportamiento en relación con el entorno laboral- profesional</span></td>
        <td width="82%" style="font-size: 12px;">
        <ul>
            <li>Asistencia y puntualidad</li>
            <li>Cumplimiento del plan de trabajo o actividades planificadas en el PAPR</li>
            <li>Comunicación Activa en el entorno laboral hacia la colaboración y trabajo en equipo</li>
            <li>Cumplimiento del Reglamento Interno en relación con sus labores y actividades de la entidad formadora</li>
        </ul>
        </td>
    </tr>

    <tr>
        <td width="18%" style="font-size: 10px; line-height: 1.1;"><br><br><br><br><br><br><br><br><strong>CRITERIO 2:</strong> <span style="font-size: 12px;">Desempeño en el entorno laboral- profesional</span></td>
        <td width="82%" style="font-size: 10px; font-family: dejavusanscondensed; line-height: 1.5;">
            <span style="font-weight: bold;">a) <em>En actividades de observación:</em></span><br>
            &nbsp;&nbsp;&nbsp;&nbsp;▪ Capacidad del estudiante para la descripción, explicación, interpretación, argumentación y análisis crítico de tareas, actividades observadas.<br>

            <span style="font-weight: bold;">b) <em>En actividades de intervención,</em></span><br>
            &nbsp;&nbsp;&nbsp;&nbsp;▪ Capacidad del Estudiante en participar, dominar o ejecutar tareas de índoles técnicas, actividades laborales y procesos de producción o servicios, expresado en la aplicación normas técnicas, herramientas y/o protocolos establecidos e implementación<br><br>

            &nbsp;&nbsp;&nbsp;&nbsp;➢ Conocimiento de los fundamentos que sustentan la tarea, actividad y/o proceso ejecutado<br>
            &nbsp;&nbsp;&nbsp;&nbsp;➢ Cumplimiento de normas de seguridad y salud en el trabajo, cuidado de las máquinas, equipos, herramientas que eviten accidentes, o evitar el contacto con sustancias tóxicas y el contagio con agentes patógenos.<br>
            &nbsp;&nbsp;&nbsp;&nbsp;➢ Cumplimiento de las actividades designadas para la generación de soluciones integrales como parte de un equipo de trabajo
        </td>
    </tr>

    <tr>
        <td width="18%" style="font-size: 10px; line-height: 1.1;"><strong>CRITERIO 3:</strong> <span style="font-size: 12px;">Resultados del Plan de Aprendizaje Práctico en el entorno laboral real</span></td>
        <td width="82%" style="font-size: 10px; font-family: dejavusanscondensed; line-height: 1.5;">
            &nbsp;&nbsp;&nbsp;&nbsp;▪ Sustentación del informe final de la práctica por parte del estudiante, considerando:<br>

            &nbsp;&nbsp;&nbsp;&nbsp;➢ Calidad de redacción y presentación de informe Final<br>
            &nbsp;&nbsp;&nbsp;&nbsp;➢ Argumentación de experiencias de aprendizaje, y propuesta de recomendaciones hacia la entidad formadora como mecanismo de mejora continua<br>

            &nbsp;&nbsp;&nbsp;&nbsp;▪ Evaluación realizada por el tutor de la Entidad Receptora<br>
            &nbsp;&nbsp;&nbsp;&nbsp;▪ Evaluación realizada por el tutor de la Entidad Formadora

        </td>
    </tr>
</table>
';

$html_tabla3 = '
<table border="0.5" cellpadding="4" cellspacing="0" width="100%">
    <tr bgcolor="#9CC3E5">
        <td colspan="2"><strong>    4. DURACIÓN DE PRÁCTICA LABORAL</strong></td>
    </tr>

    <tr>
        <td width="20%" style="font-size: 10px;"><strong>Fecha Inicio:</strong></td>
        <td width="30%" style="font-size: 10px;"> <strong>' . $fecha_inicio . '</strong></td>

        <td width="20%" style="font-size: 10px;"><strong>Fecha Fin:</strong></td>
        <td width="30%" style="font-size: 10px;"> <strong>' . $fecha_fin . '</strong></td>
    </tr>

    <tr bgcolor="#9CC3E5">
        <td colspan="4">
        <strong>    5. PROCESO DE FORMACIÓN DE PRÁCTICA LABORAL</strong><br><span style="font-size: 10px;">Indicar la relación de áreas o departamentos donde rotará el estudiante para la realización de su práctica laboral en la entidad receptora.</span>
        </td>
    </tr>

    <tr>
        <td width="30%" style="font-size: 9px;"><strong>SECCIÓN DEPARTAMENTAL (Entidad Receptora)</strong></td>
        <td width="40%" style="font-size: 9px;" align="center"><br><br><strong>ACTIVIDAD A DESEMPEÑAR</strong></td>
        <td width="30%" style="font-size: 9px; line-height: 1.1;"><strong>OBSERVACIÓN<span style="font-size: 7px; font-family: dejavusanscondensed;">(Especificar tarea laboral, actividad o proceso que se observa, participa o intervienen)</span></strong></td>
    </tr>
</table>
';

$pdf->writeHTML($html_tabla2, true, false, true, false, '');
$pdf->writeHTML($html_tabla3, true, false, true, false, '');

$pdf->AddPage();
$pdf->SetY(35);

$html_tabla4 = '
<table border="0.5" cellpadding="4" cellspacing="0" width="100%">

    <tr>
        <td width="30%" style="font-size: 10px; " align="center"><br><br><br><strong>Nivel Institucional</strong></td>
        <td width="40%" style="font-size: 8.5px; font-family: dejavusanscondensed; text-align: justify;"><br><br><br>Actividades de familiarización, observación y descripción de actividades, tareas y/o procesos<br><br></td>
        <td width="30%" style="font-size: 11px; line-height: 1.1; text-align: justify;">' . $nivel_institucional . '</td>
    </tr>

    <tr>
        <td width="30%" style="font-size: 10px;" align="center"><br><br><br><strong>Nivel Funcional</strong></td>
        <td width="40%" style="font-size: 8.5px; font-family: dejavusanscondensed; text-align: justify;"><br><br><br>Actividades de intervención o participación directa en una actividad laboral o proceso<br><br></td>
        <td width="30%" style="font-size: 11px; line-height: 1.1; text-align: justify;">' . $nivel_funcional . '</td>
    </tr>

    <tr>
        <td width="30%" style="font-size: 10px;" align="center"><br><br><br><strong>Nivel Práctico</strong></td>
        <td width="40%" style="font-size: 8.5px; font-family: dejavusanscondensed; text-align: justify;"><br><br><br>Actividades de intervención o participación directa en una actividad laboral o proceso<br><br></td>
        <td width="30%" style="font-size: 11px; line-height: 1.1; text-align: justify;">' . $nivel_practico . '</td>
    </tr>

    <tr>
        <td width="100%" colspan="3" style="font-size: 10px; text-align: justify;">Este documento proporciona una ruta de compromiso y ejecución como plan de aprendizaje y de rotación del estudiante en el entorno laboral real para la formación académica acorde al perfil del estudiante en su asignación de práctica hacia la entidad receptora.</td>
    </tr>
</table>
';
$pdf->writeHTML($html_tabla4, true, false, true, false, '');

$pdf->Ln(30);

$firmas = '<table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr>
        <td width="60%" style="text-align: start; vertical-align: bottom;">
            ___________________________<br>
            Firma del estudiante de Prácticas
        </td>
        
        <td width="40%" style="text-align: center; vertical-align: bottom;">
            ___________________________<br>
            <span style="text-align: center;">Firma del Tutor de la entidad <br>receptora</span>
        </td>
    </tr>
    
    <tr>
        <br>
        <br>
        <br>
        <br>
        <td colspan="2" style="text-align: center;">
            ___________________________________<br>
            Firma del Docente Tutor de la <br>Entidad Formadora
        </td>
    </tr>
</table>
';


$pdf->writeHTML($firmas, true, false, true, false, '');

$pdf->Output($nombre_doc . '.pdf', 'I');
