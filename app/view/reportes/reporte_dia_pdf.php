<?php
//Llamamos a la libreria
require_once 'app/view/pdf/pdf_base.php';
//creamos el objeto
$pdf=new PDF();

//Añadimos una pagina
$pdf->AddPage();
$pdf->SetFont('Arial','BU',14);
//Mover
$pdf->Cell(30);
$pdf->Cell(130,10,'Reporte desde'." ".$fecha_filtro." ". 'hasta'." ".$fecha_filtro_fin,0,1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(80,6,'Ingresos',0,2,'L',0);
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,6,'Producto',1,0,'C',1);
$pdf->Cell(20,6,'Inv. Inicial',1,0,'C',1);
$pdf->Cell(20,6,'Agregado',1,0,'C',1);
$pdf->CellFitSpace(28,6,'Precio Unitario',1,0,'C',1);
$pdf->CellFitSpace(21,6,'Cant. Vendida',1,0,'C',1);
$pdf->CellFitSpace(20,6,'Cant. Salida',1,0,'C',1);
$pdf->CellFitSpace(30,6,'Stock Restante',1,0,'C',1);
$pdf->Cell(22,6,'TOTAL',1,0,'C',1);
$pdf->Ln();
$ingresos_productos = 0;
$sumasa = 0;
$pdf->SetFont('Arial','',10);
foreach ($productos as $p){
    $fecha_fin = $this->reporte->jalar_fecha_fin($p->id_producto);
    $nueva_fecha_inicio_ = $fecha_fin->fecha;
    $artificio = false;
    if($nueva_fecha_inicio_ > $nueva_fecha_fin_){
        $nueva_fecha_inicio = $fecha_filtro;
        $nueva_fecha_fin = $fecha_filtro_fin;
        $artificio = true;
    }else{
        $nueva_fecha_inicio = $nueva_fecha_inicio_;
        $nueva_fecha_fin = $nueva_fecha_fin_;
    }

    $inventario_inicial = $this->reporte->inicial($fecha_filtro,$fecha_filtro_fin, $p->id_producto);

    //FUNCION PARA CALCULAR ANTES DE LA FECHA DEL PRIMER FILTRO LOS VALORES
    $nueva_consulta = $this->reporte->jalar_nuevo_valor_inicial($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_producto);
    $stock_added_nuevo = $this->reporte->stockadded($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_producto);
    $cantidad_nueva = $this->reporte->products_selled($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_producto);
    $stock_out_nuevo = $this->reporte->stockout($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_producto);
    //operaciones
    if($stock_added_nuevo == NULL){
        $stock_added_nuevo_ = 0;
    }else{
        $stock_added_nuevo_ = $stock_added_nuevo;
    }
    if($stock_out_nuevo == NULL){
        $stock_out_nuevo_ = 0;
    }else{
        $stock_out_nuevo_ = $stock_out_nuevo;
    }
    if($artificio == true){
        $nuevo_valor_inicial = $nueva_consulta;
    }else{
        $nuevo_valor_inicial = $nueva_consulta + $stock_added_nuevo_ - $cantidad_nueva - $stock_out_nuevo_;
    }

    $stock_now = $this->reporte->total_productos_now($p->id_producto);
    $cantidad = $this->reporte->products_selled($fecha_filtro,$fecha_filtro_fin,$p->id_producto);
    $stock_added = $this->reporte->stockadded($fecha_filtro,$fecha_filtro_fin, $p->id_producto);
    $stock_out = $this->reporte->stockout($fecha_filtro,$fecha_filtro_fin, $p->id_producto);
    $total_por_producto = $this->reporte->total_por_producto($fecha_filtro,$fecha_filtro_fin,$p->id_producto);
    $ingresos_productos = $ingresos_productos + $total_por_producto;
    $sumasa = $inventario_inicial + $stock_added + $stock_out + $cantidad;
    if($sumasa > 0) {
        $pdf->CellFitSpace(30, 6, $p->producto_nombre, 1, 0, 'C', 0);
        $pdf->CellFitSpace(20, 6, $nuevo_valor_inicial ?? 0, 1, 0, 'C', 0);
        $pdf->CellFitSpace(20, 6, $stock_added ?? 0, 1, 0, 'C', 0);
        $pdf->CellFitSpace(28, 6, $p->producto_precio_valor, 1, 0, 'C', 0);
        $pdf->CellFitSpace(21, 6, $cantidad ?? 0, 1, 0, 'C', 0);
        $pdf->CellFitSpace(20, 6, $stock_out ?? 0, 1, 0, 'C', 0);
        $pdf->CellFitSpace(30, 6, $stock_now ?? 0, 1, 0, 'C', 0);
        $pdf->CellFitSpace(22, 6, 'S/. ' . $total_por_producto ?? 0, 1, 1, 'C', 0);
        $ingresos_productos = $ingresos_productos + $p->producto_venta_total;
    }
}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL INGRESOS VENTAS',0,0,'C',0);
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
$pdf->SetFont('Arial','',10);
foreach ($egreso as $e){
    $pdf->CellFitSpace(50,6,$e->egreso_fecha_registro,1,0,'C',0);
    $pdf->CellFitSpace(115,6,$e->egreso_descripcion,1,0,'C',0);
    $pdf->CellFitSpace(30,6,'S/. '.$e->egreso_monto,1,1,'C',0);
    $egresos = $egresos +$e->egreso_monto;
}
$pdf->SetFont('Arial','',12);
$pdf->Cell(118,10,'TOTAL EGRESOS',0,0,'C',0);
$pdf->Cell(30,10,'S/. '.number_format($egresos,2),0,0,'R',0);
$balance_final = $ingresos_productos - $egresos;
$pdf->Ln();
$pdf->SetFont('Arial','U',12);
$pdf->Cell(70,6,'Balance General',0,1,'L',0);
$pdf->Ln();
$pdf->SetFont('Arial','',12);
$pdf->Cell(100,6,'TOTAL VENTAS',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($ingresos_productos,2),0,1,'C',0);
$pdf->Cell(100,6,'TOTAL EGRESOS',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($egresos,2),0,1,'C',0);
$pdf->Cell(100,6,'SALDO TOTAL DEL DIA',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($balance_final,2),0,1,'C',0);
$pdf->Cell(100,6,'MONTO DE APERTURA DE CAJA',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($listar_monto_inicial->total_apertura,2) ?? 0,0,1,'C',0);
$pdf->Cell(100,6,'TOTAL EN CAJA',0,0,'L',0);
$pdf->Cell(18,6,'S/. '.number_format($balance_final + $listar_monto_inicial->total_apertura,2),0,0,'C',0);

$pdf->Ln();
$pdf->Output('','Reporte_del_dia_'.$fecha_hoy);

?>