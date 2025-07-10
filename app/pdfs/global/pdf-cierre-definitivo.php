<?php
require_once('../../../TCPDF-main/tcpdf.php');

function sanitizar(string $campo, string $tipo = 'texto', string $default = 'NO APLICA')
{
    if (!isset($_POST[$campo])) {
        return $default;
    }

    $valor = trim($_POST[$campo]);

    if ($valor === '') {
        return $default;
    }

    $valor = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');

    switch ($tipo) {
        case 'nombre':
        case 'ciudad':
        case 'direccion':
            return ucwords(strtolower($valor));

        case 'parrafo':
            return ucfirst($valor);

        case 'email':
            return strtolower($valor);

        default:
            return $valor;
    }
}

// Datos del formulario
$nombres         = sanitizar('nombres', 'nombre');
$apellidos       = sanitizar('apellidos', 'nombre');
$cedula          = sanitizar('cedula');
$numero_cuenta   = sanitizar('numero_cuenta');
$direccion       = sanitizar('direccion', 'direccion');
$telefono        = sanitizar('telefono');
$ciudad          = sanitizar('ciudad', 'ciudad');
$fecha           = sanitizar('fecha');
$email           = sanitizar('email', 'email');
$institucion     = sanitizar('institucion', 'direccion', 'Empresa Pública Municipal de Agua Potable y Alcanatarillado de Daule EMAPA-EP');
$motivo_cierre   = sanitizar('motivo_cierre', 'parrafo');
$forma_recepcion = sanitizar('forma_recepcion');

$venta_predio       = isset($_POST['venta_predio']) ? '✔' : '';
$cuenta_fallecido   = isset($_POST['cuenta_fallecido']) ? '✔' : '';
$casa_deshabitada   = isset($_POST['casa_deshabitada']) ? '✔' : '';
$otros              = isset($_POST['otros']) ? '✔' : '';


class PDF extends TCPDF
{
    public function Header()
    {
        $this->Image('../../../image/logo-emapa.png', 168, 6, 30);
        $this->Ln(5);
        $this->SetY($this->PageNo() == 1 ? 25 : 30);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('dejavusans', '', 8);
        $pageWidth = $this->getPageWidth();
        $margin = $this->getMargins();

        // Separador
        $this->Line($margin['left'], $this->GetY() - 2, $pageWidth - $margin['right'], $this->GetY() - 2);

        $leftX = $margin['left'];
        $centerX = $pageWidth / 2 - 20;
        $rightX = $pageWidth - $margin['right'] - 70;

        $pagina = $this->getAliasNumPage();
        $centro = 'EMAPA-EP DAULE';
        $derecha = 'Solicitud de acceso a la información pública';

        $this->SetXY($leftX, $this->GetY());
        $this->Cell(30, 5, $pagina, 0, 0, 'L');

        $this->SetXY($centerX, $this->GetY());
        $this->Cell(40, 5, $centro, 0, 0, 'C');

        $this->SetXY($rightX, $this->GetY());
        $this->MultiCell(70, 5, $derecha, 0, 'R');
    }
}

$pdf = new PDF();
$pdf->AddPage();

// TÍTULO desplazado
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetY(25);
$pdf->Cell(0, 10, 'SOLICITUD DE CIERRE DEFINITIVO', 0, 1, 'C');

// FECHA Y CIUDAD
$pdf->SetFont('helvetica', '', 11);
$pdf->SetXY(15, 40);
$pdf->Cell(20, 7, 'FECHA :', 0, 0);
$pdf->Rect(35, 40, 50, 7);
$pdf->SetXY(36, 40.5);
$pdf->Cell(0, 6, $fecha);

$pdf->SetXY(90, 40);
$pdf->Cell(25, 7, 'CIUDAD :', 0, 0);
$pdf->Rect(115, 40, 50, 7);
$pdf->SetXY(116, 40.5);
$pdf->Cell(0, 6, $ciudad);

// INSTITUCIÓN
$pdf->SetXY(15, 50);
$pdf->Cell(30, 7, 'INSTITUCIÓN:', 0, 0);
$institucionHeight = $pdf->getStringHeight(146, $institucion, false, true, '', 5);
$pdf->Rect(45, 50, 150, $institucionHeight + 2);
$pdf->SetXY(47, 51);
$pdf->MultiCell(146, 5, $institucion, 0, 'C');
$y = 50 + $institucionHeight + 7;

// SECCIÓN IDENTIFICACIÓN
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetXY(15, $y);
$pdf->Cell(0, 8, 'IDENTIFICACIÓN  DEL CLIENTE', 0, 1);
$pdf->SetFont('helvetica', '', 11);
$y += 10;

$height = 7;
$field_w = 100;
$label_w = 65;

function campoTexto($pdf, $label, $valor, $x, $y, $label_w, $field_w, $line_height)
{
    $label_lines = substr_count($label, "\n") + 1;
    $label_height = $line_height * $label_lines;
    $valor_height = $pdf->getStringHeight($field_w - 2, $valor, false, true, '', $line_height);
    $box_height = max($label_height, $valor_height);

    $pdf->SetXY($x, $y);
    $pdf->MultiCell($label_w, $line_height, $label, 0, 'L', false);
    $pdf->Rect($x + $label_w, $y, $field_w, $box_height);
    $pdf->SetXY($x + $label_w + 1, $y + 0.5);
    $pdf->MultiCell($field_w - 2, $line_height, $valor, 0, 'L');

    return $box_height + 2;
}

// CAMPOS
$y += campoTexto($pdf, 'NOMBRES:', $nombres, 15, $y, $label_w, $field_w, $height);
$y += campoTexto($pdf, 'APELLIDOS:', $apellidos, 15, $y, $label_w, $field_w, $height);
$y += campoTexto($pdf, 'N° DE CÉDULA:', $cedula, 15, $y, $label_w, $field_w, $height);
$y += campoTexto($pdf, "N° CUENTA DE LA FACTURA\nO NOTIFICACIÓN DE PAGO:", $numero_cuenta, 15, $y, $label_w, $field_w, $height);
$y += campoTexto($pdf, 'DIRECCIÓN DOMICILIARIA:', $direccion, 15, $y, $label_w, $field_w, $height);
$y += campoTexto($pdf, 'TELÉFONO FIJO /CELULAR:', $telefono, 15, $y, $label_w, $field_w, $height);

// MOTIVO DEL CIERRE
$pdf->SetXY(15, $y);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'ESPECIFIQUE EL MOTIVO DEL CIERRE DEFINITIVO :', 0, 1);
$pdf->SetFont('helvetica', '', 10);
$y += 8;
$motivo_height = $pdf->getStringHeight(176, $motivo_cierre, false, true, '', 5);
$pdf->Rect(15, $y, 180, $motivo_height + 2);
$pdf->SetXY(17, $y + 1);
$pdf->MultiCell(176, 5, $motivo_cierre, 0, 'L');
$y += $motivo_height + 10;

// FORMA DE RESPUESTA
$pdf->SetXY(15, $y);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'FORMA DE RECEPCIÓN DE LA RESPUESTA DE LA SOLICITUD DEL CIERRE :', 0, 1);
$y += 8;
$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(15, $y);
$pdf->Cell(25, 6, 'Presencial');
$pdf->Rect(40, $y, 6, 6);
$pdf->SetFont('dejavusans', '', 10);
if ($forma_recepcion === 'presencial') {
    $pdf->SetXY(41, $y + 0.5);
    $pdf->Cell(0, 5, '✔');
}
$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(60, $y);
$pdf->Cell(10, 6, 'Email:');
$pdf->Rect(75, $y, 90, 6);
$pdf->SetXY(77, $y + 0.5);
$pdf->Cell(0, 5, $email);
$y += 10;

// MOTIVOS DEL CIERRE
$pdf->SetXY(15, $y);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'MOTIVOS DEL CIERRE DEFINITIVO', 0, 1);
$y += 10;
$pdf->SetFont('dejavusans', '', 10);

$motivos = [
    'VENTA DEL PREDIO' => $venta_predio,
    'TITULAR DE LA CUENTA FALLECIDO' => $cuenta_fallecido,
    'CASA DESHABITADA' => $casa_deshabitada,
    'OTROS' => $otros
];


$i = 0;
foreach ($motivos as $motivo => $check) {
    $pdf->SetXY(15, $y + ($i * 7));
    $pdf->Cell(60, 6, $motivo);
    $pdf->Rect(80, $y + ($i * 7), 6, 6);
    if ($check === '✔') {
        $pdf->SetXY(81, $y + ($i * 7) + 0.5);
        $pdf->Cell(0, 5, '✔');
    }
    $i++;
}

$pdf->Output("Solicitud_Cierre_Definitivo.pdf", 'I');
exit();
