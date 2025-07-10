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

// Función para mostrar la fecha como "10 de julio de 2025"
function formatearFechaTexto($fecha)
{
    $meses = [
        '01' => 'enero', '02' => 'febrero', '03' => 'marzo',
        '04' => 'abril', '05' => 'mayo', '06' => 'junio',
        '07' => 'julio', '08' => 'agosto', '09' => 'septiembre',
        '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'
    ];

    $partes = explode('-', $fecha); // [año, mes, día]
    $anio = $partes[0];
    $mes = $meses[$partes[1]] ?? 'mes inválido';
    $dia = intval($partes[2]);

    return "$dia de $mes de $anio";
}

// === Obtener datos del formulario ===
$nombres   = sanitizar('nombres', 'nombre');
$apellidos = sanitizar('apellidos', 'nombre');
$cedula    = sanitizar('cedula');
$fecha     = sanitizar('fecha');

$nombreCompleto = "$nombres $apellidos";
$fechaTexto     = formatearFechaTexto($fecha);
$añoActual      = date('Y');

// === Crear PDF ===
class CustomPDF extends TCPDF
{
    public function Header()
    {
        $this->Image('../../../image/logo-emapa.png', 20, 12, 30);

        $this->SetFont('helvetica', 'B', 9);
        $this->SetTextColor(0, 81, 158);

        $this->SetXY(63, 13);
        $this->MultiCell(
            80,
            5,
            "Vicente Piedrahíta y Guayaquil CC Yanco\nDaule - Ecuador\nTel: 2-795-911 / 2-795-644",
            0,
            'C'
        );

        $this->SetXY(125, 13);
        $this->MultiCell(
            60,
            5,
            "www.emapadaule.gob.ec\ninfo@emapadaule.gob.ec",
            0,
            'R'
        );

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 11);
    }

    public function Footer()
    {
        $this->SetY(-30);
        $this->SetFont('times', 'B', 12);
        $this->Cell(0, 10, 'NO DEUDOR', 0, 0, 'C');
    }
}

$pdf = new CustomPDF();
$pdf->AddPage();
$pdf->SetMargins(25, 40, 25);
$pdf->SetY(40);

// Encabezado
$pdf->SetFont('times', 'B', 14);
$pdf->Cell(0, 10, 'Dirección comercial', 0, 1, 'C');
$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 8, 'CONSTANCIA', 0, 1, 'C');
$pdf->SetFont('times', '', 11);
$pdf->Cell(0, 6, 'No. EMAPA-EP-DC-2020-0018-C', 0, 1, 'C');
$pdf->Ln(5);

// Contenido del certificado
$html = <<<HTML
<p style="text-align: justify; font-size: 11pt; line-height: 1.6;">
A petición de la señor/a <strong>$nombreCompleto</strong>, portador/a de la cédula de identidad No. <strong>$cedula</strong> en oficio s/n de fecha $fechaTexto, en la cual solicita: 
<strong>“ME EXTIENDAN UN CERTIFICADO DE NO ADEUDAR NI TENER CONVENIO CON USTEDES”.</strong>
</p>
<p style="text-align: justify; font-size: 11pt; line-height: 1.6;">
Informo que revisado los archivos estadísticos en nuestro Sistema informático Aqua Comercial se constató que la persona en mención 
hasta la presente fecha no registra valores pendientes, ya que <strong>NO</strong> es usuaria de la EMAPA-EP DAULE, por ende no registra ni 
deuda ni valores comprometidos en convenio de pago con la institución.
</p>
<p style="text-align: justify; font-size: 11pt; line-height: 1.6;">
Constancia que se extiende para que el interesado haga el uso legal pertinente, cuya vigencia es de 30 días plazo.
</p>
HTML;

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(15);

// Firma y pie de página
$pdf->SetFont('times', '', 11);
$pdf->Cell(0, 6, 'Atentamente,', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('times', 'B', 11);
$pdf->Cell(0, 6, 'Director Comercial', 0, 1, 'C');
$pdf->Ln(4);
$pdf->SetFont('times', '', 10);
$pdf->Cell(0, 6, "Daule, $fechaTexto", 0, 1, 'L');
$pdf->SetFont('times', 'I', 9);
$pdf->Cell(0, 6, 'C/c archivo', 0, 1, 'L');

// Salida del PDF
$pdf->Output('constancia_no_deuda.pdf', 'I');
