<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 22/02/2022
 * Time: 09:32 a. m.
 */

//LLAMAMOS A LA LIBRERIA que está en la vista de report
//require 'app/view/report/pdf_base.php';
//llamamos a la clase pdf_base.php que esta en la vista sellgas
//require_once 'pdf_base_ticket.php';
//se llama directo a la libreria
require 'app/view/pdf/fpdf/fpdf.php';
//require 'app/view/report/pdf_base.php';
// creamos el objeto
$pdf = new FPDF('P');
//Define el marcador de posición usado para insertar el número total de páginas en el documento
$pdf->AddPage();
//CABECERA DEL ARCHIVO
//Logo
$pdf->Image(_SERVER_._LOGO_GENERAL,40,10, '35', '25', 'png');

$pdf->Ln(5);
$pdf->SetFillColor(220,220,220);
$pdf->SetFont('Helvetica','',9);
//$pdf->Cell(60,4, "$dato_pago->empresa_nombrecomercial",0,1,'C',0);
//$pdf->Cell(60,4,"$dato_venta->empresa_nombre",0,1,'C');
//$pdf->Cell(60,4, "EMPRESA DE SERVICIOS PARA EL ",0,1,'C',0);
//$pdf->Cell(60,4, "DESARROLLO HUMANO",0,1,'C',0);
$pdf->SetFont('Helvetica','',9);
$pdf->Cell(105,0,'','',0);
$pdf->Cell(75,0,'','T',1,'R');
$pdf->Cell(158,8, "RUC $dato_venta->empresa_ruc",0,1,'R',0);
//$pdf->Cell(60,4, "DESARROLLO HUMANO",0,1,'C',0);
$pdf->Cell(105,8,"",0,0,'R',0);
$pdf->Cell(75,8,"$tipo_comprobante",0,1,'C',1);
$pdf->Cell(150,8,"$serie_correlativo",0,1,'R',0);
$pdf->Cell(105,0,'','',0);
$pdf->Cell(75,0,'','T',1,'R');
$pdf->SetFont('Helvetica','B',9);
//$pdf->Cell(60,4,"$dato_pago->empresa_domiciliofiscal",0,1,'C');
$pdf->Cell(100,4,"$dato_venta->empresa_razon_social",0,1,'C');
$pdf->SetFont('Helvetica','',7);
$pdf->Cell(100,4,"$dato_venta->empresa_domiciliofiscal",0,1,'C');
$pdf->Cell(100,4,"(ALT 13 Y 14 CALVO DE ARAUJO)",0,1,'C');
$pdf->Cell(100,4,"LORETO - MAYNAS - IQUITOS",0,1,'C');
$pdf->Ln(3);
$pdf->Cell(180,0,'','T',1,'R');
$pdf->Ln(3);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(16,4, "Fecha:",0,0,'L',false);
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(60,4, "$fecha_hoy",0,0,'L',false);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(16,4, "Pago:",0,0,'L',false);
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(60,4, "$dato_venta->tipo_pago_nombre",0,1,'L',false);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(16,4,"$dnni:",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->Cell(60,4, "$documento",0,0,'L',false);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(16,4,"Cliente:",0,0,'L');
if($dato_venta->id_tipodocumento != 4){
    $pdf->SetFont('Helvetica','',7);
    $pdf->MultiAlignCell(100,4, "$dato_venta->cliente_nombre",0,1,'L',false);
}else{
    $pdf->SetFont('Helvetica','',7);
    $pdf->MultiAlignCell(100,4, "$dato_venta->cliente_razonsocial",0,1,'L',false);
}
$pdf->SetFillColor(180,180,180);
$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(16,4,"Dirección:",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->MultiCell(180,4,"$dato_venta->cliente_direccion",0,1,'');

$pdf->SetFont('Helvetica','B',8);
$pdf->Cell(16,4,"Referencia:",0,0,'L');
$pdf->SetFont('Helvetica','',8);
$pdf->MultiCell(180,4,"$dato_pdf",0,1,'');
$pdf->SetFont('Helvetica','B',8);

$pdf->Cell(26,4,"Forma de pago:",0,0,'L');
$pdf->SetFont('Helvetica','',7);
$pdf->Cell(14,4,"$dato_venta->venta_forma_pago",0,1,'L');

if($dato_venta->venta_tipo == "07"){
    $pdf->SetFont('Helvetica','B',8);
    $pdf->Cell(30,4,"Motivo de Emision:",0,0,'L');
    $pdf->SetFont('Helvetica','',8);
    $pdf->Cell(47,4,"$motivo",0,0,'L');
    $pdf->SetFont('Helvetica','B',8);
    $pdf->Cell(35,4,"Documento Relacionado:",0,0,'L');
    $pdf->SetFont('Helvetica','',8);
    $pdf->Cell(25,4,"$documento_relacionado",0,1,'L');
}
$cu = "";
foreach ($cuotas as $c){
    $numero = str_pad($c->venta_cuota_numero, 3, "00", STR_PAD_LEFT);
    $fecha = date('d/m/Y', strtotime($c->venta_cuota_fecha));
    $cu .= "- Cuota".$numero." : ".$fecha.' - '.$c->venta_cuota_importe.'   ';
}
$pdf->MultiCell(140,4,"$cu",0,1,false);

$pdf->Cell(180,0,'','T',1,'R');
$pdf->Ln(3);
$pdf->SetFont('Helvetica','B',7);
$pdf->Cell(8, 10, 'ITEM', 1,'','C',1);
$pdf->Cell(15, 10, 'CANT',1,0,'C',1);
$pdf->Cell(110, 10, 'DESCRIPCION', 1,'','C',1);
$pdf->Cell(20, 10, 'P.U.',1,0,'C',1);
$pdf->Cell(10, 10, 'IGV',1,0,'C',1);
$pdf->Cell(17, 10, 'P.VENTA',1,1,'C',1);

$pdf->SetWidths(array(8,15,110,20,10,17));
//PRODUCTOS
$aa=1;
$filas_tot = 0;
foreach ($detalle_venta as $f){
    $cant = strlen($f->venta_detalle_nombre_producto);
    $filas = ceil($cant / 65);
    if($filas==0){$filas=1;}
    $filas_tot+=$filas;
    $he = 4 * $filas;
    $pdf->SetFont('Helvetica', '', 8);
//    $pdf->Cell(8, $he, "$aa", 1,'','C');
//    //$pdf->Cell(15, 20, number_format(round("$f->venta_detalle_cantidad ",2), 2, ',', ' '), 1,'','C');
//    $pdf->Cell(10, $he, "$f->venta_detalle_cantidad", 1, 0,'C');
    //$pdf->MultiAlignCell(120,4,"$f->venta_detalle_nombre_producto",1,0,'L');
//    //$pdf->CellFitSpace(100,5,"$f->venta_detalle_nombre_producto",1,0,'L');
//    $pdf->Cell(15, $he, number_format(round("$f->venta_detalle_precio_unitario",2), 2, ',', ' '),1,0,'C');
//    $pdf->Cell(10, $he, "$f->venta_detalle_total_igv", 1, 0, 'C');
//    $pdf->Cell(17, $he, number_format(round("$f->venta_detalle_valor_total",2), 2, ',', ' '),1,1,'C');

    $pdf->Row(array($aa,$f->venta_detalle_cantidad, $f->venta_detalle_nombre_producto,  number_format(round("$f->venta_detalle_precio_unitario",6), 6, ',', ' '),$f->venta_detalle_total_igv,number_format(round("$f->venta_detalle_valor_total",2), 2, ',', ' ')));
    $aa++;
}
$pdf->Ln(7);
$pdf->Cell(70,3,'','T',0,'R');
$pdf->Cell(70,0,'',0,0,'L');
$pdf->Cell(40,3,'','T',1,'R');
$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(70, 3, "$montoLetras", 0,0,'L');

if ($dato_venta->venta_tipo != "20") {
    $pdf->Image("$ruta_qr",'8',$pdf->GetY() + 4, '20', '20', '', '');
}

$pdf->Cell(110, 1, "Descuento: $dato_venta->simbolo $dato_venta->venta_totaldescuento", 0,2,'R');
$pdf->Ln(7);
if($dato_venta->venta_tipo != "20"){
    $pdf->Cell(70,1,'BIENES TRANSFERIDOS EN LA',0,0,'R');
}else{
    $pdf->Cell(70,1,'ESTE NO ES UN COMPROBANTE',0,0,'R');
}
$pdf->Cell(110, 1, "Op.Grat: $dato_venta->simbolo $dato_venta->venta_totalgratuita", 0,1,'R');
$pdf->Ln(3);
if($dato_venta->venta_tipo != "20"){
    $pdf->Cell(70,1,'AMAZONIA PARA SER CONSUMIDOS',0,0,'R');
}else{
    $pdf->Cell(70,1,'VALIDO PARA SUNAT SI REQUIERE UNA',0,0,'R');
}
$pdf->Cell(110, 1, "Op.Exon: $dato_venta->simbolo $dato_venta->venta_totalexonerada", 0,1,'R');
$pdf->Ln(3);
if($dato_venta->venta_tipo != "20"){
    $pdf->Cell(70,1,'EN LA MISMA',0,0,'R');
}else{
    $pdf->Cell(70,1,'BOLETA O FACTURA, SOLICÍTELO',0,0,'R');
}
$pdf->Cell(110, 1, "Op.Inaf: $dato_venta->simbolo $dato_venta->venta_totalinafecta", 0,1,'R');
$pdf->Ln(3);
$pdf->Cell(180, 1, "Op.Grav: $dato_venta->simbolo $dato_venta->venta_totalgravada", 0,1,'R');
$pdf->Ln(3);
$pdf->Cell(180, 1, "IGV: $dato_venta->simbolo $dato_venta->venta_totaligv", 0,1,'R');
$pdf->Ln(3);
$pdf->SetFont('Helvetica', '', 7);
$pdf->Cell(70,0,'',0,0,'L');
$pdf->SetFont('Helvetica', 'B', 9);
$pdf->Cell(110, 1, "TOTAL: $dato_venta->simbolo $dato_venta->venta_total", 0,'1','R');
$pdf->SetFont('Helvetica', '', 7);
$pdf->Ln(3);
$pdf->Cell(60,0,'',0,1,'L');
$pdf->Ln(3);
$pdf->Cell(70,3,'','T',0,'R');
$pdf->Cell(70,0,'',0,0,'L');
$pdf->Cell(40,3,'','T',1,'R');

// PIE DE PAGINA
$pdf->Ln();
if($dato_venta->venta_observaciones!=""){
    $pdf->Cell(180,3,'','T',1,'R');
    $pdf->MultiCell(180,8,"OBSERVACIONES: $dato_venta->venta_observaciones",0,1,'');
    $pdf->Cell(180,3,'','T',1,'R');
}

//if($filas_tot<4) {
//    if($dato_venta->venta_tipo == "07"){
//        $hei = 100 + (5 * $filas_tot);
//    }else{
//        $hei = 99 + (5 * $filas_tot);
//    }
//
//}else{
//    if($dato_venta->venta_tipo == "07"){
//        $hei = 98 + (5 * $filas_tot);
//    }else{
//        $hei = 95 + (5 * $filas_tot);
//    }
//}
$pdf->Ln(2);

$pdf->Ln(3);
//$ruta_guardado = 'media/comprobantes/'."$serie_correlativo-" .date('Ymd').'.pdf';
$pdf->Output("$dato_venta->empresa_ruc-$serie_correlativo", 'I');
?>
