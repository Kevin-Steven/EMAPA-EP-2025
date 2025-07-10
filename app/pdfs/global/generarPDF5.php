<?php
require_once('../../../../TCPDF-main/tcpdf.php');

// Extraer variables del formulario
$nombres = isset($_POST['nombres']) ? ucwords(strtolower(trim($_POST['nombres']))) : 'NO APLICA';
$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : 'NO APLICA';
$carrera = isset($_POST['carrera']) ? trim($_POST['carrera']) : 'NO APLICA';
$nombre_doc = '5 CARTA DE COMPROMISO';

$nombre_entidad_receptora = isset($_POST['nombre_entidad']) ? trim($_POST['nombre_entidad']) : 'NO APLICA';
$ruc = isset($_POST['ruc']) ? trim($_POST['ruc']) : 'NO APLICA';
$direccion_entidad_receptora = isset($_POST['direccion-entidad']) ? trim($_POST['direccion-entidad']) : 'NO APLICA';

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

$nombre_ciudad = isset($_POST['ciudad']) ? ucwords(strtolower(trim($_POST['ciudad']))) : 'NO APLICA';
$nombre_representante_rrhh = isset($_POST['nombres-representante-rrhh']) ? ucwords(strtolower(trim($_POST['nombres-representante-rrhh']))) : 'NO APLICA';
$numero_institucional = isset($_POST['numero_institucional']) ? trim($_POST['numero_institucional']) : 'NO APLICA';
$correo_institucional = isset($_POST['correo-institucional']) ? trim($_POST['correo-institucional']) : 'NO APLICA';
$nombre_rector = $_POST['rector'] ?? 'NO APLICA';

$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_inicio_larga = $fecha_inicio ? formato_fecha_larga($fecha_inicio) : 'NO APLICA';

function formato_fecha_larga($fecha) {
    $meses = [
        'enero','febrero','marzo','abril','mayo','junio',
        'julio','agosto','septiembre','octubre','noviembre','diciembre'
    ];
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$fecha_obj) return 'N/A';
    return $fecha_obj->format('d') . ' de ' . $meses[(int)$fecha_obj->format('m') - 1] . ' del ' . $fecha_obj->format('Y');
}

class CustomPDF extends TCPDF {
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

$pdf = new CustomPDF();
$pdf->AddPage();
$pdf->SetMargins(23, 35, 23);
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
$pdf->Cell(0, 1, 'CARTA DE COMPROMISO', 0, 1, 'C');
$pdf->Ln(4);
$pdf->SetFont('times', 'I', 11);
$pdf->Cell(0, 1, $nombre_ciudad . ', ' . $fecha_inicio_larga, 0, 1, 'R');
$pdf->Ln(4);

$pdf->SetFont('times', 'B', 11);
$pdf->Cell(0, 1, $nombre_rector, 0, 1, 'L');
$pdf->SetFont('times', '', 11);
$pdf->Cell(0, 1, 'Rector.', 0, 1, 'L');
$pdf->Cell(0, 1, 'Instituto Superior Tecnológico Bolivariano de Tecnología.', 0, 1, 'L');
$pdf->Cell(0, 1, 'Guayaquil.', 0, 1, 'L');
$pdf->Ln(1);

$html_linea1 = '<p style="font-size: 11px; line-height: 1.5;">En su despacho.</p>';
$html_linea2 = '<p style=" font-size: 11px; line-height: 3;">De mis consideraciones:</p> ';
$html_linea3 = '<p style=" font-size: 11px; line-height: 1.9; text-indent: 34px;">En mi calidad de representante de Talento Humano de la empresa
<strong>' . $nombre_entidad_receptora . '</strong>, con RUC número <strong>' . $ruc . '</strong>,  con sede en <strong>' . $direccion_entidad_receptora . '</strong>, dedicada a Educación; manifiesto nuestro compromiso de participar como “entidad
receptora”, para la realización de las prácticas laborales del estudiante <strong>' . $nombres . '</strong>,
con cédula de identidad número <strong>' . $cedula . '</strong>, de la carrera <strong>' . $carrera . '</strong>,
 del Instituto Superior Tecnológico Bolivariano de Tecnología. <br> Agradecemos de antemano por la predisposición de su Institución, por la atención prestada y
por el trámite que se le dé a la presente, en función del mejoramiento de la calidad de la Educación
Superior ecuatoriana. Con sentimientos de estima y respeto, me suscribo de usted. <br> Atentamente, </p>';

$pdf->writeHTMLCell('', '', '', '', $html_linea1, 0, 1, 0, true, 'J', true);
$pdf->writeHTMLCell('', '', '', '', $html_linea2, 0, 1, 0, true, 'J', true);
$pdf->writeHTMLCell('', '', '', '', $html_linea3, 0, 1, 0, true, 'J', true);
$pdf->Ln(7);

$pdf->Ln(13);
$pdf->SetFont('times', 'B', 11);
$pdf->Cell(0, 1, '__________________________________________________________', 0, 1, 'L');
$pdf->Cell(0, 1, $nombre_representante_rrhh, 0, 1, 'L');
$pdf->Cell(0, 8, 'Representante de Talento Humano de la entidad receptora', 0, 1, 'L');
$pdf->SetFont('times', '', 11);
$pdf->Cell(0, 8, 'Dirección: ' . $direccion_entidad_receptora, 0, 1, 'L');
$pdf->Cell(0, 8, 'Teléfono: ' . $numero_institucional, 0, 1, 'L');
$pdf->Cell(0, 8, 'Correo electrónico: ' . $correo_institucional, 0, 1, 'L');

$pdf->Output('5 CARTA DE COMPROMISO.pdf', 'I');
@unlink($logo_entidad_receptora);
