<?php
//Llamamos a la libreria
require_once 'app/view/pdf/pdf_base_proforma.php';
//creamos el objeto
$pdf=new PDF();
//Añadimos una pagina
$pdf->AddPage();
//Define el marcador de posición usado para insertar el número total de páginas en el documento
$pdf->AliasNbPages();
$pdf->SetFont('Arial','BU',13);
$pdf->Cell(70,6,'DATOS DEL CLIENTE:',0,1,'L',0);
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell(190,6,'NOMBRE                      :'." ".$datos_proforma->cliente_nombre.$datos_proforma->cliente_razonsocial,0,1,'L',0);
if($datos_proforma->cliente_numero == "11111111"){
    $mostrar = "SIN DOCUMENTO";
}else{
    $mostrar = $datos_proforma->cliente_numero;
}
$pdf->Cell(190,6,'DNI / RUC                     :'." ".$mostrar,0,1,'L',0);
$pdf->Cell(190,6,'FECHA GENERADA    :'." ".date('d-m-Y', strtotime($datos_proforma->proforma_fecha_generada)),0,1,'L',0);
$pdf->Cell(190,6,'DIRECCIÓN                 :'." ".$datos_proforma->cliente_direccion,0,1,'L',0);
$pdf->CellFitSpace(190,6,'NOTA                           :'." ".$datos_proforma->proforma_nota,0,1,'L',0);
$pdf->Cell(190,6,'FECHA VIGENCIA      :'." ".date('d-m-Y',strtotime($datos_proforma->proforma_fecha_vigencia)),0,1,'L',0);
$pdf->Ln();
$pdf->Cell(30);
//$pdf->SetFont('Arial','BU',14);
//$pdf->Cell(130,10,'DATOS DE LOS PRODUCTOS',0,1,'C');
$pdf->Ln();
$pdf->Ln();
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',9);
//$pdf->Cell(25,5,'TIPO',1,0,'C',1);
$pdf->Cell(105,5,'DESCRIPCIÓN',1,0,'C',1);
$pdf->Cell(16,5,'CANT.',1,0,'C',1);
$pdf->Cell(22,5,'P. UNIT.',1,0,'C',1);
$pdf->Cell(22,5,'TOTAL',1,1,'C',1);
//$pdf->SetWidths(array(25,105,16,22,22));
$pdf->SetWidths(array(105,16,22,22));
$total_ = 0;
$pdf->SetFont('Arial','',8);
foreach ($listar_pdf as $lp) {
    /*if($lp->proforma_detalle_mm == 1){
        $ver = "PISCO";
    }else{
        $ver = "PISCO";
    }*/
    $total = $lp->proforma_detalle_producto_cantidad * $lp->proforma_detalle_precio;
    //$pdf->Row(array($ver,$lp->proforma_detalle_nombre_producto,$lp->proforma_detalle_producto_cantidad,'S/. '.$lp->proforma_detalle_precio,'S/. '.number_format($total,2)));
    $pdf->Row(array($lp->proforma_detalle_nombre_producto,$lp->proforma_detalle_producto_cantidad,'S/. '.$lp->proforma_detalle_precio,'S/. '.number_format($total,2)));
    $total_ = $total_ + $total;
}
$pdf->Ln();
$pdf->SetFont('Arial','',11);
$pdf->Cell(118,10,'MONTO TOTAL PRESUPUESTADO',0,0,'C',0);
$pdf->Cell(30,10,'S/. '.number_format($total_,2),0,1,'R',0);
$pdf->Ln();
//$pdf->Image('media/fire/firma2.png',50,235,110);
$pdf->Output('','Proforma_'.$fecha);
?>