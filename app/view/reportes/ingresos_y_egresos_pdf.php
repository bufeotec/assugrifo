<?php
//Llamamos a la libreria
require_once 'app/view/pdf/pdf_base.php';
//creamos el objeto
$pdf=new PDF();

//Añadimos una pagina
$pdf->AddPage();
//Define el marcador de posición usado para insertar el número total de páginas en el documento
$pdf->AliasNbPages();
$pdf->SetFont('Arial','BU',14);
//Mover
$pdf->Cell(30);
$pdf->Cell(130,10,'Reporte de Ingresos y Egresos'." ".$nueva_fecha,0,1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(80,6,'Ingresos',0,2,'L',0);
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,6,'Fecha',1,0,'C',1);
$pdf->Cell(30,6,'Documento',1,0,'C',1);
$pdf->Cell(30,6,'Correlativo',1,0,'C',1);
$pdf->Cell(40,6,'Nombre',1,0,'C',1);
$pdf->Cell(30,6,'Total',1,0,'C',1);
$pdf->Ln();
$ingresos_productos = 0;
$pdf->SetFont('Arial','',12);
foreach ($ventas as $p){

    $pdf->Cell(50,6,$p->venta_fecha,1,0,'C',0);
    $pdf->Cell(30,6,$p->venta_tipo,1,0,'C',0);
    $pdf->Cell(30,6,$p->venta_correlativo,1,0,'C',0);
    $pdf->Cell(40,6,$p->cliente_nombre,1,0,'C',0);
    $pdf->Cell(30,6,'S/. '.$p->venta_total,1,1,'C',0);
    $ingresos_productos = $ingresos_productos + $p->venta_total;

}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL INGRESOS',0,0,'C',0);
$pdf->Cell(30,10,'S/. '.number_format($ingresos_productos,2),0,1,'R',0);
$pdf->Ln();
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(80,6,'Egresos',0,1,'L',0);
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,6,'Fecha',1,0,'C',1);
$pdf->Cell(115,6,'Nombre',1,0,'C',1);
$pdf->Cell(30,6,'Importe',1, 1,'C',1);
$egresos = 0;
$pdf->SetFont('Arial','',12);
foreach ($listar_egresos as $e){
    $pdf->CellFitSpace(50,6,date('d-m-Y', strtotime($e->egreso_fecha_registro)),1,0,'C',0);
    $pdf->CellFitSpace(115,6,$e->egreso_descripcion,1,0,'C',0);
    $pdf->CellFitSpace(30,6,$e->egreso_monto,1,1,'C',0);
    $egresos = $egresos + $e->egreso_monto;
}
$balance_final = $ingresos_productos - $egresos;
$pdf->Cell(118,10,'TOTAL EGRESOS',0,0,'C',0);
$pdf->Cell(30,10,'S/. '.number_format($egresos,2),0,0,'R',0);
$pdf->Ln();
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(70,6,'Balance General',0,1,'L',0);
$pdf->Ln();
$pdf->SetFont('Arial','',12);
$pdf->Cell(100,6,'TOTAL INGRESOS EN EFECTIVO',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($ingresos_productos,2),0,1,'C',0);
$pdf->Cell(100,6,'TOTAL EGRESOS',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($egresos,2),0,1,'C',0);
$pdf->Cell(100,6,'SALDO TOTAL DEL DIA',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($balance_final,2),0,1,'C',0);
$pdf->Cell(100,6,'MONTO DE APERTURA DE CAJA',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($listar_monto_inicial->caja_apertura,2) ?? 0,0,1,'C',0);
$total_caja = $balance_final + $listar_monto_inicial->caja_apertura;
$pdf->Cell(100,6,'TOTAL EN CAJA',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($total_caja,2),0,0,'C',1);



$pdf->Ln();
$pdf->Output();
?>