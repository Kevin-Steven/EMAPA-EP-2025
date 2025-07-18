<?php
require_once('../../../../TCPDF-main/tcpdf.php');

// Extraer variables
$nombres = isset($_POST['nombres']) ? ucwords(strtolower(trim($_POST['nombres']))) : 'NO APLICA';
$cedula = $_POST['cedula'] ?? 'NO APLICA';
$carrera = $_POST['carrera'] ?? 'NO APLICA';
$paralelo = $_POST['paralelo'] ?? 'NO APLICA';
$periodoAcademico = $_POST['periodo_academico'] ?? 'NO APLICA';

$fecha_inicio_larga = !empty($_POST['fecha_inicio']) ? formato_fecha_larga($_POST['fecha_inicio']) : 'NO APLICA';
$fecha_fin_larga = !empty($_POST['fecha_fin']) ? formato_fecha_larga($_POST['fecha_fin']) : 'NO APLICA';

$nombre_tutor_academico = isset($_POST['tutor_academico']) ? ucwords(strtolower(trim($_POST['tutor_academico']))) : 'NO APLICA';
$partes_nombre = explode(' ', $nombre_tutor_academico);

// Extraer desde el índice 1 (es decir, sin el título)
$solo_nombre_tutor = count($partes_nombre) > 1 
    ? implode(' ', array_slice($partes_nombre, 1)) 
    : $nombre_tutor_academico;
$cedula_tutor_academico = $_POST['cedula_tutor'] ?? 'NO APLICA';
$correo_tutor_academico = $_POST['correo_tutor'] ?? 'NO APLICA';
$telefono = $_POST['telefono_tutor'] ?? 'NO APLICA';
$hora_practicas = $_POST['hora_practicas']?? 'NO APLICA';

// Puntajes (pregunta1 a pregunta10)
$opcion_uno_puntaje    = $_POST['pregunta1'] ?? null;
$opcion_dos_puntaje    = $_POST['pregunta2'] ?? null;
$opcion_tres_puntaje   = $_POST['pregunta3'] ?? null;
$opcion_cuatro_puntaje = $_POST['pregunta4'] ?? null; 
$opcion_cinco_puntaje  = $_POST['pregunta5'] ?? null;
$opcion_seis_puntaje   = $_POST['pregunta6'] ?? null;
$opcion_siete_puntaje  = $_POST['pregunta7'] ?? null;
$opcion_ocho_puntaje   = $_POST['pregunta8'] ?? null;
$opcion_nueve_puntaje  = $_POST['pregunta9'] ?? null;

$puntajes = [
    $opcion_uno_puntaje,
    $opcion_dos_puntaje,
    $opcion_tres_puntaje,
    $opcion_cuatro_puntaje,
    $opcion_cinco_puntaje,
    $opcion_seis_puntaje,
    $opcion_siete_puntaje,
    $opcion_ocho_puntaje,
    $opcion_nueve_puntaje,
];

$promedio = round(array_sum($puntajes) / count($puntajes), 2);


function generarChecks($puntaje, $valor)
{
    return ((int)$puntaje === (int)$valor) ? '☒' : '☐';
}


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


// Función para convertir hora a "18:16"
function formato_hora($hora)
{
    $hora_obj = DateTime::createFromFormat('H:i:s', $hora);
    if (!$hora_obj) {
        // Intentar si viene como 'H:i'
        $hora_obj = DateTime::createFromFormat('H:i', $hora);
        if (!$hora_obj) return 'N/A';
    }

    return $hora_obj->format('H:i');
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

    public function Footer()
    {
        // Dejamos vacío para no tener pie de página
    }

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
$pdf->SetY(33);

$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 1, 'EVALUACIÓN FINAL DEL ESTUDIANTE EN EL ENTORNO LABORAL REAL', 0, 1, 'C');
$pdf->Cell(0, 1, 'FACULTAD DE CIENCIAS EMPRESARIALES Y SISTEMAS.', 0, 1, 'C');
$pdf->Ln(3);

$pdf->SetFont('times', '', 10);

$html_tabla1 = '
    <table border="0.5" cellpadding="1" cellspacing="0">
        <tr>
            <th colspan="2" style="text-align: center; font-size: 11px;"><strong>DATOS GENERALES DEL ESTUDIANTE</strong></th>
        </tr>
        <tr>
            <td style="font-size: 10px; width: 75%;"><strong>Apellidos y Nombres:</strong></td>
            <td style="font-size: 10px; width: 25%;"><strong>Cédula de identidad:</strong></td>
        </tr>
        <tr>
            <td style="font-size: 10px;">' . $nombres . '</td>
            <td style="font-size: 10px;">' . $cedula . '</td>
        </tr>
    </table>';

$html_tabla2 = '
    <table border="0.5" cellpadding="1" cellspacing="0">
        <tr>
            <td style="font-size: 10px; width: 58%;"><strong>Carrera:</strong></td>
            <td style="font-size: 10px; width: 17%;"><strong>Grupo:</strong></td>
            <td style="font-size: 10px; width: 25%;"><strong>Nivel de Estudio:</strong></td>
        </tr>
        <tr>
            <td style="font-size: 10px;">' . $carrera . '</td>
            <td style="font-size: 10px;">' . $paralelo . '</td>
            <td style="font-size: 10px;">' . $periodoAcademico . '</td>
        </tr>
    </table>';

$html_tabla3 = '
    <table border="0.5" cellpadding="1" cellspacing="0">
        <tr>
            <th colspan="6" style="text-align: start; font-size: 10px;"><strong>Periodo Práctica Preprofesional:</strong></th>
        </tr>
        <tr>
            <td style="font-size: 10px; width: 12%;"><strong>Fecha Inicio:</strong></td>
            <td style="font-size: 10px; width: 25%;">' . $fecha_inicio_larga . '</td>
            <td style="font-size: 10px; width: 12%;"><strong>Fecha Fin:</strong></td>
            <td style="font-size: 10px; width: 26%;">' . $fecha_fin_larga . '</td>
            <td style="font-size: 10px; width: 15%;"><strong>Horas Prácticas:</strong></td>
            <td style="font-size: 10px; width: 10%;">' . $hora_practicas . '</td>
        </tr>
    </table>
    ';

$html_tabla4 = '
    <table border="0.5" cellpadding="1" cellspacing="0">
        <tr>
            <th colspan="3" style="text-align: center; font-size: 11px;"><strong>DATOS GENERALES DE TUTOR ACADÉMICO</strong></th>
        </tr>
        <tr>
            <td style="width: 45%;"><strong>Apellidos y Nombres:</strong></td>
            <td style="width: 25%;"><strong>Cédula de identidad:</strong></td>
            <td style="width: 30%;"><strong>Correo Electrónico:</strong></td>
        </tr>
        <tr>
            <td>' . $solo_nombre_tutor . '</td>
            <td>' . $cedula_tutor_academico . '</td>
            <td>' . $correo_tutor_academico . '</td>
        </tr>
    </table>
    ';

$html_tabla5 = '
    <table border="0.5" cellpadding="1" cellspacing="0">

        <tr>
            <th colspan="6" style="text-align: center; font-size: 11px;"><strong>DATOS GENERALES DE ENTIDAD FORMADORA</strong></th>
        </tr>
        <tr>
            <td style="width: 25%;"><strong>Entidad Formadora:</strong></td>
            <td style="width: 75%;" colspan="5">Instituto Superior Tecnológico Bolivariano de Tecnología.</td>
        </tr>
        <tr>
            <td><strong>Actividad Económica:</strong></td>
            <td style="width: 50%;" colspan="3">Enseñanza técnica y Profesional de nivel inferior al de la
                enseñanza superior</td>
            <td style="width: 10%;"><strong>RUC:</strong></td>
            <td >0992180021001</td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong></td>
            <td colspan="3">Guayas/ Guayaquil/ Carbo (concepción) Víctor Manuel Rendon 236 y Pedro Carbo</td>
            <td><strong>Teléfono</strong></td>
            <td>(04) 5000175 – 1800 ITB</td>
        </tr>
        <tr>
            <td><strong>Tutor Entidad Formadora:</strong></td>
            <td colspan="3">'. $solo_nombre_tutor .'</td>
            <td><strong>Teléfono</strong></td>
            <td>'. $telefono .'</td>
        </tr>
    </table>
    ';

$pdf->writeHTML($html_tabla1, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla2, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla3, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla4, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla5, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);

$html_tabla6 = '
    <table border="0.5" cellpadding="1" cellspacing="0">
        <thead>
            <tr>
                <th colspan="8" align="center" style="line-height: 1;">
                    <strong style="font-size: 11px;">ACTIVIDAD REALIZADA POR EL ESTUDIANTE (PLANIFICADA)</strong><br>
                    <small style="font-size: 9px;">Puntuación del 1 al 5 según el grado de resultado de aprendizaje obtenido el estudiante en su desempeño laboral (descripción puntual de las actividades efectuadas durante sus prácticas preprofesionales en la entidad receptora:</small><br>
                    <strong style="font-size: 9px; ">1.- Deficiente  2.- Regular  3.- Moderado  4.- Alta  5.- Eficiente</strong>
                </th>
            </tr>
            <tr>
                <th colspan="2" width="80%" align="start"><strong>DESCRIPCIÓN DE ACTIVIDADES</strong></th>
                <th width="4%" align="center"><strong>1</strong></th>
                <th width="4%" align="center"><strong>2</strong></th>
                <th width="4%" align="center"><strong>3</strong></th>
                <th width="4%" align="center"><strong>4</strong></th>
                <th width="4%" align="center"><strong>5</strong></th>
            </tr>
        </thead>
        <tbody>
            <!-- Conocimientos -->
            <tr>
                <td rowspan="3" align="center" width="13%"><strong><br><br><br>Conocimientos</strong></td>
                <td width="67%">Diseñar e implementar algoritmos utilizando las técnicas de programación lineal, estructurada, procedimental y funcional.</td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 5) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 4) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 3) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 2) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 1) . '</font></td>
            </tr>
            <tr>
                <td>Utilizar las estructuras de datos básicas y compuestas, así como estáticas y dinámicas para la entrada y salida de datos, en la implementación de algoritmos que les den solución a problemas de requerimientos de software.</td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 5) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 4) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 3) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 2) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 1) . '</font></td>
            </tr>
            <tr>
                <td>Brindar soporte técnico y de mantenimiento a sistemas de hardware de cómputo.</td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 5) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 4) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 3) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 2) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 1) . '</font></td>
            </tr>
    
            <!-- Habilidades -->
            <tr>
                <td rowspan="5" align="center"><br><br><br><br><strong>Habilidades</strong></td>
                <td>Diseñar e implementar bases de datos mediante el Modelo-Entidad-Relación.</td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 5) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 4) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 3) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 2) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 1) . '</font></td>
            </tr>
            <tr>
                <td>Aplicar las formas normales en el diseño de bases de datos mediante el Modelo-Entidad-Relación.</td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 5) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 4) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 3) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 2) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 1) . '</font></td>
            </tr>
            <tr>
                <td>Optimizar el diseño de bases de datos implementadas.</td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 5) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 4) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 3) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 2) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 1) . '</font></td>
            </tr>
            <tr>
                <td>Identificar componentes de hardware de redes LAN.</td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 5) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 4) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 3) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 2) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 1) . '</font></td>
            </tr>
            <tr>
                <td>Optimizar el diseño de redes LAN.</td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 5) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 4) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 3) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 2) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 1) . '</font></td>
            </tr>
            <tr>
                <td></td>
                <td>Implementar y monitorear servicios de redes LAN</td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 5) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 4) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 3) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 2) . '</font></td>
                <td width="4%" align="center"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 1) . '</font></td>
            </tr>
    
            <!-- Promedio total -->
            <tr>
                <td colspan="2" align="right"><strong>PROMEDIO TOTAL</strong></td>
                <td colspan="5" align="center"><strong>' . $promedio . '</strong></td>
            </tr>
        </tbody>
    </table>';

$html_tabla7 = '
    <table border="0.5" cellpadding="1" cellspacing="0">
        <tr>
            <td colspan="2" align="center"><strong>Observaciones</strong></td>
        </tr>
        <tr>
            <td colspan="2" align="justify">En la evaluación final se puede observar que el estudiante mejoro de forma satisfactoria sus habilidades y destrezas, con la intervención del
Docente tutor donde aplico un correcto seguimiento y control de las actividades asignadas durante sus prácticas.</td>
        </tr>
    </table>
';

$firmas = '  <table width="100%" style="font-size: 11px;">
  <tr>
    <!-- Firma del Estudiante -->
    <td style="text-align: center; width: 45%;">
      <div>____________________________________</div>
      <strong>Firma del Estudiante</strong>
    </td>

    <td style="width: 10%;"></td>

    <!-- Firma del Tutor de la Entidad Receptora -->
    <td style="text-align: center; width: 45%;">
      <div>____________________________________</div>
      <strong>Firma y Sello del Docente/Tutor</strong>
    </td>
  </tr>
</table>';

$pdf->writeHTML($html_tabla6, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla7, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY + 5);
$pdf->writeHTMLCell(0, 1, '', '', $firmas, 0, 1, false, true, 'C');

$pdf->Output('EVALUACIÓN FINAL DEL ESTUDIANTE.pdf', 'I');
