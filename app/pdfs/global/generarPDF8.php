<?php
require_once('../../../../TCPDF-main/tcpdf.php');

$nombres = isset($_POST['nombres']) ? ucwords(strtolower(trim($_POST['nombres']))) : 'NO APLICA';
$cedula = $_POST['cedula'] ?? 'NO APLICA';
$carrera = $_POST['carrera'] ?? 'NO APLICA';
$fecha_inicio_larga = !empty($_POST['fecha_inicio']) ? formato_fecha_larga($_POST['fecha_inicio']) : 'NO APLICA';
$fecha_fin_larga = !empty($_POST['fecha_fin']) ? formato_fecha_larga($_POST['fecha_fin']) : 'NO APLICA';
$horas_practicas = $_POST['hora_practicas'] ?? 'NO APLICA';
$nombre_entidad = $_POST['nombre_entidad'] ?? 'NO APLICA';
$departamento = $_POST['departamento'] ?? 'NO APLICA';
$nombre_tutor_academico = isset($_POST['tutor_entidad']) ? ucwords(strtolower(trim($_POST['tutor_entidad']))) : 'NO APLICA';

$telefono_tutor = $_POST['telefono_tutor'] ?? 'NO APLICA';
$nombre_doc = '8 INFORME DE ACTIVIDADES'; 

// ✅ Recuperar registros múltiples (por semana)
$actividades = [];
$semanas_inicio = $_POST['semana_inicio'] ?? [];
$semanas_fin = $_POST['semana_fin'] ?? [];
$horas = $_POST['horas_realizadas'] ?? [];
$descripciones = $_POST['actividades_realizadas'] ?? [];

for ($i = 0; $i < count($semanas_inicio); $i++) {
    $actividades[] = [
        'semana_inicio' => $semanas_inicio[$i] ?? 'NO APLICA',
        'semana_fin' => $semanas_fin[$i] ?? 'NO APLICA',
        'horas_realizadas' => $horas[$i] ?? 'NO APLICA',
        'actividades_realizadas' => $descripciones[$i] ?? 'NO APLICA'
    ];
}

function formato_fecha_larga($fecha) {
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
}

// Inicializar TCPDF
$pdf = new CustomPDF();
$pdf->AddPage();
$pdf->SetMargins(15, 35, 15);
$pdf->SetY(35);


$pdf->SetFont('times', 'B', 11);
$pdf->Cell(0, 1, 'INFORME DE ACTIVIDADES', 0, 1, 'C');
$pdf->Ln(3);

$pdf->SetFont('times', '', 10);

$html_tabla1 = '
    <table border="0.5" cellpadding="2" cellspacing="0">
        <tr>
            <th colspan="2" style="text-align: center; font-size: 10px;"><strong>DATOS GENERALES DEL ESTUDIANTE</strong></th>
        </tr>
        <tr>
            <td style="width: 70%;"><strong>Apellidos y Nombres:</strong><br>' . $nombres . '</td>
            <td style="width: 30%;"><strong>Cédula de identidad:</strong><br>' . $cedula . '</td>
        </tr>

    </table>
    <table border="0.5" cellpadding="2" cellspacing="0">
        <tr>
            <td><strong>Carrera:</strong><br>' . $carrera . '</td>
        </tr>

    </table>

    <table border="0.5" cellpadding="2.5" cellspacing="0">
        <tr>
            <th colspan="6" style="text-align: center; font-size: 10px;"><strong>PERIODO PRÁCTICA PREPROFESIONAL</strong></th>
        </tr>
        <tr>
            <td style="font-size: 10px; width: 12%;"><strong>Fecha Inicio:</strong></td>
            <td style="font-size: 10px; width: 25%;">' . $fecha_inicio_larga . '</td>
            <td style="font-size: 10px; width: 12%;"><strong>Fecha Fin:</strong></td>
            <td style="font-size: 10px; width: 26%;">' . $fecha_fin_larga . '</td>
            <td style="font-size: 10px; width: 15%;"><strong>Horas Prácticas:</strong></td>
            <td style="font-size: 10px; width: 10%;">' . $horas_practicas . '</td>
        </tr>
    </table>

    <table border="0.5" cellpadding="2" cellspacing="0">

        <tr>
            <th colspan="6" style="text-align: center; font-size: 10px;"><strong>DATOS GENERALES DE ENTIDAD FORMADORA</strong></th>
        </tr>
        <tr>
            <td style="width: 25%;"><strong>Entidad receptora:</strong></td>
            <td style="width: 75%;" colspan="5">' . $nombre_entidad . '</td>
        </tr>
        <tr>
            <td><strong>Departamento /Área y/o Rotación:</strong></td>
            <td style="width: 75%;" colspan="5">' . $departamento . '</td>
        </tr>
        <tr>
            <td><strong>Tutor entidad receptora</strong></td>
            <td colspan="3">' . $nombre_tutor_academico . '</td>
            <td><strong>Teléfono</strong></td>
            <td>' . $telefono_tutor . '</td>
        </tr>
    </table>

    <table border="0.5" cellpadding="4" cellspacing="0" width="100%">
    <!-- Título -->
    <tr style="text-align: center; font-size: 11px; font-weight: bold;">
        <td colspan="3">Registro de actividades</td>
    </tr>

    <!-- Encabezados -->
    <tr style="text-align: center; font-weight: bold;">
        <td style="width: 20%; font-size: 11px;">Semanas/Fecha:</td>
        <td style="width: 15%; font-size: 11px;">Horas realizadas</td>
        <td style="width: 65%; font-size: 11px;">Actividades realizadas</td>
    </tr>
';
$meses_es = [
    1  => 'enero',
    2  => 'febrero',
    3  => 'marzo',
    4  => 'abril',
    5  => 'mayo',
    6  => 'junio',
    7  => 'julio',
    8  => 'agosto',
    9  => 'septiembre',
    10 => 'octubre',
    11 => 'noviembre',
    12 => 'diciembre'
];

foreach ($actividades as $actividad) {
    // Crear objetos DateTime desde los valores que ya tienes
    $inicio = new DateTime($actividad['semana_inicio']);
    $fin = new DateTime($actividad['semana_fin']);

    // Mes de la fecha final para el texto
    $numero_mes_fin = (int) $fin->format('n');
    $mes_nombre = $meses_es[$numero_mes_fin];

    // Formatear el texto de la fecha como "Fecha 03 al 07, Marzo de 2025"
    $formato_fecha = 'Fecha ' . $inicio->format('d') . ' al ' . $fin->format('d') . ', ' . ucfirst($mes_nombre) . ' de ' . $fin->format('Y');

    // Ahora generas la fila de la tabla con el formato bonito
    $html_tabla1 .= '
    <tr >
        <td style="width: 20%;">' . htmlspecialchars($formato_fecha) . '</td>
        <td style="text-align: center; width: 15%;">' . htmlspecialchars($actividad['horas_realizadas']) . ' horas</td>
        <td style="width: 65%;">' . htmlspecialchars($actividad['actividades_realizadas']) . '</td>
    </tr>
    ';
}

$html_tabla1 .= '</table>';


$html_tabla2 = '
    <table width="100%" style="font-size: 11px;">
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
      <strong>Firma y sello del Tutor de la Entidad <br>Receptora</strong>
    </td>
  </tr>
</table>
';

$pdf->writeHTML($html_tabla1, true, false, true, false, '');
$pdf->Ln(10);
$pdf->writeHTML($html_tabla2, true, false, true, false, '');

$pdf->Output($nombre_doc . '.pdf', 'I');
