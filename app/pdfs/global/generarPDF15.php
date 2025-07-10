<?php
require_once('../../../../TCPDF-main/tcpdf.php');

$temp_dir = __DIR__ . '/temp_uploads/';
if (!file_exists($temp_dir)) {
    mkdir($temp_dir, 0777, true);
}

function guardar_imagen_temporal($key, $nombre) {
    $temp_dir = __DIR__ . '/temp_uploads/';
    if (!file_exists($temp_dir)) mkdir($temp_dir, 0777, true);

    if (isset($_FILES[$key]) && is_uploaded_file($_FILES[$key]['tmp_name'])) {
        $ruta = $temp_dir . $nombre;
        move_uploaded_file($_FILES[$key]['tmp_name'], $ruta);
        return realpath($ruta); // ✅ ruta absoluta del sistema
    }

    return ''; // Devuelve vacío si no se subió
}

// Guardar imágenes temporalmente
$img_estudiante_area_trabajo = guardar_imagen_temporal('img_estudiante_area_trabajo', 'evidencia_area_' . time() . '.jpg');
$img_estudiante_area_trabajo_herramientas = guardar_imagen_temporal('img_estudiante_area_trabajo_herramientas', 'evidencia_herramientas_' . time() . '.jpg');
$img_estudiante_supervisor_entidad = guardar_imagen_temporal('img_estudiante_supervisor_entidad', 'evidencia_supervisor_' . time() . '.jpg');

$nombre_doc = '15 EVIDENCIAS DEL ESTUDIANTES EN LA EJECUCIÓN DE PRÁCTICAS';

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
$pdf->SetMargins(25, 20, 25);
$pdf->SetY(38);

$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 1, 'Evidencias de fotos del estudiante en la ejecución de prácticas laborales. ', 0, 1, 'L');
$pdf->SetFont('times', '', 11);
$pdf->Ln(3);

$html_evidencias = '
<table border="0.5" cellpadding="5" cellspacing="0" width="100%">
    <tr>
        <td width="100%" align="center">
            1. Foto del estudiante que evidencie las actividades que realiza durante la práctica en el área de trabajo asignada.<br>
            <img src="' . $img_estudiante_area_trabajo . '" width="auto" height="160px">
        </td>
    </tr>
    <tr>
        <td width="100%" align="center">
            2. Foto del estudiante en su puesto de trabajo y las herramientas que utiliza para el desarrollo de las actividades asignadas.<br>
            <img src="' . $img_estudiante_area_trabajo_herramientas . '" width="auto" height="160px">
        </td>
    </tr>
    <tr>
        <td width="100%" align="center">
            3. Foto del estudiante y el supervisor de la empresa que evidencien las actividades realizadas durante la práctica en el área de trabajo asignada.<br>
            <img src="' . $img_estudiante_supervisor_entidad . '" width="auto" height="160px">
        </td>
    </tr>
</table>';


$pdf->writeHTML($html_evidencias, true, false, true, false, '');
$pdf->Output($nombre_doc . '.pdf', 'I');
@unlink($img_estudiante_area_trabajo);
@unlink($img_estudiante_area_trabajo_herramientas);
@unlink($img_estudiante_supervisor_entidad);

