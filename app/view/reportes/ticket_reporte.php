<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 29/04/2021
 * Time: 11:18 p. m.
 */

require 'app/models/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;



$nombre_impresora = "Ticketera2";


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);


/*
	Vamos a imprimir un logotipo
	opcional. Recuerda que esto
	no funcionará en todas las
	impresoras

	Pequeña nota: Es recomendable que la imagen no sea
	transparente (aunque sea png hay que quitar el canal alfa)
	y que tenga una resolución baja. En mi caso
	la imagen que uso es de 250 x 250
*/
/* Initialize */
$printer -> initialize();
# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo
*/
/*try{
    $logo = EscposImage::load("media/logo/logo_ruta_ticket.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){*//*No hacemos nada si hay error}*/
/*
	Ahora vamos a imprimir un encabezado
*/
$printer->setFont(Printer::FONT_B);
$printer->setTextSize(2,2);
$printer->text("REPORTE GENERAL" . "\n");
$printer->setFont(Printer::FONT_A);
$printer->setTextSize(1,1);
//$printer->text("$dato_pago->empresa_nombre" . "\n");
$printer->text("DEL DIA : " . "$nueva_fecha_i AL $nueva_fecha_f\n");//AQUI IRIA LA FECHA
//$printer->text("$empresa->empresa_domiciliofiscal" . "\n");
//$printer->text("CAL. YAVARI NRO. 1360" . "\n");
//$printer->text("LORETO - MAYNAS - PUNCHANA" . "\n");

//$printer->setFont(Printer::FONT_B);
//$printer->setTextSize(2,2);
//$printer->text("$venta_tipo" . "\n");
//$printer->text("$venta->venta_serie-$venta->venta_correlativo" . "\n\n");
/*
 Ahora datos del cliente
*/
//$printer->setFont(Printer::FONT_B);
//$printer->setTextSize(1,1);
//#La fecha también
//$printer->text(date("Y-m-d H:i:s") . "\n");
//$printer->setFont(Printer::FONT_A);
//$printer->setTextSize(1,1);
//$printer->text("------------------------------------------------" . "\n");
//$printer->text("DATOS DEL CLIENTE" . "\n");
////$printer->text("------------------------------------------------" . "\n");
///*Alinear a la izquierda*/
//$printer->setJustification(Printer::JUSTIFY_LEFT);
//$printer->text("RAZÓN SOCIAL: $cliente_nombre" . "\n");
//$printer->text("Nro. Doc    : $cliente->cliente_numero" . "\n");
//$printer->text("FECHA       : " .date('d-m-Y', strtotime($venta->venta_fecha)) . "\n");
//$printer->text("DIRECCIÓN   : $cliente->cliente_direccion" . "\n");
//$printer->text("$venta->mesa_nombre" . "\n");

//$printer->text("PADRES:       $padre1" . "\n" . "           $padre2" . "\n");
# Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("------------------------------------------------" . "\n");
/*
	Ahora vamos a imprimir los
	productos
*/
$total = 0;
$apertura = 1;
//DATOS PARA LOS TOTALES DE VENTAS
$n_ventas_salon = $this->reporte->n_ventas_salon($id_caja,$fecha_ini_caja, $fecha_fin_caja);
//DATOS PARA LOS ADELANTOS AL PERSONAL
//$datos_gastos_p = $this->reporte->datos_gastos_p($id_usuario, $fecha_ini_caja, $fecha_fin_caja);
//$sumar_datos_p = $this->reporte->sumar_datos_p($id_usuario, $fecha_ini_caja, $fecha_fin_caja);
//FUNCIONEAS PARA SACAR LOS DATOS DEL REPORTE GENERAL
$monto_caja_apertura = $this->reporte->reporte_caja_x_caja($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ingreso_caja_chica = $this->reporte->ingreso_caja_chica_x_caja($id_caja,$fecha_ini_caja, $fecha_fin_caja);
$ventas_efectivo_salon = $this->reporte->ventas_efectivo($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ventas_tarjeta_salon = $this->reporte->ventas_tarjeta($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ventas_trans_salon_plin = $this->reporte->ventas_trans_plin($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ventas_trans_salon_yape = $this->reporte->ventas_trans_yape($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$ventas_trans_salon_otros = $this->reporte->ventas_trans_otros($id_caja, $fecha_ini_caja, $fecha_fin_caja);
$salida_caja_chica = $this->reporte->salida_caja_chica_x_caja($id_caja, $fecha_ini_caja, $fecha_fin_caja);


//FUNCIONES DESGLOSADAS PARA SALON
$monto_caja_apertura = $monto_caja_apertura->total_apertura;
$ingreso_caja_chica = $ingreso_caja_chica->total;
$ventas_efectivo  = $ventas_efectivo_salon->total;
$ventas_tarjeta  = $ventas_tarjeta_salon->total;
$ventas_trans_salon_plin  = $ventas_trans_salon_plin->total;
$ventas_trans_salon_yape  = $ventas_trans_salon_yape->total;
$ventas_trans_salon_otros  = $ventas_trans_salon_otros->total;
$salida_caja_chica = $salida_caja_chica->total;


$ingresos_total_de_ventas = $ventas_efectivo + $ventas_tarjeta + $ventas_trans_salon_plin + $ventas_trans_salon_yape +
    $ventas_trans_salon_otros;
$ingresos_generales = $ventas_efectivo + $ventas_trans_salon_plin + $ventas_trans_salon_yape+ $ventas_trans_salon_otros+
    $ventas_tarjeta + $monto_caja_apertura + $ingreso_caja_chica - $salida_caja_chica;
$egresos_totales = $salida_caja_chica;
$diferencia = $monto_caja_apertura + $ingreso_caja_chica + $ventas_efectivo - $salida_caja_chica;


/*Alinear a la izquierda para la cantidad y el nombre*/
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text('TOTAL DE VENTAS DEL DIA' . '            S/ ' . $ingresos_total_de_ventas . "\n");
$printer->text('INGRESOS - EGRESOS' . '                 S/ ' . $ingresos_generales . "\n");
$printer->text("------------------------------------------------\n");
$printer->text('APERTURA DE CAJA' . '                   S/ ' . $monto_caja_apertura . "\n");
$printer->text('INGRESOS DE CAJA CHICA' . '             S/ ' . $ingreso_caja_chica . "\n");
$printer->text("------------------------------------------------\n");
$printer->text('VENTAS EN SALON' . '                     S/ ' . $ingresos_total_de_ventas . "\n");
$printer->text('PAGOS EFECTIVO' . '                      S/ ' . $ventas_efectivo . "\n");
$printer->text('PAGOS TARJETA' . '                       S/ ' . $ventas_tarjeta . "\n");
$printer->text('PAGOS TRANSFERENCIA PLIN' . '            S/ ' . $ventas_trans_salon_plin . "\n");
$printer->text('PAGOS TRANSFERENCIA YAPE' . '            S/ ' . $ventas_trans_salon_yape . "\n");
$printer->text('PAGOS TRANSFERENCIA OTROS' . '           S/ ' . $ventas_trans_salon_otros . "\n");
$printer->text("------------------------------------------------\n");
$printer->text('EGRESOS' . '                             S/ ' . $egresos_totales . "\n");
$printer->text('SALIDAS CAJA CHICA' . '                  S/ ' . $salida_caja_chica . "\n");
$printer->text("------------------------------------------------\n");
$printer->text('N° VENTAS' . '                           ' . $n_ventas_salon->total . "\n");
$printer->text("------------------------------------------------\n");
$printer->text('TOTAL EFECTIVO' . '                      S/ ' . $diferencia . "\n");

/*Y a la derecha para el importe*/
//$printer->setJustification(Printer::JUSTIFY_CENTER);
//$printer->text($dp->venta_detalle_cantidad . "   x   " .$dp->venta_detalle_valor_unitario.'  S/ ' . $dp->venta_detalle_valor_total . "\n");

/*
	Terminamos de imprimir
	los productos, ahora va el total
*/
$printer->text("------------------------------------------------\n");
/*
	AHORA VAMOS A LISTAR LOS EGRESOS DETALLADOS
*/
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->setFont(Printer::FONT_B);
$printer->setTextSize(2,2);
$printer->text("DETALLES DE EGRESOS" . "\n\n");
$total_e = 0;
$printer->setFont(Printer::FONT_A);
$printer->setTextSize(1,1);
$listar_egresos = $this->reporte->listar_egresos_reporte($id_caja,$fecha_ini_caja, $fecha_fin_caja);
foreach ($listar_egresos as $dp) {

    /*Alinear a la izquierda para la cantidad y el nombre*/
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text($dp->egreso_descripcion . "\n");

    /*Y a la derecha para el importe*/
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text('S/ ' . $dp->egreso_monto . "\n");
    $total_e = $total_e + $dp->egreso_monto;

}
$printer->text("------------------------------------------------\n");
$printer->text("               TOTAL: S/ ". $total_e ."\n");


/*Alimentamos el papel 3 veces*/
$printer->feed(2);

/*
	Cortamos el papel. Si nuestra impresora
	no tiene soporte para ello, no generará
	ningún error
*/
$printer->cut();

/*
	Por medio de la impresora mandamos un pulso.
	Esto es útil cuando la tenemos conectada
	por ejemplo a un cajón
*/
$printer->pulse();

/*
	Para imprimir realmente, tenemos que "cerrar"
	la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
*/
$printer->close();

?>

