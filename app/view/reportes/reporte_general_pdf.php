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
$pdf->Cell(130,10,'Reporte General desde'." ".$fecha_i." ". 'hasta'." ".$fecha_f,0,1,'C');
$pdf->Ln();
//$caja_total = 0;
//$ingresos_total = 0;
//$egresos_totales = 0;
//$ingresos_tarjeta_total = 0;
//$ingresos_transferencias_total = 0;
//$ingresos_totales_salon = 0;
//$ingresos_totales_delivery = 0;
//$ingresos_generales = 0;
//$movimientos_caja_chica = 0;
//$salida_caja_chica = 0;
//$orden_pedido_total = 0;
//$ingresos_total_efectivo_delivery = 0;
//$ingresos_total_tarjeta_delivery = 0;
//$ingresos_total_transferencia_delivery = 0;
//$ingresos_total_de_ventas = 0;
//$diferencia = 0;
//
//for($i=$fecha_filtro;$i<=$fecha_filtro_fin;$i+=86400){
//
//    $caja = $this->reporte->sumar_caja(date("Y-m-d",$i),$id_caja_numero);
//    $reporte_ingresos_movi = $this->reporte->listar_datos_ingresos_caja(date("Y-m-d",$i),$id_caja_numero);
//    $reporte_ingresos = $this->reporte->listar_datos_ingresos(date("Y-m-d",$i),$id_caja_numero);
//    $reporte_ingresos_tarjeta = $this->reporte->listar_datos_ingresos_tarjeta(date("Y-m-d",$i),$id_caja_numero);
//    $reporte_ingresos_transferencia = $this->reporte->listar_datos_ingresos_transferencia(date("Y-m-d",$i),$id_caja_numero);
//    $reporte_orden_pedido = $this->reporte->listar_monto_op(date("Y-m-d",$i),$id_caja_numero);
//    $reporte_egresos_movi = $this->reporte->listar_datos_egresos(date("Y-m-d",$i),$id_caja_numero);
//    //FUNCIONES PARA LOS INGRESOS DEL DELIVERY
//    $reporte_ingresos_delivery = $this->reporte->listar_datos_ingresos_delivery(date("Y-m-d",$i),$id_caja_numero);
//    $reporte_ingresos_tarjeta_delivery = $this->reporte->listar_datos_ingresos_tarjeta_delivery(date("Y-m-d",$i),$id_caja_numero);
//    $reporte_ingresos_transferencia_delivery = $this->reporte->listar_datos_ingresos_transferencia_delivery(date("Y-m-d",$i),$id_caja_numero);
//
//    //RESULTADO DE LAS FUNCIONES PARA SALON
//    $caja = $caja->total;
//    $reporte_ingresos_movi = $reporte_ingresos_movi->total;
//    $ingresos = $reporte_ingresos->total;
//    $reporte_ingresos_tarjeta = $reporte_ingresos_tarjeta->total;
//    $reporte_ingresos_transferencia = $reporte_ingresos_transferencia->total;
//    $reporte_orden_pedido = $reporte_orden_pedido->total;
//    $reporte_egresos_movi = $reporte_egresos_movi->total;
//    //RESULTADO DE LAS FUNCIONES PARA DELIVERYS
//    $reporte_ingresos_delivery = $reporte_ingresos_delivery->total;
//    $reporte_ingresos_tarjeta_delivery = $reporte_ingresos_tarjeta_delivery->total;
//    $reporte_ingresos_transferencia_delivery = $reporte_ingresos_transferencia_delivery->total;
//
//    //SUMA DE TOTALES EN EL SALON
//    $caja_total = $caja_total + $caja;
//    $movimientos_caja_chica = $movimientos_caja_chica + $reporte_ingresos_movi;
//    $ingresos_total = $ingresos_total + $ingresos;
//    $ingresos_tarjeta_total = $ingresos_tarjeta_total + $reporte_ingresos_tarjeta;
//    $ingresos_transferencias_total = $ingresos_transferencias_total + $reporte_ingresos_transferencia;
//    $orden_pedido_total = $orden_pedido_total + $reporte_orden_pedido;
//    $salida_caja_chica = $salida_caja_chica + $reporte_egresos_movi;
//    //SUMA DE TOTALES DEL DELIVERY
//    $ingresos_total_efectivo_delivery = $ingresos_total_efectivo_delivery + $reporte_ingresos_delivery;
//    $ingresos_total_tarjeta_delivery = $ingresos_total_tarjeta_delivery + $reporte_ingresos_tarjeta_delivery;
//    $ingresos_total_transferencia_delivery = $ingresos_total_transferencia_delivery + $reporte_ingresos_transferencia_delivery;
//}
//$ingresos_total_de_ventas = $ingresos_total_de_ventas + $ingresos_total + $ingresos_tarjeta_total + $ingresos_transferencias_total + $ingresos_total_efectivo_delivery +
//    $ingresos_total_tarjeta_delivery + $ingresos_total_transferencia_delivery;
//$ingresos_generales = $ingresos_generales + $ingresos_total + $ingresos_tarjeta_total + $ingresos_transferencias_total + $ingresos_total_efectivo_delivery +
//                        $ingresos_total_tarjeta_delivery + $ingresos_total_transferencia_delivery + $caja_total + $movimientos_caja_chica - $salida_caja_chica;
//$ingresos_totales_salon = $ingresos_totales_salon + $ingresos_tarjeta_total + $ingresos_total + $ingresos_transferencias_total;
//$ingresos_totales_delivery = $ingresos_totales_delivery + $ingresos_total_efectivo_delivery + $ingresos_total_tarjeta_delivery + $ingresos_total_transferencia_delivery;
//$egresos_totales = $egresos_totales + $salida_caja_chica + $orden_pedido_total;
//
//$diferencia = $diferencia + $caja_total + $movimientos_caja_chica + $ingresos_total + $ingresos_total_efectivo_delivery - $salida_caja_chica - $orden_pedido_total;
//

$total = 0;
$apertura = 1;
$n_ventas_salon = $this->reporte->n_ventas_salon($id_caja,$fecha_ini_caja, $fecha_fin_caja);

$monto_caja_apertura = $this->reporte->reporte_caja_x_caja($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ingreso_caja_chica = $this->reporte->ingreso_caja_chica_x_caja($id_caja, $fecha_ini_caja,$fecha_fin_caja);
$ventas_efectivo_salon = $this->reporte->ventas_efectivo($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ventas_tarjeta_salon = $this->reporte->ventas_tarjeta($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ventas_trans_salon_plin = $this->reporte->ventas_trans_plin($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ventas_trans_salon_yape = $this->reporte->ventas_trans_yape($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ventas_trans_salon_otros = $this->reporte->ventas_trans_otros($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$salida_caja_chica = $this->reporte->salida_caja_chica_x_caja($id_caja, $fecha_ini_caja, $fecha_fin_caja);

$impuestos = $this->reporte->impuestos($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$impuestos_tt = $this->reporte->impuestos_tt($id_caja, $fecha_ini_caja, $fecha_fin_caja);

//FUNCIONES DESGLOSADAS PARA SALON
$monto_caja_apertura = $monto_caja_apertura->total_apertura;
$ingreso_caja_chica = $ingreso_caja_chica->total;
$ventas_efectivo  = $ventas_efectivo_salon->total;
$ventas_tarjeta  = $ventas_tarjeta_salon->total;
$ventas_trans_salon_plin  = $ventas_trans_salon_plin->total;
$ventas_trans_salon_yape  = $ventas_trans_salon_yape->total;
$ventas_trans_salon_otros  = $ventas_trans_salon_otros->total;
$salida_caja_chica = $salida_caja_chica->total;

$impuestos_ = $impuestos->total;
$impuestos_tt_ = $impuestos_tt->total;

$ingresos_total_de_ventas = $ventas_efectivo + $ventas_tarjeta + $ventas_trans_salon_plin + $ventas_trans_salon_yape +
    $ventas_trans_salon_otros;
$ingresos_generales = $ventas_efectivo + $ventas_trans_salon_plin + $ventas_trans_salon_yape+ $ventas_trans_salon_otros+
    $ventas_tarjeta + $impuestos_tt_ + $impuestos_ + $monto_caja_apertura + $ingreso_caja_chica - $salida_caja_chica;
$egresos_totales = $salida_caja_chica;
$diferencia = $monto_caja_apertura + $ingreso_caja_chica + $ventas_efectivo - $salida_caja_chica + $impuestos_;


$pdf->SetFont('Arial','U',14);
$pdf->Cell(70,6,'BALANCE GENERAL',0,1,'L',0);
$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(100,6,'TOTAL DE VENTAS DEL DIA',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ingresos_total_de_ventas ?? 0,0,1,'C',0);
$pdf->Cell(100,6,'INGRESOS - EGRESOS',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ingresos_generales ?? 0,0,1,'C',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(120,6,'--------------------------------------------------------------------------------------',0,1,'C',0);
$pdf->Cell(100,6,'- Apertura de Caja',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$monto_caja_apertura ?? 0,0,1,'C',0);
$pdf->Cell(100,6,'- Ingresos caja chica',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ingreso_caja_chica ?? 0,0,1,'C',0);
$pdf->Cell(120,6,'--------------------------------------------------------------------------------------',0,1,'C',0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(100,6,'VENTAS EN EL LOCAL',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ingresos_total_de_ventas ?? 0,0,1,'C',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(100,6,'- Pagos Efectivo',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_efectivo ?? 0,0,1,'C',0);
$pdf->Cell(100,6,'- Pagos Tarjeta',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_tarjeta ?? 0,0,1,'C',0);
$pdf->Cell(100,6,'- Pagos Transferencia Plin',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_trans_salon_plin ?? 0,0,1,'C',0);
$pdf->Cell(100,6,'- Pagos Transferencia Yape',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_trans_salon_yape ?? 0,0,1,'C',0);
$pdf->Cell(100,6,'- Pagos Transferencia Otros',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$ventas_trans_salon_otros ?? 0,0,1,'C',0);
$pdf->Cell(120,6,'--------------------------------------------------------------------------------------',0,1,'C',0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(100,6,'EGRESOS',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$salida_caja_chica ?? 0,0,1,'C',0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(100,6,'- Salida caja chica',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$salida_caja_chica ?? 0,0,1,'C',0);
$pdf->Cell(120,6,'--------------------------------------------------------------------------------------',0,1,'C',0);
$pdf->Cell(100,6,'- Total de Impuestos Efectivo',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$impuestos_ ?? 0,0,1,'C',0);
$pdf->Cell(100,6,'- Total de Impuestos Tarjeta',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$impuestos_tt_ ?? 0,0,1,'C',0);
$pdf->Cell(120,6,'--------------------------------------------------------------------------------------',0,1,'C',0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(100,6,'TOTAL EFECTIVO',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.$diferencia ?? 0,0,1,'C',0);

$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','BU',12);

$pdf->Cell(140,10,'LISTADO DE EGRESOS',0,2,'C');
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',12);
$pdf->Cell(12);
$pdf->Cell(38,6,'Fecha',1,0,'C',1);
$pdf->Cell(100,6,'Concepto',1,0,'C',1);
$pdf->Cell(35,6,'Total',1,0,'C',1);
$pdf->Ln();
$egresos_servicios = 0;
$pdf->SetFont('Arial','',10);
foreach ($listar_egresos as  $le){
    $pdf->Cell(12);
    $pdf->CellFitSpace(38,6,$le->egreso_fecha_registro,1,0,'C',0);
    $pdf->CellFitSpace(100,6,$le->egreso_descripcion,1,0,'C',0);
    $pdf->CellFitSpace(35,6,'S/. '.$le->egreso_monto,1,1,'C',0);
    $egresos_servicios = $egresos_servicios +$le->egreso_monto;

}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL EGRESOS',0,0,'C',0);
$pdf->Cell(30,10,'S/. '.$egresos_servicios,0,1,'R',0);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','BU',14);
$pdf->Cell(30);
$pdf->Cell(130,10,'CANTIDAD DE VENTAS POR PRODUCTOS',0,1,'C');
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',10);
$pdf->Cell(18);
$pdf->Cell(70,6,'PRODUCTO',1,0,'C',1);
$pdf->Cell(47,6,'RANGO DE FECHAS',1,0,'C',1);
$pdf->Cell(43,6,'CANTIDAD VENDIDA',1,0,'C',1);
$pdf->Ln();
$cantidad_vendida = 0;
$pdf->SetFont('Arial','',9);
$productos = $this->reporte->reporte_productos($fecha_ini_caja,$fecha_fin_caja,$id_caja);
foreach ($productos as $p){
    $pdf->Cell(18);
    $pdf->CellFitSpace(70,6,$p->nombre,1,0,'C',0);
    $pdf->CellFitSpace(47,6,$fecha_i.' / '.$fecha_f,1,0,'C',0);
    $pdf->CellFitSpace(43,6,$p->total,1,1,'C',0);
    $cantidad_vendida = $cantidad_vendida +$p->total;

}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL DE PRODUCTOS VENDIDOS',0,0,'C',0);
$pdf->Cell(30,10,$cantidad_vendida,0,1,'R',0);





$pdf->Ln();
$pdf->Ln();
$pdf->Output();
?>