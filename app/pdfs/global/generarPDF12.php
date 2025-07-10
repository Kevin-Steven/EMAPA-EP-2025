<?php
require_once('../../../../TCPDF-main/tcpdf.php');

// Extraer variables
$nombres = isset($_POST['nombres']) ? ucwords(strtolower(trim($_POST['nombres']))) : 'NO APLICA';
$carrera = $_POST['carrera'] ?? 'NO APLICA';
$actividad_realizada = isset($_POST['actividad_realizada']) ? trim($_POST['actividad_realizada']) : 'NO APLICA';
$nombre_doc = '12 SUPERVISIÓN DE LA PRÁCTICA LABORAL AL ESTUDIANTE ENTIDAD RECEPTORA (ENTIDAD RECEPTORA)';

$fecha_inicio_larga = !empty($_POST['fecha_inicio']) ? formato_fecha_larga($_POST['fecha_inicio']) : 'NO APLICA';
$fecha_fin_larga = !empty($_POST['fecha_fin']) ? formato_fecha_larga($_POST['fecha_fin']) : 'NO APLICA';

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

// Ruta base absoluta para las imágenes temporales
$temp_dir = __DIR__ . '/temp_uploads/';

// Crear el directorio si no existe
if (!file_exists($temp_dir)) {
    mkdir($temp_dir, 0777, true);
}

// Función reutilizable para guardar cualquier imagen del formulario
function guardar_imagen_temporal($key, $nombre_archivo) {
    global $temp_dir; // Usamos la variable global para mantener consistencia

    if (isset($_FILES[$key]) && is_uploaded_file($_FILES[$key]['tmp_name'])) {
        $ruta_destino = $temp_dir . $nombre_archivo;
        if (move_uploaded_file($_FILES[$key]['tmp_name'], $ruta_destino)) {
            return realpath($ruta_destino); // ✅ Devuelve la ruta absoluta
        }
    }

    return ''; // Retorna cadena vacía si no se cargó la imagen correctamente
}

// Guardar imágenes específicas subidas por el usuario
$img_practicas_puesto_trabajo   = guardar_imagen_temporal('img_practicas_puesto_trabajo',   'puesto_' . time() . '.jpg');
$img_puesto_trabajo             = guardar_imagen_temporal('img_puesto_trabajo',             'trabajo_' . time() . '.jpg');
$img_estudiante_tutor_entidad   = guardar_imagen_temporal('img_estudiante_tutor_entidad',   'tutor_'  . time() . '.jpg');
$img_cierre_practicas           = guardar_imagen_temporal('img_cierre_practicas',           'cierre_' . time() . '.jpg');

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
$pdf->Cell(0, 1, '(SOLO PARA USO DEL SUPERVISOR ENTIDAD RECEPTORA)', 0, 1, 'C');
$pdf->Ln(5);

$info = '<p>Indique con una “X” la evaluación que usted considere adecuada, en el momento de la supervisión
durante la Práctica laboral, teniendo en cuenta el cumplimiento de los siguientes indicadores: </p>';
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
    </table>
    <table border="0.5" cellpadding="3" cellspacing="0">
        <tr>
            <td colspan="2" align="center" style="font-size: 11px;"><strong>EJECUCION DE LA PRACTICA:</strong></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 11px; text-align: justify;">El estudiante <strong>' . $nombres . '</strong>, de la carrera de <strong>' . $carrera . '</strong> realiza las siguientes actividades: <strong>'. $actividad_realizada .'.</strong></td>
        </tr>
    </table>
    <table border="0.5" cellpadding="3" cellspacing="0">
        <tr>
            <td colspan="2" align="center" style="font-size: 11px;"><strong>OBSERVACIONES:</strong></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 11px; text-align: justify;">Mediante la supervisión realizada al estudiante se verificó que cumple con los indicadores relacionados con el objeto de su profesión.</td>
        </tr>
    </table>';


$firmas = '  <table width="100%" style="font-size: 11px;">
  <tr>
    <td style="text-align: center; width: 45%;">
      <div>____________________________________</div>
      <strong>Firma y sello supervisor de la<br> entidad receptora</strong>
    </td>

    <td style="width: 10%;"></td>

    <!-- Firma del Tutor de la Entidad Receptora -->
    <td style="text-align: center; width: 45%;">
      <div>____________________________________</div>
      <strong>Firma y sello del Docente Tutor</strong>
    </td>
  </tr>
</table>';
$pdf->writeHTML($html_tabla1, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY + 5);
$pdf->writeHTMLCell(0, 1, '', '', $firmas, 0, 1, false, true, 'C');

$pdf->AddPage();
$pdf->SetY(40);
$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 1, 'EVIDENCIAS', 0, 1, 'L');
$pdf->Ln(5);

$html_evidencias = '
<table border="0.5" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <td width="50%" align="center" valign="middle">
            <img src="' . $img_practicas_puesto_trabajo . '" width="150">
        </td>
        <td width="50%" align="center" valign="middle">
            <img src="' . $img_puesto_trabajo . '" width="150">
        </td>
    </tr>
    <tr>
        <td align="center"><strong>Realización de prácticas en el puesto de trabajo</strong></td>
        <td align="center"><strong>Puesto de trabajo</strong></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="middle">
            <img src="' . $img_estudiante_tutor_entidad . '" width="150">
        </td>
        <td width="50%" align="center" valign="middle">
            <img src="' . $img_cierre_practicas . '" width="150">
        </td>
    </tr>
    <tr>
        <td align="center"><strong>Estudiante y tutor de las prácticas (Entidad Receptora)</strong></td>
        <td align="center"><strong>Cierre de prácticas laborales (Culminación de Prácticas)</strong></td>
    </tr>
</table>';


$pdf->writeHTML($html_evidencias, true, false, true, false, '');
$pdf->Output($nombre_doc . '.pdf', 'I');
@unlink($img_practicas_puesto_trabajo);
@unlink($img_puesto_trabajo);
@unlink($img_estudiante_tutor_entidad);
@unlink($img_cierre_practicas);