<?php
session_start();
include 'db.php';

if (!isset($_POST['id'])) die("Error: ID no recibido");

$id = (int)$_POST['id'];
$stmt = $db->prepare("SELECT * FROM estudiantes WHERE id = ?");
$stmt->execute([$id]);
$e = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$e) die("Estudiante no encontrado");

require_once('tcpdf/tcpdf.php');
require_once('tcpdf/fpdi/src/autoload.php');
use setasign\Fpdi\Tcpdf\Fpdi;

$pdf = new Fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(25, 20, 20);
$pdf->SetAutoPageBreak(false, 0);

$plantilla = 'plantilla_certificado.pdf';
if (!file_exists($plantilla)) die("Falta plantilla_certificado.pdf");

$pdf->AddPage();
$tpl = $pdf->setSourceFile($plantilla);
$pdf->useTemplate($pdf->importPage(1), 0, 0, 210);

// Función mes en letras
function mesEnLetras($m) {
    $meses = ['', 'enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    return $meses[$m];
}

// 0. INSTITUCIÓN (grande)
$pdf->SetFont('helvetica', 'B', 26);
$pdf->SetTextColor(0, 51,135);
$pdf->SetXY(25, 35);                                 
$pdf->Cell(170, 15, strtoupper("INSTITUCIÓN EDUCATIVA BANDURA"), 0, 1, 'C');

// 1. INSTITUCIÓN (grande)
$pdf->SetFont('helvetica', 'B', 18);
$pdf->SetTextColor(63, 148,202);
$pdf->SetXY(20, 60);                                 
$pdf->Cell(170, 15, strtoupper("CERTIFICA"), 0, 1, 'C');

// 2. INSTITUCIÓN (grande)
$pdf->SetFont('helvetica', '', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(25, 70);                                 
$pdf->Cell(170, 15, 'Que el(la) niño(a):', 0, 1, 'L');

// 3. NOMBRE COMPLETO (grande)
$pdf->SetFont('helvetica', 'B', 24);
$pdf->SetTextColor(0, 51,135);
$pdf->SetXY(20, 85);                                 // AJUSTA SI ES NECESARIO
$pdf->Cell(170, 15, strtoupper($e['nombres'].' '.$e['apellidos']), 0, 1, 'C');

// 4. PÁRRAFO COMPLETO (el que querías)
$pdf->SetFont('helvetica', '', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(25, 110);                                 // posición inicial del bloque

$texto_parrafo = "Identificado(a) con Tarjeta de Identidad Nº " . $e['documento'] . ", se encuentra matriculado(a) y cursando el grado " . $e['grado'] . " en nuestra institución durante el año lectivo actual.

Se expide el presente certificado a solicitud del interesado(a), en la ciudad de Soacha, Cundinamarca, a los " . date('d') . " días del mes de " . mesEnLetras(date('n')) . " de " . date('Y') . ".";

// MultiCell permite salto de línea automático y respeta el ancho
$pdf->MultiCell(160, 10, $texto_parrafo, 0, 'I', false, 1, 25, '', true, 0, false, true, 0, 'M');

// 5. FIRMA 
$pdf->SetFont('helvetica', '', 14);
$pdf->SetY(195);
$pdf->Cell(0, 8, '_________________________________', 0, 1, 'L');
$pdf->Cell(0, 5, 'Dirección Jardín Infantil Bandura', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 5, 'NIT: 900.596.538-2', 0, 0, 'L');

// 6. Código único abajo
$pdf->SetFont('helvetica', 'I', 9);
$pdf->SetTextColor(100,100,100);
$pdf->SetY(260);
$pdf->Cell(0, 10, 'Código: CERT-'.date('Y').'-'.str_pad($e['id'],4,'0',STR_PAD_LEFT), 0, 0, 'C');

// DESCARGAR
$nombre = "Certificado_".preg_replace('/[^a-zA-Z0-9]/', '_', $e['nombres'].'_'.$e['apellidos']).".pdf";
$pdf->Output($nombre, 'D');
exit();