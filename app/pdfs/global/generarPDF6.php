<?php
require_once('../../../../TCPDF-main/tcpdf.php');

// Variables desde el formulario POST
$nombre_entidad_receptora = isset($_POST['nombre_entidad']) ? trim($_POST['nombre_entidad']) : 'NO APLICA';
$ruc = isset($_POST['ruc']) ? trim($_POST['ruc']) : 'NO APLICA';
$actividad_economica = isset($_POST['actividad_economica']) ? trim($_POST['actividad_economica']) : 'NO APLICA';
$direccion_entidad_receptora = isset($_POST['direccion']) ? trim($_POST['direccion']) : 'NO APLICA';
$nombre_ciudad_entidad_receptora = isset($_POST['ciudad']) ? ucwords(strtolower(trim($_POST['ciudad']))) : 'NO APLICA';
$provincia = isset($_POST['provincia']) ? ucwords(strtolower(trim($_POST['provincia']))) : 'NO APLICA';

$nombre_completo_estudiante = isset($_POST['nombres_estudiante']) ? ucwords(strtolower(trim($_POST['nombres_estudiante']))) : 'NO APLICA';

$hora_inicio = isset($_POST['horario_practica_inicio']) ? trim($_POST['horario_practica_inicio']) : 'NO APLICA';
$hora_fin = isset($_POST['horario_practica_fin']) ? trim($_POST['horario_practica_fin']) : 'NO APLICA';
$jornada_laboral = isset($_POST['jornada_laboral']) ? ucwords(strtolower(trim($_POST['jornada_laboral']))) : 'NO APLICA';

$nombres_tutor_receptor = isset($_POST['nombre_tutor']) ? ucwords(strtolower(trim($_POST['nombre_tutor']))) : 'NO APLICA';
$cargo_tutor_receptor = isset($_POST['cargo_tutor']) ? ucwords(strtolower(trim($_POST['cargo_tutor']))) : 'NO APLICA';
$numero_practicas = isset($_POST['numero_practicas']) ? trim($_POST['numero_practicas']) : 'NO APLICA';
$numero_institucional = isset($_POST['telefono_institucional']) ? trim($_POST['telefono_institucional']) : 'NO APLICA';
$numero_telefono = isset($_POST['telefono_celular']) ? trim($_POST['telefono_celular']) : 'NO APLICA';
$correo_representante = isset($_POST['correo_tutor']) ? trim($_POST['correo_tutor']) : 'NO APLICA';


$fecha_inicio_larga = isset($_POST['fecha_inicio']) ? formato_fecha_larga($_POST['fecha_inicio']) : 'NO APLICA';
$fecha_fin_larga    = isset($_POST['fecha_fin']) ? formato_fecha_larga($_POST['fecha_fin']) : 'NO APLICA';

$nombre_doc = '6 FICHA DE ENTIDAD RECEPTORA';


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
$pdf->SetMargins(23, 35, 23);
$pdf->SetY(35);

$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 1, 'FICHA DE ENTIDAD RECEPTORA', 0, 1, 'C');
$pdf->Ln(3);

$pdf->SetFont('times', '', 12);
$tabla1 = '
<table border="0.5" cellpadding="1" cellspacing="0">
    <tr>
        <td style="font-size: 12px; line-height: 1.7;">
                <strong>Nombre de la entidad receptora:</strong><br>
                ' . $nombre_entidad_receptora . '
        </td>
    </tr>
</table>
';

$tabla2 = '
<table border="0.5" cellpadding="1" cellspacing="0">
    <tr>
        <td style="font-size: 12px; line-height: 1.7;">
                <strong>RUC:</strong><br>
                ' . $ruc . '
        </td>
    </tr>
</table>
';

$tabla3 = '
<table border="0.5" cellpadding="1" cellspacing="0">
    <tr>
        <td style="font-size: 12px; line-height: 1.7;">
                <strong>Actividad económica principal:</strong><br>
                ' . $actividad_economica . '
        </td>
    </tr>
</table>
';

$tabla4 = '
<table border="0.5" cellpadding="1" cellspacing="0">
    <tr>
        <td style="font-size: 12px; line-height: 1.7;">
                <strong>Dirección:</strong><br>
                ' . $direccion_entidad_receptora . '
        </td>
    </tr>
</table>
';

$tabla5 = '
<table border="0.5" cellpadding="1" cellspacing="0" width="100%">
    <tr>
        <td style="width: 50%; font-size: 12px; line-height: 1.7;">
            <strong>Ciudad:</strong><br>
            ' . $nombre_ciudad_entidad_receptora . '
        </td>
        <td style="width: 50%; font-size: 12px; line-height: 1.7;">
            <strong>Provincia:</strong><br>
            ' . $provincia . '
        </td>
    </tr>
</table>
';

$tabla6 = '
<table border="0.5" cellpadding="1" cellspacing="0" width="100%">
    <tr>
        <td style="width: 50%; font-size: 12px; line-height: 1.7;">
            <strong>Fecha de inicio de la práctica:</strong><br>
            ' . $fecha_inicio_larga . '
        </td>
        <td style="width: 50%; font-size: 12px; line-height: 1.7;">
            <strong>Fecha de culminación de la práctica:</strong><br>
            ' . $fecha_fin_larga . '
        </td>
    </tr>
</table>
';

$tabla7 = '
<table border="0.5" cellpadding="1" cellspacing="0" width="100%">
    <tr>
        <td style="width: 50%; font-size: 12px; line-height: 1.7;">
            <strong>Horario de la práctica:</strong><br>
            ' . $hora_inicio . ' a ' . $hora_fin . '
        </td>
        <td style="width: 50%; font-size: 12px; line-height: 1.7;">
            <strong>Jornada laboral:</strong><br>
            ' . $jornada_laboral . '
        </td>
    </tr>
</table>
';

$tabla8 = '
<table border="0.5" cellpadding="1" cellspacing="0">
    <tr>
        <td style="font-size: 12px; line-height: 1.7;">
                <strong>Nombres y Apellidos del tutor de la entidad receptora:</strong><br>
                ' . $nombres_tutor_receptor . '
        </td>
    </tr>
</table>
';

$tabla9 = '
<table border="0.5" cellpadding="1" cellspacing="0" width="100%">
    <tr>
        <td style="width: 50%; font-size: 12px;">
            <strong>Cargo del tutor de la entidad receptora:</strong><br>
            ' . $cargo_tutor_receptor . '
        </td>
        <td style="width: 50%; font-size: 12px;">
            <strong>Número de prácticas:</strong><br>
            ' . $numero_practicas . '
        </td>
    </tr>
</table>
';

$tabla10 = '
<table border="0.5" cellpadding="1" cellspacing="0" width="100%">
    <tr>
        <td style="width: 50%; font-size: 12px; line-height: 1.7;">
            <strong>Número de teléfono institucional: </strong><br>
            ' . (!empty($numero_institucional) ? $numero_institucional : 'NO APLICA') . '
        </td>
        <td style="width: 50%; font-size: 12px; line-height: 1.7;">
            <strong>Número de teléfono celular:</strong><br>
            ' . $numero_telefono . '
        </td>
    </tr>
</table>
';

$tabla11 = '
<table border="0.5" cellpadding="1" cellspacing="0">
    <tr>
        <td style="font-size: 12px; line-height: 1.7;">
                <strong>Dirección de correo electrónico:</strong><br>
                ' . $correo_representante . '
        </td>
    </tr>
</table>
';

$pdf->writeHTMLCell('', '', '', '', $tabla1, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla2, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla3, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla4, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla5, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla6, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla7, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla8, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla9, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla10, 0, 1, 0, true, 'J', '', '');
$pdf->Ln(4);
$pdf->writeHTMLCell('', '', '', '', $tabla11, 0, 1, 0, true, 'J', '', '');



$pdf->Ln(14);
// Definir ancho deseado para la línea
$ancho_linea = 100;

// Calcular X para centrar el contenido horizontalmente
$centro_x = ($pdf->GetPageWidth() - $ancho_linea) / 2;

$pdf->SetFont('times', 'B', 12);
$pdf->SetX($centro_x);
$pdf->Cell($ancho_linea, 6, $nombre_completo_estudiante, 'B', 1, 'C');




$pdf->Output($nombre_doc . '.pdf', 'I');
