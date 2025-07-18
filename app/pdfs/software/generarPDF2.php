<?php
require_once('../../../../TCPDF-main/tcpdf.php');

// Extraer variables del formulario POST
$nombres = isset($_POST['nombres']) ? ucwords(strtolower(trim($_POST['nombres']))) : 'NO APLICA';
$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : 'NO APLICA';
$carrera = $_POST['carrera'] ?? 'NO APLICA';
$paralelo = isset($_POST['paralelo']) ? trim($_POST['paralelo']) : 'NO APLICA';
$periodoAcademico = isset($_POST['periodo_academico']) ? trim($_POST['periodo_academico']) : 'NO APLICA';
$hora_practicas = isset($_POST['hora_practicas']) ? trim($_POST['hora_practicas']) : 'NO APLICA';

$nombre_tutor_academico = isset($_POST['tutor_academico']) ? ucwords(strtolower(trim($_POST['tutor_academico']))) : 'NO APLICA';
// Convertir en array de palabras
$partes_nombre = explode(' ', $nombre_tutor_academico);

// Extraer desde el índice 1 (es decir, sin el título)
$solo_nombre_tutor = count($partes_nombre) > 1 
    ? implode(' ', array_slice($partes_nombre, 1)) 
    : $nombre_tutor_academico;

$cedula_tutor_academico = isset($_POST['cedula_tutor']) ? trim($_POST['cedula_tutor']) : 'NO APLICA';
$correo_tutor_academico = isset($_POST['correo_tutor']) ? trim($_POST['correo_tutor']) : 'NO APLICA';
$telefono_tutor = isset($_POST['telefono_tutor']) ? trim($_POST['telefono_tutor']) : 'NO APLICA';

$calificacion = isset($_POST['nota_eva-s']) ? trim($_POST['nota_eva-s']) : 'NO APLICA';

if ($calificacion !== 'NO APLICA' && is_numeric($calificacion)) {
    $calificacion = ($calificacion == 100.00) ? '100' : $calificacion;
}


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

$eva_s = guardar_imagen_temporal('eva_s', 'eva_' . time() . '.jpg');


$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$hora_inicio = $_POST['hora_inicio'] ?? '';
$hora_fin = $_POST['hora_fin'] ?? '';

$fecha_inicio_larga = formato_fecha_larga($fecha_inicio);
$fecha_inicio_corta = formato_fecha_corta($fecha_inicio);
$fecha_fin_larga = formato_fecha_larga($fecha_fin);
$fecha_fin_corta = formato_fecha_corta($fecha_fin);
$hora_inicio = formato_hora($hora_inicio);
$hora_fin = formato_hora($hora_fin);

function formato_fecha_larga($fecha) {
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$fecha_obj) return 'N/A';
    return $fecha_obj->format('d') . ' de ' . $meses[$fecha_obj->format('n') - 1] . ' del ' . $fecha_obj->format('Y');
}

function formato_fecha_corta($fecha) {
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
    return $fecha_obj ? $fecha_obj->format('d/m/Y') : 'N/A';
}

function formato_hora($hora) {
    $hora_obj = DateTime::createFromFormat('H:i:s', $hora) ?: DateTime::createFromFormat('H:i', $hora);
    return $hora_obj ? $hora_obj->format('H:i') : 'N/A';
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
$pdf->SetY(35);

$pdf->SetFont('times', 'B', 14);
$pdf->Cell(0, 1, 'PLAN DE APRENDIZAJE PRACTICO Y DE ROTACIÓN DEL', 0, 1, 'C');
$pdf->Cell(0, 1, 'ESTUDIANTE EN EL ENTORNO LABORAL', 0, 1, 'C');
$pdf->Cell(0, 1, 'FACULTAD DE CIENCIAS EMPRESARIALES Y SISTEMAS.', 0, 1, 'C');
$pdf->Ln(3);

$pdf->SetFont('times', '', 10);

$html_tabla1 = '
    <table border="0.5" cellpadding="3" cellspacing="0">
        <tr>
            <th colspan="2" style="text-align: center; font-size: 11px;"><strong>DATOS GENERALES DEL ESTUDIANTE</strong></th>
        </tr>
        <tr>
            <td style="font-size: 10px; width: 75%;"><strong>Apellidos y Nombres:</strong></td>
            <td style="font-size: 10px; width: 25%;"><strong>Cédula de identidad:</strong></td>
        </tr>
        <tr>
            <td style="font-size: 10px;">' . $nombres . '</td>
            <td style="font-size: 10px;">' . $cedula . '</td>
        </tr>
    </table>';

$html_tabla2 = '
    <table border="0.5" cellpadding="3" cellspacing="0">
        <tr>
            <td style="font-size: 10px; width: 58%;"><strong>Carrera:</strong></td>
            <td style="font-size: 10px; width: 17%;"><strong>Grupo:</strong></td>
            <td style="font-size: 10px; width: 25%;"><strong>Nivel de Estudio:</strong></td>
        </tr>
        <tr>
            <td style="font-size: 10px;">' . $carrera . '</td>
            <td style="font-size: 10px;">' . $paralelo . '</td>
            <td style="font-size: 10px;">' . $periodoAcademico . '</td>
        </tr>
    </table>';

$html_tabla3 = '
    <table border="0.5" cellpadding="3" cellspacing="0">
        <tr>
            <th colspan="6" style="text-align: start; font-size: 10px;"><strong>Periodo Práctica Preprofesional:</strong></th>
        </tr>
        <tr>
            <td style="font-size: 10px; width: 12%;"><strong>Fecha Inicio:</strong></td>
            <td style="font-size: 10px; width: 25%;">' . $fecha_inicio_larga . '</td>
            <td style="font-size: 10px; width: 12%;"><strong>Fecha Fin:</strong></td>
            <td style="font-size: 10px; width: 26%;">' . $fecha_fin_larga . '</td>
            <td style="font-size: 10px; width: 15%;"><strong>Horas Prácticas:</strong></td>
            <td style="font-size: 10px; width: 10%;">' . $hora_practicas . '</td>
        </tr>
    </table>
    ';

$html_tabla4 = '
    <table border="0.5" cellpadding="3" cellspacing="0">
        <tr>
            <th colspan="3" style="text-align: center; font-size: 11px;"><strong>DATOS GENERALES DE TUTOR ACADÉMICO</strong></th>
        </tr>
        <tr>
            <td style="width: 45%;"><strong>Apellidos y Nombres:</strong></td>
            <td style="width: 25%;"><strong>Cédula de identidad:</strong></td>
            <td style="width: 30%;"><strong>Correo Electrónico:</strong></td>
        </tr>
        <tr>
            <td>'.$solo_nombre_tutor.'</td>
            <td>'.$cedula_tutor_academico.'</td>
            <td>'.$correo_tutor_academico.'</td>
        </tr>
    </table>
    ';

$html_tabla5 = '
    <table border="0.5" cellpadding="3" cellspacing="0">

        <tr>
            <th colspan="6" style="text-align: center; font-size: 11px;"><strong>DATOS GENERALES DE ENTIDAD FORMADORA</strong></th>
        </tr>
        <tr>
            <td style="width: 25%;"><strong>Entidad Formadora:</strong></td>
            <td style="width: 75%;" colspan="5">Instituto Superior Tecnológico Bolivariano de Tecnología.</td>
        </tr>
        <tr>
            <td><strong>Actividad Económica:</strong></td>
            <td style="width: 50%;" colspan="3">Enseñanza técnica y Profesional de nivel inferior al de la
                enseñanza superior</td>
            <td style="width: 10%;"><strong>RUC:</strong></td>
            <td >0992180021001</td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong></td>
            <td colspan="3">Víctor Manuel Rendon 236 y Pedro Carbo</td>
            <td><strong>Teléfono</strong></td>
            <td>(04) 5000175 – 1800 ITB</td>
        </tr>
        <tr>
            <td><strong>Tutor Entidad Formadora:</strong></td>
            <td colspan="3">'.$solo_nombre_tutor.'</td>
            <td><strong>Teléfono</strong></td>
            <td>'. $telefono_tutor .'</td>
        </tr>
    </table>
    ';

$html_tabla6 = '
    <table border="0.5" cellpadding="4" cellspacing="0">

        <tr>
            <th colspan="6" style="text-align: center; font-size: 10px;"><strong>RESULTADOS DE APRENDIZAJE ESPECÍFICO DEL ESTUDIANTE</strong></th>
        </tr>
        <tr>
            <td><strong>INDICADORES</strong></td>
            <td colspan="5"><strong>CRITERIOS</strong></td>
        </tr>
        <tr>
            <td rowspan="4" ><strong><br><br><br>Conocimientos:</strong></td>
            <td colspan="5">Diseñar e implementar algoritmos utilizando las técnicas de programación lineal, estructurada,
                procedimental y funcional
            </td>
        </tr>
        <tr>
            <td colspan="5">Utilizar las estructuras de datos básicas y compuestas, así como estáticas y dinámicas para la entrada y
                salida de datos, en la implementación de algoritmos que les den solución a problemas de requerimientos de
                software
            </td>
        </tr>
        <tr>
            <td colspan="5">Brindar soporte técnico y de mantenimiento a sistemas de hardware de cómputo.</td>
        </tr>
        <tr>
            <td colspan="5">Diseñar e implementar bases de datos mediante el Modelo-Entidad-Relación</td>
        </tr>

        <tr>
            <td rowspan="5" ><strong><br><br><br>Habilidades:</strong></td>
            <td colspan="5">Aplicar las formas normales en el diseño de bases de datos mediante el Modelo-Entidad-Relación</td>
        </tr>
        <tr>
            <td colspan="5">Optimizar el diseño de bases de datos implementadas.</td>
        </tr>
        <tr>
            <td colspan="5">Identificar componentes de hardware de redes LAN.</td>
        </tr>
        <tr>
            <td colspan="5">Optimizar el diseño de redes LAN</td>
        </tr>
        <tr>
            <td colspan="5">Implementar y monitorear servicios de redes LAN</td>
        </tr>
    </table>
    ';

$pdf->writeHTML($html_tabla1, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla2, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla3, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla4, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla5, true, false, true, false, '');
$currentY = $pdf->GetY();
$pdf->SetY($currentY - 3);
$pdf->writeHTML($html_tabla6, true, false, true, false, '');

// ! SEGUNDA PAGINA
$pdf->SetMargins(20, 35, 20);
$pdf->AddPage();
$pdf->SetY(35);

$pdf->SetFont('helvetica', 'B', 10.5);
$pdf->Cell(0, 1, 'RESULTADO DE LA DIAGNÓSTICO INICIAL', 0, 1, 'C');
$pdf->Ln(8);
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 1, 'Guayaquil, '. $fecha_fin_larga, 0, 1, 'R');
$pdf->Ln(15);

$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 1, $nombre_tutor_academico, 0, 1, 'L');
$pdf->Cell(0, 1, 'Facultad de Ciencias Empresariales y Sistema', 0, 1, 'L');
$pdf->Cell(0, 1, 'Instituto Superior Tecnológico Bolivariano de Tecnología.', 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('times', '', 12);
$html_parrafo = '
<p style=" font-size: 12px; line-height: 1.8;">De mi consideración: <br>Por medio de la presente se da informe sobre el resultado de la evaluación inicial realizada en la
    plataforma digital del entorno virtual de aprendizajes (eva-s) para dar inicio a la realización de las
    prácticas laborales por parte del estudiante <strong>' . $nombres . '</strong>, con número de
    cédula <strong>' . $cedula . '</strong>, de la carrera <strong>' . $carrera . '</strong> del
    <strong>' . $paralelo . '</strong>, con la fecha de inicio <strong>' . $fecha_inicio_corta . '</strong>, y finaliza <strong>' . $fecha_fin_corta . '</strong>. Como se detalla a continuación:
</p>';

$pdf->writeHTMLCell(
    '',               
    '',               
    '',               
    '',               
    $html_parrafo,    
    0,                
    1,                
    0,                
    true,             
    'J',              
    true              
);

$pdf->Ln(7);


$pdf->SetFont('helvetica', '', 8.6);

$html_tabla7 = '
    <table border="0.5" cellpadding="6" cellspacing="0">
        <tr>
            <td style="width: 25%; background-color: #8EAADB; color: white; text-align: center;"><strong>Nombres del estudiante </strong></td>
            <td style="width: 13%; background-color: #8EAADB; color: white; text-align: center;"><strong>Cédula</strong></td>
            <td style="width: 11%; background-color: #8EAADB; color: white; text-align: center;"><strong>Estado</strong></td>
            <td style="width: 16%; background-color: #8EAADB; color: white; text-align: center;"><strong>Comenzado el </strong></td>
            <td style="width: 15%; background-color: #8EAADB; color: white; text-align: center;"><strong>Finalizado</strong></td>
            <td style="width: 20%; background-color: #8EAADB; color: white; text-align: center;"><strong>Calificación '.$calificacion.'/100</strong></td>
        </tr>
        <tr>
            <td style="text-align: center;">'.$nombres.'</td>
            <td style="text-align: center;">'.$cedula.'</td>
            <td style="text-align: center;">Finalizado</td>
            <td style="text-align: center;">'.$fecha_inicio_larga. ' ' . $hora_inicio.'</td>
            <td style="text-align: center;">'.$fecha_fin_larga. ' ' . $hora_fin.'</td>
            <td style="text-align: center;">'.$calificacion.'/100</td>
        </tr>
    </table>
';

    $pdf->writeHTMLCell(
        '',               // Ancho del contenido (210mm ancho total - 20mm izq - 20mm der = 170mm)
        '',                // Alto automático
        '',                // Posición X (margen izquierdo)
        '',                // Posición Y (automático, sigue después de lo anterior)
        $html_tabla7,     // Contenido HTML
        0,                 // Sin borde
        1,                 // Salto de línea después de escribir
        0,                 // Sin relleno
        true,              // Reset height
        '',               // Alineación Justificada
        ''               // Auto padding
    );

$pdf->Ln(7);
$pdf->SetFont('times', '', 12);
$html_parrafo_2 = '
<p style=" font-size: 12px; line-height: 1.8;">Particular que informo para los fines pertinentes. Se adjunta documento diagnóstico de evaluación del estudiante.</p>';

$pdf->writeHTMLCell(
    '','', '', '', $html_parrafo_2, 0, 1, 0, true, 'J', '',''
);

$pdf->Ln(60);
$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 1, '_________________________________', 0, 1, 'C');
$pdf->Cell(0, 1, 'FIRMA Y SELLO DEL', 0, 1, 'C');
$pdf->Cell(0, 1, 'COORDINADOR PRÁCTICA', 0, 1, 'C');

$pdf->SetMargins(20, 35, 20);
$pdf->AddPage();
$pdf->SetY(35);

$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 1, 'RESULTADOS EXTRAÍDOS DEL EVA-S DEL', 0, 1, 'C');
$pdf->Cell(0, 1, 'DIAGNÓSTICO INICIAL', 0, 1, 'C');
$pdf->Ln(10);


if ($eva_s && file_exists($eva_s)) {
    $html_eva = '
    <div style="text-align: center; margin-top: 15px;">
        <img src="' . $eva_s . '" width="auto" height="300">
    </div>';
    $pdf->writeHTML($html_eva, true, false, true, false, '');
}

$pdf->Output('2 PLAN DE APRENDIZAJE PRACTICO Y DE ROTACION.pdf', 'I');
@unlink($eva_s);
