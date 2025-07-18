<?php
require_once('../../../../TCPDF-main/tcpdf.php');

$nombre_doc ='9 EVALUACIÓN CONDUCTUAL DEL ESTUDIANTE';

// Recibir puntajes de las 15 preguntas del formulario
$opcion_uno_puntaje = $_POST['pregunta1'] ?? null;
$opcion_dos_puntaje = $_POST['pregunta2'] ?? null;
$opcion_tres_puntaje = $_POST['pregunta3'] ?? null;
$opcion_cuatro_puntaje = $_POST['pregunta4'] ?? null;
$opcion_cinco_puntaje = $_POST['pregunta5'] ?? null;
$opcion_seis_puntaje = $_POST['pregunta6'] ?? null;
$opcion_siete_puntaje = $_POST['pregunta7'] ?? null;
$opcion_ocho_puntaje = $_POST['pregunta8'] ?? null;
$opcion_nueve_puntaje = $_POST['pregunta9'] ?? null;
$opcion_diez_puntaje = $_POST['pregunta10'] ?? null;
$opcion_once_puntaje = $_POST['pregunta11'] ?? null;
$opcion_doce_puntaje = $_POST['pregunta12'] ?? null;
$opcion_trece_puntaje = $_POST['pregunta13'] ?? null;
$opcion_catorce_puntaje = $_POST['pregunta14'] ?? null;
$opcion_quince_puntaje = $_POST['pregunta15'] ?? null;

$puntajes = [
    $opcion_uno_puntaje,
    $opcion_dos_puntaje,
    $opcion_tres_puntaje,
    $opcion_cuatro_puntaje,
    $opcion_cinco_puntaje,
    $opcion_seis_puntaje,
    $opcion_siete_puntaje,
    $opcion_ocho_puntaje,
    $opcion_nueve_puntaje,
    $opcion_diez_puntaje,
    $opcion_once_puntaje,
    $opcion_doce_puntaje,
    $opcion_trece_puntaje,
    $opcion_catorce_puntaje,
    $opcion_quince_puntaje
];

// Calcular promedio
$promedio = round(array_sum($puntajes) / count($puntajes), 2);


function generarChecks($puntaje, $valor)
{
    return ((int)$puntaje === (int)$valor) ? '☒' : '☐';
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
$pdf->SetY(32);


$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 1, 'EVALUACIÓN CONDUCTUAL DEL ESTUDIANTE', 0, 1, 'C');
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 1, '(SOLO PARA USO DEL SUPERVISOR ENTIDAD RECEPTORA)', 0, 1, 'C');
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 1, 'Indique con una “X” la evaluación que usted considere adecuada, basada en el desempeño del', 0, 1, 'C');
$pdf->Cell(0, 1, 'estudiante durante la Práctica Pre-profesional laboral, y teniendo en cuenta la siguiente escala:', 0, 1, 'C');
$html_title = '<table><tr><td>
<strong>5-</strong> Siempre.
</td>
<td>
<strong>4-</strong> Casi siempre.
</td>
<td>
<strong>3-</strong> Ocasionalmente.
</td>
<td>
<strong>2-</strong> Casi nunca.
</td>
<td>
<strong>1-</strong> Nunca.
</td></tr></table>';
$pdf->writeHTMLCell(0, 1, '', '', $html_title, 0, 1, false, true, 'C');

$pdf->Ln(3);

$pdf->SetFont('times', '', 11);

$html_tabla = '
<table border="0.5" cellpadding="2" cellspacing="0">
    <tr>
        <th colspan="2" width="70%" style="text-align: center;"><strong>INDICADORES:</strong></th>
        <th width="4%" style="text-align: center;"><strong>5</strong></th>
        <th width="4%" style="text-align: center;"><strong>4</strong></th>
        <th width="4%" style="text-align: center;"><strong>3</strong></th>
        <th width="4%" style="text-align: center;"><strong>2</strong></th>
        <th width="4%" style="text-align: center;"><strong>1</strong></th>
        <th width="10%" style="text-align: center;"><strong>Puntaje</strong></th>
    </tr>
    <tr>
        <td rowspan="4" width="20%"><br><br><br><br><strong>Disciplina</strong></td>
        <td width="50%">Asiste puntualmente a su práctica.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_uno_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_uno_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Se presenta con adecuado porte y respeto en el área laboral asignada.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_dos_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_dos_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Manifiesta una actitud de servicio, cooperación y trabajo en equipo.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_tres_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_tres_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Actúa siguiendo la ética profesional y normas de principios morales.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cuatro_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_cuatro_puntaje . '</strong></td>
    </tr>

    <tr>
        <td rowspan="4"><br><br><br><br><strong>Integración al ambiente laboral</strong></td>
        <td>Cumple con las Normas, Políticas, procedimientos y cultura organizacional.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_cinco_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_cinco_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Establece una comunicación profesional efectiva y asertiva en el área asignada.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_seis_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_seis_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Trabaja en iniciativa y soluciones integrales acorde a su asignación de práctica.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_siete_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_siete_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Demuestra capacidad de adaptación y desenvolvimiento al área asignada.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_ocho_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_ocho_puntaje . '</strong></td>
    </tr>

    <tr>
        <td rowspan="7"><br><br><br><br><br><br><br><strong>Conocimientos y habilidades profesionales</strong></td>
        <td>Aplica adecuadamente los conocimientos teóricos y prácticos del perfil profesional.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_nueve_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_nueve_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Demuestra adecuadamente las destrezas y habilidades acordes al perfil profesional.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_diez_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_diez_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_diez_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_diez_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_diez_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_diez_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Genera soluciones y propuestas halladas en el área de asignación de práctica.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_once_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_once_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_once_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_once_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_once_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_once_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Comunica asertivamente situaciones para la mejora continua del área asignada.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_doce_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_doce_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_doce_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_doce_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_doce_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_doce_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Demuestra capacidad resolutiva a casos reales del área asignada.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_trece_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_trece_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_trece_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_trece_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_trece_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_trece_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Demuestra proactividad en adquirir nuevos conocimientos en el área asignada de prácticas.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_catorce_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_catorce_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_catorce_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_catorce_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_catorce_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_catorce_puntaje . '</strong></td>
    </tr>
    <tr>
        <td>Aporta destreza académica en reuniones de trabajo del área asignada.</td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_quince_puntaje, 5) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_quince_puntaje, 4) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_quince_puntaje, 3) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_quince_puntaje, 2) . '</font></td>
        <td style="text-align: center;"><font face="dejavusans">' . generarChecks($opcion_quince_puntaje, 1) . '</font></td>
        <td style="text-align: center;"><strong>' . $opcion_quince_puntaje . '</strong></td>
    </tr>

    <tr>
        <td colspan="2"></td>
        <td colspan="5" align="center";><strong>Promedio</strong></td>
        <td style="text-align: center;"><strong>' . $promedio . '</strong></td>
    </tr>
</table>';
$pdf->writeHTML($html_tabla, true, false, true, false, '');

$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 1, 'EVALUACIÓN:', 0, 1, 'C');
$pdf->Ln(3);
$pdf->SetFont('times', '', 11);
$es_satisfactoria = $promedio >= 3;

$html_evaluacion = '<table>
<tr>
    <td width="40%" style="text-align: center;"><strong>Satisfactoria</strong> (>= 3 puntos de promedio)</td>
    <td width="10%" style="text-align: center;"><font face="dejavusans">' . ($es_satisfactoria ? '☒' : '☐') . '</font></td>
    <td width="40%" style="text-align: center;"><strong>Insatisfactoria</strong> (< 3 puntos de promedio)</td>
    <td width="10%" style="text-align: center;"><font face="dejavusans">' . (!$es_satisfactoria ? '☒' : '☐') . '</font></td>
</tr>
</table>';

$pdf->writeHTMLCell(0, 1, '', '', $html_evaluacion, 0, 1, false, true, 'C');
$pdf->Ln(7);
$firmas = '  <table width="100%" style="font-size: 11px;">
  <tr>
    <!-- Firma del Estudiante -->
    <td style="text-align: center; width: 45%;">
      <div>____________________________________</div>
      Firma del Estudiante
    </td>

    <td style="width: 10%;"></td>

    <!-- Firma del Tutor de la Entidad Receptora -->
    <td style="text-align: center; width: 45%;">
      <div>____________________________________</div>
      Firma y sello del Tutor Entidad Receptora
    </td>
  </tr>
</table>';
$pdf->writeHTMLCell(0, 1, '', '', $firmas, 0, 1, false, true, 'C');

$pdf->Ln(10);

$pdf->Output('informe-actividades.pdf', 'I');
