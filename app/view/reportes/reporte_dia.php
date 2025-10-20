



<div class="modal fade" id="ver_general" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 70% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalles</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row">
                        <div id="nombre"></div>
                        <div id="detalle" class="table-responsive">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="container-fluid">
        <hr><h2 class="concss">
            <a href="<?= _SERVER_?>"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="<?= _SERVER_?>Reporte"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> REPORTE GENERAL
        </h2><hr>
    </section>
    <section class="content" style="background-color: white;box-shadow: 2px 2px 2px #888888;border-radius: 5px; padding: 10px; margin: 10px; min-height: 500px">
        <!-- /.row -->
        <!-- Main row -->
        <form method="post" action="<?= _SERVER_ ?>Reporte/reporte_dia">
            <input type="hidden" id="enviar_fecha" name="enviar_fecha" value="1">
            <div class="row">
                <div class="col-lg-2">
                    <label for="turno">Fecha Inicio:</label>
                    <input type="date" class="form-control" id="fecha_filtro" name="fecha_filtro" step="1" value="<?php echo $fecha_filtro;?>">
                </div>
                <div class="col-lg-2">
                    <label for="turno">Fecha Fin:</label>
                    <input type="date" class="form-control" id="fecha_filtro_fin" name="fecha_filtro_fin" value="<?php echo $fecha_filtro_fin;?>">
                </div>
                <div class="col-lg-3">
                    <button style="margin-top: 30px;" class="btn btn-success" ><i class="fa fa-search"></i> Buscar Registro</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-lg-12">
                <h4 style="text-align: center;">REPORTE GENERAL <i class="fa fa-calendar"></i> Desde: <?php echo date('d-m-Y',strtotime($fecha_filtro));?> Hasta: <?php echo date('d-m-Y',strtotime($fecha_filtro_fin));?></h4>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <h4 style="text-align: center; color: blue">REPORTE <?= _EMPRESA_;?></h4>
                <table class="table table-bordered table-hover" style="border-color: black">
                    <thead>
                    <tr style="background-color: #ebebeb">
                        <th>Producto</th>
                        <!--<th>Inventario Inicial</th>-->
                        <th>Agregado</th>
                        <th>Precio Unitario</th>
                        <th>Descuento</th>
                        <th>Cantidad Vendida</th>
                        <th>Cantidad Salida</th>
                       <!-- <th>Stock Final del Dia</th>-->
                        <th>Total Ganancias</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    //Calculo de Todo lo que es productos
                    $ingresos_productos_t = 0;
                    $sumasa = 0;
                    $nuevo_valor_inicial = 0;
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
                        $inventario_inicial = $this->reporte->inicial($fecha_filtro,$fecha_filtro_fin, $p->id_talla);
                        //FUNCION PARA CALCULAR ANTES DE LA FECHA DEL PRIMER FILTRO LOS VALORES
                        $nueva_consulta = $this->reporte->jalar_nuevo_valor_inicial($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        $stock_added_nuevo = $this->reporte->stockadded($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        $cantidad_nueva = $this->reporte->products_selled($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        $stock_out_nuevo = $this->reporte->stockout($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        //operaciones
                        if($nueva_consulta == NULL){
                            $nueva_consulta_ = 0;
                        }else{
                            $nueva_consulta_ = $nueva_consulta;
                        }
                        if($stock_added_nuevo == NULL){
                            $stock_added_nuevo_ = 0;
                        }else{
                            $stock_added_nuevo_ = $stock_added_nuevo;
                        }
                        if($cantidad_nueva == NULL){
                            $cantidad_nueva = 0;
                        }else{
                            $cantidad_nueva_ = $cantidad_nueva;
                        }
                        if($stock_out_nuevo == NULL){
                            $stock_out_nuevo_ = 0;
                        }else{
                            $stock_out_nuevo_ = $stock_out_nuevo;
                        }
                        if($artificio == true){
                            $nuevo_valor_inicial = $nueva_consulta;
                        }else{
                            $nuevo_valor_inicial = $nueva_consulta_ + $stock_added_nuevo_ - $cantidad_nueva_ - $stock_out_nuevo_;
                        }

                        $stock_added = $this->reporte->stockadded_t($fecha_filtro,$fecha_filtro_fin, $p->id_talla);
                        $stock_out = $this->reporte->stockout_t($fecha_filtro,$fecha_filtro_fin, $p->id_talla);
                        $cantidad = $this->reporte->products_selled_t($fecha_filtro,$fecha_filtro_fin,$p->id_talla);

                        if($fecha_filtro_fin == date('Y-m-d')){
                            $stock_now = $this->reporte->total_productos_now($p->id_talla);
                        }else{
                            $stock_now = $nuevo_valor_inicial +$stock_added - $cantidad - $stock_out;
                        }

                        //$total_por_producto = $this->reporte->total_por_producto($fecha_filtro,$fecha_filtro_fin,$p->id_producto);
                        $total_por_producto = $this->reporte->total_por_producto_toda_fila_t($fecha_filtro,$fecha_filtro_fin,$p->id_talla);
                        $monto_total_producto = 0;
                        $nuevo_descuento = 0;
                        $porcentaje = 0;
                        $descuento_total_p = 0;
                        $restar = 0;
                        foreach ($total_por_producto as $tp){
                            $total_temporal = $tp->venta_detalle_importe_total;

                            if($tp->venta_descuento_global > 0){
                                $porcentaje = $total_temporal * $tp->venta_descuento_global;
                                $total_temporal = $total_temporal - $porcentaje;

                                $descuento_total_p = $descuento_total_p + $porcentaje;
                            }
                            if($tp->venta_detalle_descuento > 0){
                                $restar = $restar + $tp->venta_detalle_descuento;

                            }
                            $monto_total_producto = $monto_total_producto + $total_temporal;
                            $nuevo_descuento = $descuento_total_p + $restar;
                        }

                        $ingresos_productos_t = $ingresos_productos_t + $monto_total_producto;
                        $sumasa = $stock_added + $stock_out + $cantidad;

                        //$jalar_descuento_x_prod = $this->repporte->jalar_descuento_x_prod($p->id_producto);

                        if($sumasa > 0){
                            ?>
                            <tr>
                                <td><?php echo $p->producto_nombre;?> / <?= $p->talla_nombre?></td>
                                <!--<td><?php echo $nuevo_valor_inicial ?? 0;?></td>-->
                                <td>
                                    <a style="color: blue; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="salidas_stock(<?= $p->id_producto;?>,'<?= $fecha_filtro?>','<?= $fecha_filtro_fin?>',1)">
                                        <?php echo $stock_added ?? 0;?>
                                    </a>
                                </td>
                                <td><?php echo $p->producto_precio_valor;?></td>
                                <td><?php echo $nuevo_descuento?></td>
                                <td>
                                    <a style="color: red; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="salidas_stock(<?= $p->id_producto;?>,'<?= $fecha_filtro?>','<?= $fecha_filtro_fin?>',2)">
                                        <?php echo $cantidad ?? 0;?>
                                    </a>
                                </td>
                                <td>
                                    <a style="color: blue; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="salidas_stock(<?= $p->id_producto;?>,'<?= $fecha_filtro?>','<?= $fecha_filtro_fin?>',3)">
                                        <?php echo $stock_out ?? 0;?>
                                    </a>

                                </td>
                                <!--<td><?php echo $stock_now ?? 0;?></td>-->
                                <td>S/. <?php echo $monto_total_producto ?? 0;?></td>
                            </tr>
                                <?php
                        }
                        ?>
                        <?php
                    }
                    //Fin de Calculo Todo Lo Que Es Productos
                    ?>
                    <tr><td colspan="6" style="text-align: right">Total Ingresos Ventas Productos:</td><td style="background-color: #f9f17f"><b> S/. <?php echo number_format($ingresos_productos_t,2) ?? 0;?></b></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <h4 style="text-align: center;color: blue">REPORTE .<?= _EMPRESA_;?></h4>
                <table class="table table-bordered table-hover" style="border-color: black">
                    <thead>
                    <tr style="background-color: #ebebeb">
                        <th>Producto</th>
                        <!--<th>Inventario Inicial</th>-->
                        <th>Agregado</th>
                        <th>Precio Unitario</th>
                        <th>Descuento</th>
                        <th>Cantidad Vendida</th>
                        <th>Cantidad Salida</th>
                        <!-- <th>Stock Final del Dia</th>-->
                        <th>Total Ganancias</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    //Calculo de Todo lo que es productos
                    $ingresos_productos = 0;
                    $sumasa = 0;
                    $nuevo_valor_inicial = 0;
                    foreach ($productos as $p){
                        $fecha_fin = $this->reporte->jalar_fecha_fin($p->id_talla);
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
                        $inventario_inicial = $this->reporte->inicial($fecha_filtro,$fecha_filtro_fin, $p->id_talla);
                        //FUNCION PARA CALCULAR ANTES DE LA FECHA DEL PRIMER FILTRO LOS VALORES
                        $nueva_consulta = $this->reporte->jalar_nuevo_valor_inicial($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        $stock_added_nuevo = $this->reporte->stockadded($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        $cantidad_nueva = $this->reporte->products_selled($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        $stock_out_nuevo = $this->reporte->stockout($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        //operaciones
                        if($nueva_consulta == NULL){
                            $nueva_consulta_ = 0;
                        }else{
                            $nueva_consulta_ = $nueva_consulta;
                        }
                        if($stock_added_nuevo == NULL){
                            $stock_added_nuevo_ = 0;
                        }else{
                            $stock_added_nuevo_ = $stock_added_nuevo;
                        }
                        if($cantidad_nueva == NULL){
                            $cantidad_nueva = 0;
                        }else{
                            $cantidad_nueva_ = $cantidad_nueva;
                        }
                        if($stock_out_nuevo == NULL){
                            $stock_out_nuevo_ = 0;
                        }else{
                            $stock_out_nuevo_ = $stock_out_nuevo;
                        }
                        if($artificio == true){
                            $nuevo_valor_inicial = $nueva_consulta;
                        }else{
                            $nuevo_valor_inicial = $nueva_consulta_ + $stock_added_nuevo_ - $cantidad_nueva_ - $stock_out_nuevo_;
                        }

                        $stock_added = $this->reporte->stockadded($fecha_filtro,$fecha_filtro_fin, $p->id_talla);
                        $stock_out = $this->reporte->stockout($fecha_filtro,$fecha_filtro_fin, $p->id_talla);
                        $cantidad = $this->reporte->products_selled($fecha_filtro,$fecha_filtro_fin,$p->id_talla);

                        if($fecha_filtro_fin == date('Y-m-d')){
                            $stock_now = $this->reporte->total_productos_now($p->id_talla);
                        }else{
                            $stock_now = $nuevo_valor_inicial +$stock_added - $cantidad - $stock_out;
                        }

                        //$total_por_producto = $this->reporte->total_por_producto($fecha_filtro,$fecha_filtro_fin,$p->id_producto);
                        $total_por_producto = $this->reporte->total_por_producto_toda_fila($fecha_filtro,$fecha_filtro_fin,$p->id_talla);
                        $monto_total_producto = 0;
                        $nuevo_descuento = 0;
                        $porcentaje = 0;
                        $descuento_total_p = 0;
                        $restar = 0;
                        foreach ($total_por_producto as $tp){
                            $total_temporal = $tp->venta_detalle_importe_total;

                            if($tp->venta_descuento_global > 0){
                                $porcentaje = $total_temporal * $tp->venta_descuento_global;
                                $total_temporal = $total_temporal - $porcentaje;

                                $descuento_total_p = $descuento_total_p + $porcentaje;
                            }
                            if($tp->venta_detalle_descuento > 0){
                                $restar = $restar + $tp->venta_detalle_descuento;

                            }
                            $monto_total_producto = $monto_total_producto + $total_temporal;
                            $nuevo_descuento = $descuento_total_p + $restar;
                        }

                        $ingresos_productos = $ingresos_productos + $monto_total_producto;
                        $sumasa = $stock_added_nuevo + $stock_out + $cantidad;

                        //$jalar_descuento_x_prod = $this->repporte->jalar_descuento_x_prod($p->id_producto);

                        if($sumasa > 0){
                            ?>
                            <tr>
                                <td><?php echo $p->producto_nombre;?> / <?= $p->talla_nombre?></td>
                                <!--<td><?php echo $nuevo_valor_inicial ?? 0;?></td>-->
                                <td>
                                    <a style="color: blue; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="salidas_stock(<?= $p->id_producto;?>,'<?= $fecha_filtro?>','<?= $fecha_filtro_fin?>',1)">
                                        <?php echo $stock_added?? 0;?>
                                    </a>
                                </td>
                                <td><?php echo $p->producto_precio_valor;?></td>
                                <td><?php echo $nuevo_descuento?></td>
                                <td>
                                    <a style="color: red; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="salidas_stock(<?= $p->id_producto;?>,'<?= $fecha_filtro?>','<?= $fecha_filtro_fin?>',2)">
                                        <?php echo $cantidad ?? 0;?>
                                    </a>
                                </td>
                                <td>
                                    <a style="color: blue; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="salidas_stock(<?= $p->id_producto;?>,'<?= $fecha_filtro?>','<?= $fecha_filtro_fin?>',3)">
                                        <?php echo $stock_out ?? 0;?>
                                    </a>

                                </td>
                                <!--<td><?php echo $stock_now ?? 0;?></td>-->
                                <td>S/. <?php echo $monto_total_producto ?? 0;?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    //Fin de Calculo Todo Lo Que Es Productos
                    ?>
                    <tr><td colspan="6" style="text-align: right">Total Ingresos Ventas Productos:</td><td style="background-color: #f9f17f"><b> S/. <?php echo number_format($ingresos_productos,2) ?? 0;?></b></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <h4 style="text-align: center;color: blue">REPORTE OTROS</h4>
                <table class="table table-bordered table-hover" style="border-color: black">
                    <thead>
                    <tr style="background-color: #ebebeb">
                        <th>Producto</th>
                        <!--<th>Inventario Inicial</th>-->
                        <th>Agregado</th>
                        <th>Precio Unitario</th>
                        <th>Descuento</th>
                        <th>Cantidad Vendida</th>
                        <th>Cantidad Salida</th>
                        <!-- <th>Stock Final del Dia</th>-->
                        <th>Total Ganancias</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    //Calculo de Todo lo que es productos
                    $ingresos_productos = 0;
                    $sumasa = 0;
                    $nuevo_valor_inicial = 0;
                    foreach ($productos as $p){
                        $fecha_fin = $this->reporte->jalar_fecha_fin($p->id_talla);
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
                        $inventario_inicial = $this->reporte->inicial($fecha_filtro,$fecha_filtro_fin, $p->id_talla);
                        //FUNCION PARA CALCULAR ANTES DE LA FECHA DEL PRIMER FILTRO LOS VALORES
                        $nueva_consulta = $this->reporte->jalar_nuevo_valor_inicial($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        $stock_added_nuevo = $this->reporte->stockadded($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        $cantidad_nueva = $this->reporte->products_selled($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        $stock_out_nuevo = $this->reporte->stockout($nueva_fecha_inicio,$nueva_fecha_fin,$p->id_talla);
                        //operaciones
                        if($nueva_consulta == NULL){
                            $nueva_consulta_ = 0;
                        }else{
                            $nueva_consulta_ = $nueva_consulta;
                        }
                        if($stock_added_nuevo == NULL){
                            $stock_added_nuevo_ = 0;
                        }else{
                            $stock_added_nuevo_ = $stock_added_nuevo;
                        }
                        if($cantidad_nueva == NULL){
                            $cantidad_nueva = 0;
                        }else{
                            $cantidad_nueva_ = $cantidad_nueva;
                        }
                        if($stock_out_nuevo == NULL){
                            $stock_out_nuevo_ = 0;
                        }else{
                            $stock_out_nuevo_ = $stock_out_nuevo;
                        }
                        if($artificio == true){
                            $nuevo_valor_inicial = $nueva_consulta;
                        }else{
                            $nuevo_valor_inicial = $nueva_consulta_ + $stock_added_nuevo_ - $cantidad_nueva_ - $stock_out_nuevo_;
                        }

                        $stock_added = $this->reporte->stockadded_o($fecha_filtro,$fecha_filtro_fin, $p->id_talla);
                        $stock_out = $this->reporte->stockout_o($fecha_filtro,$fecha_filtro_fin, $p->id_talla);
                        $cantidad = $this->reporte->products_selled_o($fecha_filtro,$fecha_filtro_fin,$p->id_talla);

                        if($fecha_filtro_fin == date('Y-m-d')){
                            $stock_now = $this->reporte->total_productos_now($p->id_talla);
                        }else{
                            $stock_now = $nuevo_valor_inicial +$stock_added - $cantidad - $stock_out;
                        }

                        //$total_por_producto = $this->reporte->total_por_producto($fecha_filtro,$fecha_filtro_fin,$p->id_producto);
                        $total_por_producto = $this->reporte->total_por_producto_toda_fila_o($fecha_filtro,$fecha_filtro_fin,$p->id_talla);
                        $monto_total_producto = 0;
                        $nuevo_descuento = 0;
                        $porcentaje = 0;
                        $descuento_total_p = 0;
                        $restar = 0;
                        foreach ($total_por_producto as $tp){
                            $total_temporal = $tp->venta_detalle_importe_total;

                            if($tp->venta_descuento_global > 0){
                                $porcentaje = $total_temporal * $tp->venta_descuento_global;
                                $total_temporal = $total_temporal - $porcentaje;

                                $descuento_total_p = $descuento_total_p + $porcentaje;
                            }
                            if($tp->venta_detalle_descuento > 0){
                                $restar = $restar + $tp->venta_detalle_descuento;

                            }
                            $monto_total_producto = $monto_total_producto + $total_temporal;
                            $nuevo_descuento = $descuento_total_p + $restar;
                        }

                        $ingresos_productos = $ingresos_productos + $monto_total_producto;
                        $sumasa = $stock_added_nuevo + $stock_out + $cantidad;

                        //$jalar_descuento_x_prod = $this->repporte->jalar_descuento_x_prod($p->id_producto);

                        if($sumasa > 0){
                            ?>
                            <tr>
                                <td><?php echo $p->producto_nombre;?> / <?= $p->talla_nombre?></td>
                                <!--<td><?php echo $nuevo_valor_inicial ?? 0;?></td>-->
                                <td>
                                    <a style="color: blue; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="salidas_stock(<?= $p->id_producto;?>,'<?= $fecha_filtro?>','<?= $fecha_filtro_fin?>',1)">
                                        <?php echo $stock_added?? 0;?>
                                    </a>
                                </td>
                                <td><?php echo $p->producto_precio_valor;?></td>
                                <td><?php echo $nuevo_descuento?></td>
                                <td>
                                    <a style="color: red; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="salidas_stock(<?= $p->id_producto;?>,'<?= $fecha_filtro?>','<?= $fecha_filtro_fin?>',2)">
                                        <?php echo $cantidad ?? 0;?>
                                    </a>
                                </td>
                                <td>
                                    <a style="color: blue; cursor: pointer;" class="button" data-toggle="modal" data-target="#ver_general" onclick="salidas_stock(<?= $p->id_producto;?>,'<?= $fecha_filtro?>','<?= $fecha_filtro_fin?>',3)">
                                        <?php echo $stock_out ?? 0;?>
                                    </a>

                                </td>
                                <!--<td><?php echo $stock_now ?? 0;?></td>-->
                                <td>S/. <?php echo $monto_total_producto ?? 0;?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    //Fin de Calculo Todo Lo Que Es Productos
                    ?>
                    <tr><td colspan="6" style="text-align: right">Total Ingresos Ventas Productos:</td><td style="background-color: #f9f17f"><b> S/. <?php echo number_format($ingresos_productos,2) ?? 0;?></b></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-6">
                <h3 style="text-align: center">Salida de Caja Chica</h3>
                <?php
                $egresos_dia = $this->reporte->listar_egresos_dia($fecha_filtro,$fecha_filtro_fin);
                $egresos = 0;
                $egresos_resta = 0;
                ?>
                <table class="table table-bordered table-hover" style="border-color: black">
                    <thead>
                    <tr style="background-color: #ebebeb">
                        <th>Descripcion Egreso</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($egresos_dia as $ex) {
                        if($ex->movimiento_tipo == 1){
                            $movi = 'Ingreso';
                        }else{
                            $movi = 'Salida';
                        }
                        ?>
                        <tr>
                            <td><?php echo $ex->egreso_descripcion;?></td>
                            <td><?php echo $movi;?></td>
                            <td>S/. <?php echo $ex->egreso_monto;?></td>
                        </tr>
                        <?php
                        $egresos = $egresos + $ex->egreso_monto;
                        $egresos_resta = $egresos_resta + $ex->egreso_monto;
                    }?>
                    <tr><td  colspan="2" style="text-align: right;">Egresos Totales:</td><td style="background-color: #f9f17f"><b>S/. <?php echo number_format($egresos,2) ?? 0;?></b></td></tr>
                    </tbody>
                </table>
            </div>

            <div class="col-lg-6">
                <h3 style="text-align: center">Ingresos de Caja Chica</h3>
                <?php
                $egresos_dia_ = $this->reporte->listar_ingresos_dia($fecha_filtro,$fecha_filtro_fin);
                $ingresos_e = 0;
                $egresos_resta = 0;
                ?>
                <table class="table table-bordered table-hover" style="border-color: black">
                    <thead>
                    <tr style="background-color: #ebebeb">
                        <th>Descripcion Ingreso</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($egresos_dia_ as $ex) {
                        if($ex->movimiento_tipo == 1){
                            $movi = 'Ingreso';
                        }else{
                            $movi = 'Salida';
                        }
                        ?>
                        <tr>
                            <td><?php echo $ex->egreso_descripcion;?></td>
                            <td><?php echo $movi;?></td>
                            <td>S/. <?php echo $ex->egreso_monto;?></td>
                        </tr>
                        <?php
                        $ingresos_e = $ingresos_e + $ex->egreso_monto;
                        $egresos_resta = $egresos_resta + $ex->egreso_monto;
                    }?>
                    <tr><td  colspan="2" style="text-align: right;">Ingresos Totales:</td><td style="background-color: #f9f17f"><b>S/. <?php echo number_format($ingresos_e,2) ?? 0;?></b></td></tr>
                    </tbody>
                </table>
            </div>
            <?php
            $impuestos = $this->reporte->impuestos_($fecha_filtro,$fecha_filtro_fin);
            $impuestos_ = $impuestos->total;
            $balance_final = $ingresos_productos + $ingresos_productos_t - $egresos + $ingresos_e + $impuestos_;
            $balance_final_ = $ingresos_productos + $ingresos_productos_t;

            ?>
            <div class="col-lg-3"></div>
            <div class="col-lg-6 text-center"><br><br>
                <table class="table">
                    <tbody>
                    <tr>
                        <td style="background-color: #ebebeb; font-weight: bold">TOTAL INGRESOS VENTAS:</td>
                        <td>S/. <?php echo number_format($balance_final_,2) ?? 0;?></td>
                    </tr>
                    <tr>
                        <td style="background-color: #ebebeb; font-weight: bold">TOTAL EGRESOS:</td>
                        <td>S/. <?php echo number_format($egresos,2) ?? 0;?></td>
                    </tr>
                    <tr>
                        <td style="background-color: #ebebeb; font-weight: bold">TOTAL INGRESOS:</td>
                        <td>S/. <?php echo number_format($ingresos_e,2) ?? 0;?></td>
                    </tr>
                    <tr style="border-top: 2px solid green">
                        <td style="background-color: #ebebeb; font-weight: bold">TOTAL IMPUESTOS POR BOLSA:</td>
                        <td>S/. <?php echo number_format($impuestos_,2) ?? 0;?></td>
                    </tr>
                    <tr style="border-top: 2px solid green; display: none">
                        <td style="background-color: #ebebeb; font-weight: bold">SALDO TOTAL DEL DIA:</td>
                        <td>S/. <?php echo number_format($balance_final,2) ?? 0;?></td>
                    </tr>
                    <tr>
                        <td style="background-color: #ebebeb; font-weight: bold">MONTO DE APERTURA DE CAJA:</td>
                        <td>S/. <?php echo number_format($listar_monto_inicial->total_apertura,2) ?? 0;?></td>
                    </tr>
                    <tr style="border-top: 3px solid red;">
                        <td style="background-color: #ebebeb; font-weight: bold">TOTAL EN CAJA:</td>
                        <td style="background-color: #f9f17f; font-weight: bold">S/. <?php echo number_format($balance_final + $listar_monto_inicial->total_apertura,2);?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <br>
        <div class="row" style="display:none;">
            <div class="col-lg-12" style="text-align: center">
                <a class="btn btn-primary" target="_blank" href="<?= _SERVER_ ; ?>index.php?c=Reporte&a=reporte_dia_pdf&fecha_filtro=<?= $_POST['fecha_filtro']?>&fecha_filtro_fin=<?= $_POST['fecha_filtro_fin']?>"><i class="fa fa-print"></i> Imprimir</a>
            </div>
        </div>
    </section>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function salidas_stock(id_producto, fecha_filtro, fecha_filtro_fin,identificador){
        //$("#id_producto").val(id_producto);
        $.ajax({
            type: "POST",
            url: urlweb + "api/Reporte/salidas_stock",
            data: "id_producto="+id_producto + "&fecha_filtro=" + fecha_filtro+ "&fecha_filtro_fin=" + fecha_filtro_fin + "&identificador=" + identificador,
            dataType: 'json',
            success:function (r) {
                $("#detalle").html(r.detalle);
                $("#nombre").html(r.detalle_);
            }
        });
    }


</script>

<script>
    $(function () {
        $("#fecha_filtro").datepicker();
    });
</script>