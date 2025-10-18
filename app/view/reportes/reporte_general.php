

<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="container-fluid">
        <hr><h2 class="concss">
            <a href="<?= _SERVER_?>"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="<?= _SERVER_?>Reporte"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> REPORTE GENERAL
        </h2><hr>
    </section>

    <form method="post" action="<?= _SERVER_ ?>Reporte/reporte_general">
        <input type="hidden" id="enviar_fecha" name="enviar_fecha" value="1">
        <div class="row">
            <div class="col-lg-2 col-xs-6 col-md-6 col-sm-6" >
                <label for="">Caja</label>
                <select class="form-control" id="id_caja_numero" name="id_caja_numero">
                    <?php
                    (isset($caja_))?$cajita=$caja_->id_caja_numero:$cajita=0;
                    foreach($caja as $l){
                        ($l->id_caja_numero == $cajita)?$sele='selected':$sele='';
                        ?>
                        <option value="<?php echo $l->id_caja_numero;?>" <?= $sele; ?>><?php echo $l->caja_numero_nombre;?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-2 col-xs-6 col-md-6 col-sm-6" style="display: none">
                <label for="">Usuario</label>
                <select class="form-control" id="id_usuario" name="id_usuario">
                    <?php
                    (isset($usuario_))?$user=$usuario_->id_usuario:$user=0;
                    foreach($usuario as $l){
                        ($l->id_usuario == $user)?$sele='selected':$sele='';
                        ?>
                        <option value="<?php echo $l->id_usuario;?>" <?= $sele; ?>><?php echo $l->persona_nombre;?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="turno">Desde:</label>
                <input type="date" class="form-control" id="fecha_filtro" name="fecha_filtro" step="1" value="<?php echo $fecha_i;?>">
            </div>
            <div class="col-lg-2">
                <label for="turno">Hasta:</label>
                <input type="date" class="form-control" id="fecha_filtro_fin" name="fecha_filtro_fin" value="<?php echo $fecha_f;?>">
            </div>
            <div class="col-lg-3">
                <button style="margin-top: 30px;" class="btn btn-success" ><i class="fa fa-search"></i> Buscar Registro</button>
            </div>
        </div>
    </form>
    <br>

    <div class="row">

        <div class="col-lg-6">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table">
                            <!--<p>Apertura : <?= $datitos->caja_fecha_apertura;?> // Cierre : <?= $datitos->caja_fecha_cierre?> // Monto Cierre : <?= $datitos->caja_cierre;?></p>-->
                            <?php
                            if($datos){
                                //N° DE VENTAS POR TIPO
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

                                //$sumar_datos_p = $sumar_datos_p->total;
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
                                                            $ventas_trans_salon_otros + $impuestos_ + $impuestos_tt_;
                                $ingresos_generales = $ventas_efectivo + $ventas_trans_salon_plin + $ventas_trans_salon_yape+ $ventas_trans_salon_otros+
                                    $ventas_tarjeta + $impuestos_ + $impuestos_tt_ + $monto_caja_apertura + $ingreso_caja_chica - $salida_caja_chica;
                                $egresos_totales = $salida_caja_chica;
                                $diferencia = $monto_caja_apertura + $ingreso_caja_chica + $ventas_efectivo - $salida_caja_chica + $impuestos_;
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- TOTAL DE VENTAS DEL DIA:</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right"> S/.<?= $ingresos_total_de_ventas ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- INGRESOS - EGRESOS:</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right"> S/.<?= $ingresos_generales ?? 0?></label>
                                </div>
                            </div>
                            <p style="border-bottom: 1px solid red"></p>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- Apertura de Caja</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right"> S/.<?= $monto_caja_apertura ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- Ingresos caja chica</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $ingreso_caja_chica ?? 0?></label>
                                </div>
                            </div>
                            <p style="border-bottom: 1px solid red"></p>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- TOTAL VENTAS:</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $ingresos_total_de_ventas ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- Pagos Efectivo:</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $ventas_efectivo ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- Pagos Tarjeta:</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $ventas_tarjeta ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- Pagos Transferencia Plin:</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $ventas_trans_salon_plin ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- Pagos Transferencia Yape:</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $ventas_trans_salon_yape ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- Pagos Transferencia Otros:</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $ventas_trans_salon_otros ?? 0?></label>
                                </div>
                            </div>
                            <p style="border-bottom: 1px solid red"></p>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- TOTAL EGRESOS :</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $salida_caja_chica ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- SALIDA DE CAJA CHICA :</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $salida_caja_chica ?? 0?></label>
                                </div>
                            </div>
                            <p style="border-bottom: 1px solid red"></p>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- TOTAL IMPUESTOS EFECTIVO:</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> <?= $impuestos_ ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- TOTAL IMPUESTOS TARJETA :</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> <?= $impuestos_tt_ ?? 0?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- N° VENTAS :</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> <?= $n_ventas_salon->total ?? 0?></label>
                                </div>
                            </div>
                            <p style="border-bottom: 1px solid red"></p>
                            <div class="row">
                                <div class="col-md-8">
                                    <label>- TOTAL EFECTIVO :</label>
                                </div>
                                <div class="col-md-3">
                                    <label style="text-align: right;"> S/.<?= $diferencia ?? 0?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <h4 style="text-align: center;">DATOS EGRESOS</h4>
                <?php
                $egresos = 0;
                ?>
                <table class="table table-bordered table-hover" style="border-color: black">
                    <thead>
                    <tr style="background-color: #ebebeb">
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Importe</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($datos){
                        $listar_egresos = $this->reporte->listar_egresos_reporte($id_caja,$fecha_ini_caja, $fecha_fin_caja);
                    foreach ($listar_egresos as $le) {
                        ?>
                        <tr>
                            <td><?php echo date('d-m-Y H:i:s',strtotime($le->egreso_fecha_registro));?></td>
                            <td><?php echo $le->egreso_descripcion;?></td>
                            <td>S/. <?php echo $le->egreso_monto;?></td>
                        </tr>
                        <?php
                        $egresos = $egresos + $le->egreso_monto;
                    }?>
                    <tr><td colspan="2" style="text-align: right">Total Egresos:</td><td style="background-color: #f9f17f"><b> S/. <?php echo $egresos ?? 0;?></b></td></tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="col-lg-12  text-center">
                <!--<a id="imprimir_ticket" style="color: white;" class="btn btn-primary mr-5" target="_blank" onclick="ticket_venta('<?= $fecha_i; ?>','<?= $fecha_f?>','<?= $id_caja?>')"><i class="fa fa-print"></i> Ticket</a>
                <a id="imprimir_ticket_productos" style="color: white;" class="btn btn-primary mr-5" target="_blank" onclick="ticket_productos('<?= $fecha_i; ?>','<?= $fecha_f?>','<?= $id_caja?>')"><i class="fa fa-print"></i> Ticket Productos</a>-->
                <a href="<?= _SERVER_ ; ?>index.php?c=Reporte&a=reporte_general_pdf&fecha_filtro=<?= $_POST['fecha_filtro']?>&fecha_filtro_fin=<?= $_POST['fecha_filtro_fin']?>&id_caja=<?= $_POST['id_caja_numero']?>" target="_blank" class="btn btn-primary ml-2"><i class="fa fa-print"></i> Exportar PDF</a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="border-color: black">
                            <thead>
                            <tr style="background-color: #ebebeb">
                                <th>PRODUCTO</th>
                                <th>FECHAS</th>
                                <th>CANT.</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($datos){
                                $productos = $this->reporte->reporte_productos($fecha_ini_caja,$fecha_fin_caja,$id_caja);
                                foreach ($productos as $p){
                                    ?>
                                    <tr>
                                        <td><?= $p->nombre?></td>
                                        <td><?= $fecha_i?> / <?= $fecha_f?></td>
                                        <td><?= $p->total?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
    <br>

</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script>
    function ticket_venta(fecha_i,fecha_f,id_caja){
        var boton = 'imprimir_ticket';

        $.ajax({
            type: 'POST',
            url: urlweb + "api/Reporte/ticket_reporte",
            data: "fecha_i=" + fecha_i + "&fecha_f=" + fecha_f + "&id_caja=" + id_caja,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'imprimiendo...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-print\"></i> Imprimir", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Éxito!...', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 400);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

    function ticket_productos(fecha_i,fecha_f,id_caja){
        var boton = 'imprimir_ticket_productos';

        $.ajax({
            type: 'POST',
            url: urlweb + "api/Reporte/ticket_productos",
            data: "fecha_i=" + fecha_i + "&fecha_f=" + fecha_f + "&id_caja=" + id_caja,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'imprimiendo...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-print\"></i> Imprimir", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Éxito!...', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 400);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

</script>