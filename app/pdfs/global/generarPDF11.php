<?php
require_once('../../../../TCPDF-main/tcpdf.php');

// Extraer variables
$nombres = isset($_POST['nombres']) ? ucwords(strtolower(trim($_POST['nombres']))) : 'NO APLICA';
$cedula = $_POST['cedula'] ?? 'NO APLICA';
$carrera = $_POST['carrera'] ?? 'NO APLICA';
$nombre_entidad_receptora = isset($_POST['nombre_entidad_receptora']) ? trim($_POST['nombre_entidad_receptora']) : 'NO APLICA';

$nombre_doc = '11 SUPERVISIÓN DE LA PRÁCTICA LABORAL AL ESTUDIANTE (SUPERVISOR ACÁDEMICO)';

$fecha_inicio_larga = !empty($_POST['fecha_inicio']) ? formato_fecha_larga($_POST['fecha_inicio']) : 'NO APLICA';
$fecha_fin_larga = !empty($_POST['fecha_fin']) ? formato_fecha_larga($_POST['fecha_fin']) : 'NO APLICA';

// Indicadores de cumplimiento (radio buttons)
$opcion_uno    = isset($_POST['pregunta1']) ? (int)$_POST['pregunta1'] : 0;
$opcion_dos    = isset($_POST['pregunta2']) ? (int)$_POST['pregunta2'] : 0;
$opcion_tres   = isset($_POST['pregunta3']) ? (int)$_POST['pregunta3'] : 0;
$opcion_cuatro = isset($_POST['pregunta4']) ? (int)$_POST['pregunta4'] : 0;
$opcion_cinco  = isset($_POST['pregunta5']) ? (int)$_POST['pregunta5'] : 0;
$opcion_seis   = isset($_POST['pregunta6']) ? (int)$_POST['pregunta6'] : 0;

$puntajes = [
    $opcion_uno,
    $opcion_dos,
    $opcion_tres,
    $opcion_cuatro,
    $opcion_cinco,
    $opcion_seis,
];

$cumple = array_sum($puntajes);
$no_cumple = count($puntajes) - $cumple;


function generarChecks($valor, $columna)
{
    return ($valor == $columna) ? '☒' : '☐';
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
$pdf->SetMargins(23, 20, 23);
$pdf->SetY(33);

$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 1, 'SUPERVISIÓN DE LA PRÁCTICA LABORAL AL ESTUDIANTE', 0, 1, 'C');
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 1, '(SOLO PARA USO DEL SUPERVISOR ACADÉMICO)', 0, 1, 'C');
$pdf->Ln(5);
$supervision = '  <table width="100%">
  <tr>
    <td style="text-align: center; width: 45%;"><strong>Supervisado <font face="dejavusans">☒</font></strong></td>

    <td style="width: 10%;"></td>

    <td style="text-align: center; width: 45%;"><strong>No supervisado <font face="dejavusans">☐</font></strong></td>
  </tr>
</table>';

$info = '<p>Indique con una “X” la evaluación que usted considere adecuada, en el momento de la supervisión
durante la Práctica Pre-profesional laboral, teniendo en cuenta el cumplimiento de los siguientes
indicadores: </p>';
$pdf->writeHTML($supervision, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 4);
$pdf->writeHTML($info, true, false, true, false, '');
$pdf->Ln(3);

$pdf->SetFont('times', '', 12);

$html_tabla1 = '
    <table border="0.5" cellpadding="3" cellspacing="0">
        <thead>
            <tr>
                <th colspan="2" width="70%" align="center"><strong>INDICADORES:</strong></th>
                <th width="15%" align="center"><strong>Cumple</strong></th>
                <th width="15%" align="center"><strong>No Cumple</strong></th>
            </tr>
        </thead>
        <tbody>
            <!-- Conocimientos -->
            <tr>
                <td width="70%">El estudiante se encuentra en el área de trabajo asignada. </td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_uno, 1) . '</font></td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_uno, 0) . '</font></td>
            </tr>
            <tr>
                <td width="70%">El estudiante se observa con la vestimenta adecuada según el área de trabajo.</td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_dos, 1) . '</font></td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_dos, 0) . '</font></td>
            </tr>
            <tr>
                <td width="70%">El estudiante cuenta con los recursos necesarios para realizar sus prácticas.</td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_tres, 1) . '</font></td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_tres, 0) . '</font></td>
            </tr>
            <tr>
                <td width="70%">Existencia del docente que asigne y controle las actividades del estudiante.</td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_cuatro, 1) . '</font></td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_cuatro, 0) . '</font></td>
            </tr>
            <tr>
                <td width="70%">Los formatos de la carpeta de prácticas pre-profesionales laborales se han ido completando adecuadamente.</td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_cinco, 1) . '</font></td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_cinco, 0) . '</font></td>
            </tr>
            <tr>
                <td width="70%">Las actividades que realiza el estudiante están relacionadas con el objeto de la profesión.</td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_seis, 1) . '</font></td>
                <td width="15%" align="center"><font face="dejavusans">' . generarChecks($opcion_seis, 0) . '</font></td>
            </tr>

    
            <!-- Promedio total -->
            <tr>
                <td align="center"><strong>TOTAL</strong></td>
                <td align="center"><strong>' . $cumple . '</strong></td>
                <td align="center"><strong>' . $no_cumple . '</strong></td>
            </tr>
        </tbody>
    </table>';

$html_parrafo = '
    <p align="center" line-height="1" style="font-size: 10px;"><strong>OBSERVACIONES</strong></p>
    ';
$html_tabla2 = '
    <table border="0.5" cellpadding="2" cellspacing="0">
        <tr>
            <td colspan="2" style="font-size: 10px;" align="justify">El estudiante <strong>' . $nombres . '</strong> de la carrera de <strong>' . $carrera . '</strong> se encuentra cumpliendo las actividades de prácticas preprofesionales en la Entidad Receptora:
<strong>' . $nombre_entidad_receptora . '</strong> durante su planificación desde <strong>' . $fecha_inicio_larga . '</strong>
hasta <strong>' . $fecha_fin_larga . '</strong> acorde a los indicadores establecidos en el presente documento y en concordancia
a las destrezas y habilidades del estudiante en su asignación de prácticas preprofesional.</td>
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
      <strong>Firma y Sello del Docente Tutor</strong>
    </td>
  </tr>
</table>';
$pdf->writeHTML($html_tabla1, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_parrafo, true, false, true, false, '');
$pdf->writeHTML($html_tabla2, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY + 5);
$pdf->writeHTMLCell(0, 1, '', '', $firmas, 0, 1, false, true, 'C');

$pdf->Output($nombre_doc . '.pdf', 'I');
