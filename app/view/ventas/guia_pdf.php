<?php
require 'app/view/pdf/fpdf/fpdf.php';
//creamos el objeto
$pdf=new FPDF('P');
//Añadimos una pagina
$pdf->AddPage();
//Define el marcador de posición usado para insertar el número total de páginas en el documento
$pdf->AliasNbPages();

$pdf->Image(_SERVER_.'media/logo/logo_cralm.png',1,1, '60', '45', 'png');
$pdf->SetFont('Helvetica','',7);
$pdf->Cell(120,0,'','',0);
$pdf->Cell(75,0,'','T',1,'R');
$pdf->SetFont('Helvetica','B',14);
$pdf->Cell(100,12, "CRALM GROUP E.I.R.L.",0,0,'R',0);
$pdf->Cell(20);
$pdf->SetFont('Helvetica','B',10);
$pdf->SetFillColor(220,220,220);
$pdf->Cell(75,12, "GUIA DE REMISIÓN",1,1,'C',1);
//$pdf->Cell(60,4, "DESARROLLO HUMANO",0,1,'C',0);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(50);
$pdf->MultiAlignCell(50,6, "                  RUC : 20609569752                CAL.ESTADO DE ISRAEL NRO. 256 URB. VIRGEN DE LORETO",0,0,'C',0);
$pdf->Cell(20);
$pdf->SetFont('Helvetica','B',10);
$pdf->Cell(75,12,"N° $sale->guia_serie - $sale->guia_correlativo",1,1,'C',0);
$pdf->Ln(5);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(37,6,"DESTINATARIO : ",0,0,'L');
$pdf->Cell(20);
$pdf->Cell(25,6,"RUC / DNI : ",0,0,'L');
$pdf->Cell(20);
$pdf->Cell(20,6,"EMAIL : ",0,0,'L');
$pdf->Cell(10);
$pdf->SetFont('Helvetica','',8);
$pdf->Image(_SERVER_.'media/iconos/check.jpg',150,38, '8', '8', 'jpg');
$pdf->MultiAlignCell(60,6,"Venta Realizada Correctamente",0,1,'R');
$pdf->SetFont('Helvetica','',6);
$pdf->MultiAlignCell(55, 4, "$sale->guia_destinatario_nombre", 0, 0, 'L');
$pdf->Cell(2);
$pdf->MultiAlignCell(20, 4, "$sale->guia_destinatario_numero", 0, 0, 'L');
$pdf->Cell(6);
$pdf->MultiAlignCell(60, 4, "$sale->cliente_correo", 0, 1, 'L');
$pdf->Cell(180,6,"",0,$pdf->GetY(),'L');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(37,6,"FECHA VENTA : ",0,0,'L');
$pdf->Cell(20);
$pdf->Cell(45,6,"SERIE Y CORRELATIVO : ",0,0,'L');
$pdf->Cell(20);
$pdf->Cell(20,6,"TELEFONO : ",0,1,'L');

$pdf->SetFont('Helvetica','',9);
$pdf->Cell(35,6,date('Y-m-d',strtotime( $sale->venta_fecha)),0,0,'L');
$pdf->Cell(15);
$pdf->Cell(45,6,$sale->venta_serie.'-'.$sale->venta_correlativo,0,0,'C');
$pdf->Cell(20);
$pdf->Cell(20,6,$sale->cliente_telefono,0,1,'L');
$pdf->Ln(1);
/*DEstino de entrega*/
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(37,6,"DESTINO : ",0,0,'L');
$pdf->Cell(20);
$pdf->Cell(37,6,"DIRECCIÓN : ",0,1,'L');

$pdf->SetFont('Helvetica','',7);
$pdf->MultiAlignCell(50, 4, $sale->ubigeo_departamento.'-'.$sale->ubigeo_provincia.'-'.$sale->ubigeo_distrito, 0, 0, 'L');
$pdf->Cell(7);
$pdf->MultiAlignCell(130, 4, $sale->guia_direccion_lle, 0, 1, 'L');
$pdf->Ln(4);
$pdf->Cell(185,4,'','T',1,'C');


$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(20,6,"CONDUCTOR : ",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->CellFitSpace(50,6,$sale->guia_nombre_cond,0,0,'L');
$pdf->Cell(2);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(20,6,"PLACA   : ",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(4);
$pdf->Cell(50,6,$sale->guia_placa,0,1,'L');
$pdf->Ln(2);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(20,6,"RUC / DNI       : ",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->CellFitSpace(50,6,$sale->guia_doc_cond,0,0,'L');
$pdf->Cell(2);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(10,6,"ORIGEN : ",0,0,'L');
$pdf->Cell(4);
$pdf->SetFont('Helvetica','',8);
$pdf->CellFitSpace(100,6,'CAL.ESTADO DE ISRAEL NRO. 256 URB. VIRGEN DE LORETO (ALT 13 Y 14 CALVO DE ARAUJO)',0,1,'L');
$pdf->Ln(2);
$pdf->Cell(185,4,'','T',1,'C');
$pdf->Ln(2);
$pdf->SetFillColor(220,220,220);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(10, 8, 'CANT.', 1,0,'C',1);
$pdf->Cell(15, 8, 'U.MED', 1,0,'C',1);
$pdf->Cell(140, 8, 'DESCRIPCIÓN',1,0,'C',1);
$pdf->Cell(25, 8, 'PESO TOTAL',1,1,'C',1);
$pdf->SetWidths(array(10,15,140,25));
$pdf->SetFont('Helvetica','',8);
$total=0;
foreach ($productssale as $p){
    $total = $p->guia_remision_detalle_cantidad * $p->guia_remision_detalle_precio;
    $pdf->Row(array($p->guia_remision_detalle_cantidad,'UNIDAD','COD: '. $p->guia_remision_detalle_cod.'| '. $p->guia_remision_detalle_descripcion,''));
}

$pdf->Cell(185,4,'','T',1,'C');
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(30, 8, 'MOTIVO', 0,1,'L',0);
$pdf->Cell(185,4,'','T',1,'C');

$pdf->Circle(12 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='01')?'FD':'');
$pdf->Cell(4);
$pdf->Cell(30, 10, 'Venta', 0,0,'L',0);
$pdf->Cell(20);
$pdf->Circle($pdf->GetX() + 3 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='14')?'F':'');
$pdf->Cell(5);
$pdf->Cell(50, 10, 'Venta Sujeta a confirmación', 0,0,'L',0);
$pdf->Cell(15);
$pdf->Circle($pdf->GetX() + 3 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='07')?'F':'');
$pdf->Cell(5);
$pdf->Cell(70, 10, 'Recojo de Bienes Transformados', 0,0,'L',0);
$pdf->Cell(10);
$pdf->Circle($pdf->GetX() + 3 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='17')?'F':'');
$pdf->Cell(5);
$pdf->Cell(30, 10, 'Traslado para transformación', 0,1,'L',0);

$pdf->Circle(12 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='02')?'F':'');
$pdf->Cell(4);
$pdf->Cell(30, 10, 'Compra', 0,0,'L',0);
$pdf->Cell(20);
$pdf->Circle($pdf->GetX() + 3 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='04')?'F':'');
$pdf->Cell(5);
$pdf->Cell(50, 10, 'Traslado entre la misma empresa', 0,0,'L',0);
$pdf->Cell(15);
$pdf->Circle($pdf->GetX() + 3 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='08')?'F':'');
$pdf->Cell(5);
$pdf->Cell(70, 10, 'Importación', 0,0,'L',0);
$pdf->Cell(10);
$pdf->Circle($pdf->GetX() + 3 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='18')?'F':'');
$pdf->Cell(5);
$pdf->Cell(30, 10, 'Emisor Itinerante', 0,1,'L',0);

$pdf->Circle(12 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='03')?'F':'');
$pdf->Cell(4);
$pdf->Cell(40, 10, 'Venta con entrega a terceros', 0,0,'L',0);
$pdf->Cell(10);
$pdf->Circle($pdf->GetX() + 3 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='06')?'F':'');
$pdf->Cell(5);
$pdf->Cell(50, 10, 'Devolución', 0,0,'L',0);
$pdf->Cell(15);
$pdf->Circle($pdf->GetX() + 3 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='09')?'F':'');
$pdf->Cell(5);
$pdf->Cell(70, 10, 'Exportación', 0,1,'L',0);

$pdf->Circle(12 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='05')?'F':'');
$pdf->Cell(4);
$pdf->Cell(30, 10, 'Consignación', 0,0,'L',0);
$pdf->Cell(20);
$pdf->Circle($pdf->GetX() + 3 ,$pdf->GetY() + 4.5,2,($sale->guia_motivo=='13')?'F':'');
$pdf->Cell(5);
$pdf->Cell(50, 10, 'Otros', 0,1,'L',0);
$pdf->Ln(17);
$pdf->Cell(15);
$pdf->Cell(50, 10, '______________________________', 0,0,'C',0);
$pdf->Cell(60);
$pdf->Cell(50, 10, '______________________________', 0,1,'C',0);
$pdf->Cell(15);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(50, 6, 'RECIBI CONFORME', 0,0,'C',0);
$pdf->Cell(60);
$pdf->Cell(50, 6, 'ENTREGUE CONFORME', 0,1,'C',0);


$pdf->Output();
?>