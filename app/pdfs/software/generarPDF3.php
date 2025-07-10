<?php
require_once('../../../../TCPDF-main/tcpdf.php');
require_once('../../../config/config.php');

$carrera_id = isset($_POST['carrera_id']) ? trim($_POST['carrera_id']) : 'NO APLICA';
// Recibir datos del formulario
$nombres = isset($_POST['nombres']) ? ucwords(strtolower(trim($_POST['nombres']))) : 'NO APLICA';
$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : 'NO APLICA';
$carrera = isset($_POST['carrera']) ? ucwords(strtolower(trim($_POST['carrera']))) : 'NO APLICA';
$hora_practicas = isset($_POST['hora_practicas']) ? trim($_POST['hora_practicas']) : 'NO APLICA';

$nombre_tutor_academico = isset($_POST['nombres_tutor']) ? ucwords(strtolower(trim($_POST['nombres_tutor']))) : 'NO APLICA';
$nombre_tutor_receptor = isset($_POST['nombres_representante']) ? ucwords(strtolower(trim($_POST['nombres_representante']))) : 'NO APLICA';
$cargo_tutor_receptor = isset($_POST['cargo_representante']) ? ucwords(strtolower(trim($_POST['cargo_representante']))) : 'NO APLICA';

$ciudad_entidad_receptora = isset($_POST['ciudad']) ? ucwords(strtolower(trim($_POST['ciudad']))) : 'NO APLICA';
$nombre_entidad_receptora = isset($_POST['nombre_entidad']) ? ucwords(strtolower(trim($_POST['nombre_entidad']))) : 'NO APLICA';

$nombre_coordinador = isset($_POST['nombres-coordinador']) ? ucwords(strtolower(trim($_POST['nombres-coordinador']))) : 'NO APLICA';

$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';

function formato_fecha_larga($fecha)
{
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$fecha_obj) return 'N/A';
    return $fecha_obj->format('d') . ' de ' . $meses[((int)$fecha_obj->format('m')) - 1] . ' del ' . $fecha_obj->format('Y');
}

$fecha_inicio_larga = $fecha_inicio ? formato_fecha_larga($fecha_inicio) : 'N/A';
$fecha_fin_larga = $fecha_fin ? formato_fecha_larga($fecha_fin) : 'N/A';

class CustomPDF extends TCPDF {
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
}

$pdf = new CustomPDF();
$pdf->SetMargins(23, 35, 17);
$pdf->AddPage();
$pdf->SetY(38);
$pdf->SetFont('times', 'B', 14);
$pdf->Cell(0, 1, 'ASIGNACIÓN DE ESTUDIANTE A PRÁCTICAS LABORALES', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('times', '', 11);
$pdf->Cell(0, 1, 'Guayaquil, ' . $fecha_inicio_larga, 0, 1, 'R');
$pdf->Ln(2);
$pdf->SetFont('times', 'B', 11);
$pdf->Cell(0, 1, $nombre_tutor_receptor, 0, 1, 'L');
$pdf->Cell(0, 1, $cargo_tutor_receptor, 0, 1, 'L');
$pdf->Cell(0, 1, $nombre_entidad_receptora, 0, 1, 'L');
$pdf->Cell(0, 1, $ciudad_entidad_receptora, 0, 1, 'L');
$pdf->Ln(5);
$pdf->SetFont('times', '', 11);
$html_parrafo = '<p style="font-size: 11px; line-height: 1.3;">De mis consideraciones:<br>Reciba un cordial saludo de quienes conforman el Instituto Superior Bolivariano de Tecnología (ITB), de la Facultad de Ciencias Empresariales y Sistemas, y su carrera <strong>' . $carrera . '</strong>. Se detalla los datos de nuestro estudiante <strong>' . $nombres . '</strong>, con cédula de identidad número <strong>' . $cedula . '</strong>, que estará bajo la supervisión del: <strong>' . $nombre_tutor_academico . '</strong>, con una duración de <strong>' . $hora_practicas . '</strong>, comenzando el día <strong>' . $fecha_inicio_larga . '</strong> y terminando el día <strong>' . $fecha_fin_larga . '</strong>.<br><br>Adicionalmente, se relacionan las destrezas y habilidades del estudiante:  </p>';
$pdf->writeHTMLCell('', '', '', '', $html_parrafo, 0, 1, 0, true, 'J', true);
$pdf->Ln(7);

$html_parrafo_2 = '<ul style="font-size: 11px; line-height: 1.3;">';

$query = "SELECT descripcion FROM destrezas_habilidades WHERE carrera_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $carrera_id); 
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $html_parrafo_2 .= '<li>' . htmlspecialchars($row['descripcion']) . '</li>';
}

$html_parrafo_2 .= '</ul>';

$pdf->writeHTMLCell('', '', '', '', $html_parrafo_2, 0, 1, 0, true, 'J', true);
$pdf->Ln(7);
$html_parrafo_3 = '<p style="font-size: 11px; line-height: 1.3;">Agradecemos de antemano por la predisposición de su Institución, por la atención prestada y por el trámite que se le dé a la presente, en función del mejoramiento de la calidad de la Educación Superior ecuatoriana. Con sentimientos de estima y respeto, me suscribo de usted. <br><br>Atentamente,</p>';
$pdf->writeHTMLCell('', '', '', '', $html_parrafo_3, 0, 1, 0, true, 'J', true);
$pdf->Ln(30);
$pdf->SetFont('times', 'B', 11);
$pdf->Cell(0, 1, '__________________________________________________________', 0, 1, 'L');
$pdf->Cell(0, 1, $nombre_coordinador, 0, 1, 'L');
$pdf->Cell(0, 1, 'Coordinador de Prácticas de la Facultad de Ciencias', 0, 1, 'L');
$pdf->Cell(0, 1, 'Administrativas y Sistemas', 0, 1, 'L');
$pdf->Cell(0, 1, 'correo electrónico: practicasfaces@bolivariano.edu.ec', 0, 1, 'L');

$pdf->Output('3_CARTA_ASIGNACION_ESTUDIANTE.pdf', 'I');