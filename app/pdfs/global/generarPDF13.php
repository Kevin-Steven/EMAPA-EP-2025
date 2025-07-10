<?php
require_once('../../../../TCPDF-main/tcpdf.php');

$nombre_doc = '13 CERTIFICACIÓN DE REALIZACIÓN DE PRÁCTICAS LABORALES';
$nombres = strtoupper($_POST['nombres'] ?? 'NO APLICA');
$cedula = $_POST['cedula'] ?? 'NO APLICA';
$carrera = $_POST['carrera'] ?? 'NO APLICA';
$hora_practicas = $_POST['hora_practicas'] ?? 'NO APLICA';
$fecha_inicio_larga = !empty($_POST['fecha_inicio']) ? formato_fecha_larga($_POST['fecha_inicio']) : 'NO APLICA';
$fecha_fin_larga = !empty($_POST['fecha_fin']) ? formato_fecha_larga($_POST['fecha_fin']) : 'NO APLICA';

// Datos entidad
$nombre_entidad_receptora = $_POST['nombre_entidad_receptora'] ?? 'NO APLICA';
$ciudad_entidad = $_POST['ciudad_entidad'] ?? 'NO APLICA';
$direccion_entidad_receptora = $_POST['direccion-entidad'] ?? 'NO APLICA';
$nombres_revisor = isset($_POST['nombres_revisor']) ? ucwords(strtolower(trim($_POST['nombres_revisor']))) : 'NO APLICA';
$nombres_responsable = isset($_POST['nombres-responsable']) ? ucwords(strtolower(trim($_POST['nombres-responsable']))) : 'NO APLICA';

$correo_institucional = $_POST['correo-institucional'] ?? 'NO APLICA';
$numero_institucional = $_POST['numero_institucional'] ?? 'NO APLICA';

$temp_dir = __DIR__ . '/temp_uploads/';
if (!file_exists($temp_dir)) {
    mkdir($temp_dir, 0777, true);
}

function guardar_imagen_temporal($key, $nombre_archivo) {
    global $temp_dir;

    if (isset($_FILES[$key]) && is_uploaded_file($_FILES[$key]['tmp_name'])) {
        $ruta_destino = $temp_dir . $nombre_archivo;
        if (move_uploaded_file($_FILES[$key]['tmp_name'], $ruta_destino)) {
            return realpath($ruta_destino);
        }
    }

    return '';
}

$logo_entidad_receptora = guardar_imagen_temporal('logo-entidad', 'logo_' . time() . '.jpg');


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
    public function Header() {}

    public function Footer() {}

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
$pdf->SetMargins(23, 35, 23);
$pdf->AddPage();
$pdf->SetY(25);

if ($logo_entidad_receptora && file_exists($logo_entidad_receptora)) {
    $html_logo = '
    <div style="text-align: center; margin-top: 10px;">
        <img src="' . $logo_entidad_receptora . '" width="auto" height="50">
    </div>';
    $pdf->writeHTML($html_logo, true, false, true, false, '');
}
$pdf->Ln(6);

$pdf->SetFont('times', 'B', 14);
$pdf->Cell(0, 1, 'CERTIFICACIÓN DE REALIZACIÓN DE PRÁCTICAS LABORALES', 0, 1, 'C');
$pdf->Ln(15);
$pdf->SetFont('times', 'I', 11);
$pdf->Cell(0, 1, 'Guayaquil, ' . $fecha_fin_larga, 0, 1, 'R');
$pdf->Ln(15);
$pdf->SetFont('times', '', 12);

$html_linea1 = '<p style=" line-height: 1.9; text-indent: 34px;">Por medio de la presente, certifico que el (la) estudiante <strong>' . $nombres . '</strong>, con cédula de identidad número <strong>' . $cedula . '</strong>, de la carrera de <strong>' . $carrera . '</strong>, del Instituto Superior Tecnológico
Bolivariano de Tecnología, realizó sus prácticas laborales en la entidad receptora <strong>' . $nombre_entidad_receptora . '</strong>, ubicada en la ciudad de <strong>' . $ciudad_entidad . '</strong>, bajo la supervisión
de: <strong>Ing. ' . $nombres_revisor . '</strong>, con una duración de <strong>' . $hora_practicas . '</strong> horas, comenzando el
día <strong>' . $fecha_inicio_larga . '</strong> y terminando el día <strong>' . $fecha_fin_larga . '</strong>. </p>';

$html_linea2 = '<p style=" line-height: 1.9; text-indent: 34px;">Esta información se pone a consideración para los fines pertinentes. </p>';
$html_linea3 = '<p style=" line-height: 1.9; text-indent: 34px;">Atentamente, </p>';

$pdf->writeHTMLCell('', '', '', '', $html_linea1, 0, 1, 0, true, 'J', true);
$pdf->writeHTMLCell('', '', '', '', $html_linea2, 0, 1, 0, true, 'J', true);
$pdf->writeHTMLCell('', '', '', '', $html_linea3, 0, 1, 0, true, 'J', true);
$pdf->Ln(7);

$pdf->Ln(13);
$pdf->SetFont('times', '', 11);
$pdf->Cell(0, 1, '__________________________________________________________', 0, 1, 'L');
$pdf->Cell(0, 1, $nombres_responsable, 0, 1, 'L');
$pdf->SetFont('times', 'B', 11);
$pdf->Cell(0, 3, 'Responsable de la Práctica Preprofesional Laboral por parte de la ', 0, 1, 'L');
$pdf->Cell(0, 3, 'entidad receptora', 0, 1, 'L');
$pdf->SetFont('times', '', 11);
$pdf->Cell(0, 2, 'Dirección: ' . $direccion_entidad_receptora, 0, 1, 'L');
$pdf->Cell(0, 2, 'Teléfono: ' . $numero_institucional, 0, 1, 'L');
$pdf->Cell(0, 2, 'Correo electrónico: ' . $correo_institucional, 0, 1, 'L');

$pdf->Output($nombre_doc . '.pdf', 'I');
@unlink($logo_entidad_receptora);