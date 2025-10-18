<?php
/**
 * Created by PhpStorm
 * User: Franz
 * Date: 22/04/2019
 * Time: 12:26
 */
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <div class="col-lg-12" style="text-align: center">
                <h4>LISTA DE GUIAS DE REMISION REGISTRADAS SIN ENVIAR A SUNAT</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="<?= _SERVER_ ?>Ventas/historial_guias">
                            <input type="hidden" id="enviar_registro" name="enviar_registro" value="1">
                            <div class="row">

                                <div class="col-lg-4">
                                    <label for="">Fecha de Inicio</label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?= $fecha_ini; ?>">
                                </div>
                                <div class="col-lg-4">
                                    <label for="">Fecha Final</label>
                                    <input type="date" id="fecha_final" name="fecha_final" class="form-control" value="<?= $fecha_fin; ?>">
                                </div>
                                <div class="col-lg-4"  style="margin-top: 8px">
                                    <button class="mt-4 btn btn-success" ><i class="fa fa-search"></i> BUSCAR</button>
                                </div>
                                <div class="col-lg-12" style="text-align: center">
                                    <label for="" style="margin-top: 20px;color: black;">COMPROBANTES SIN ENVIAR: <span style="color: red;"><?= $cant_guia;?></span><br>
                                        <span style="font-size: 12px;"><strong>*</strong> ENVIAR MÁXIMO 3 DIAS DESPUES LA FECHA DE EMISIÓN</span></label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row (main row) -->
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <?php
                    if($filtro) {
                        ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-striped table-earning" id="dataTable" width="100%" cellspacing="0">
                                            <thead class="text-capitalize">
                                            <tr>
                                                <th>#</th>
                                                <th>Fecha Emision</th>
                                                <th>Fecha Traslado</th>
                                                <th>Cliente</th>
                                                <th>Serie - Correlativo</th>
                                                <th>Motivo</th>
                                                <th>Tipo Transporte</th>
                                                <th>PDF</th>
                                                <th>XML</th>
                                                <th>CDR</th>
                                                <th>Estado Sunat</th>
                                                <th>Acción</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $a = 1;
                                            $total = 0;
                                            foreach ($guias as $al){
                                                $cliente = $this->clientes->listar_clientes_editar($al->id_cliente);
                                                if(empty($cliente)){
                                                    $cliente_numero = $al->guia_destinatario_numero;
                                                    $cliente_nombre = $al->guia_destinatario_nombre;
                                                }else{
                                                    $cliente_nombre = $cliente->cliente_razonsocial;
                                                    $cliente_numero = $cliente->cliente_numero;
                                                    if(empty($cliente->cliente_razonsocial)){
                                                        $cliente_nombre = $cliente->cliente_nombre;
                                                    }
                                                }
                                                switch ($al->guia_motivo){
                                                    case '01' : $motivo = 'VENTA';  break;
                                                    case '02' : $motivo = 'COMPRA'; break;
                                                    case '03' : $motivo = 'VENTA CON ENTREGA A TERCEROS'; break;
                                                    case '04' : $motivo = 'TRASLADO ENTRE ESTABLECIMIENTOS'; break;
                                                    case '05' : $motivo = 'CONSIGNACIÓN'; break;
                                                    case '06' : $motivo = 'DEVOLUCIÓN'; break;
                                                    case '07' : $motivo = 'RECOJO DE BIENES TRANSFORMADOS'; break;
                                                    case '08' : $motivo = 'IMPORTACION'; break;
                                                    case '09' : $motivo = 'EXPORTACION'; break;
                                                    case '13' : $motivo = 'OTROS'; break;
                                                    case '14' : $motivo = 'VENTA SUJETA A CONFIRMACION DEL COMPRADOR'; break;
                                                    case '17' : $motivo = 'TRASLADO DE BIENES PARA TRANSFORMACIÓN'; break;
                                                    case '18' : $motivo = 'TRASLADO EMISOR ITINERANTE CP'; break;
                                                    default : $motivo = '';
                                                }
                                                if($al->guia_tipo_trans == '01'){
                                                    $transporte = 'PUBLICO';
                                                }else{
                                                    $transporte = 'PRIVADO';
                                                }
                                                ?>
                                                <tr id="venta_<?= $al->id_guia;?>" >
                                                    <td><?= $a;?> <input type="hidden" id="valor_<?= $a;?>" value="<?= $al->id_guia;?>"></td>
                                                    <td>
                                                        <i class="fa fa-calendar"></i><?= date('d-m-Y', strtotime($al->fecha_creacion));?>
                                                    </td>
                                                    <td>
                                                        <i class="fa fa-calendar"></i><?= date('d-m-Y', strtotime($al->guia_fecha_traslado));?><br>
                                                    </td>
                                                    <td style="text-align: center"> <?= $cliente_numero. '<br>' .$cliente_nombre;?></td>
                                                    <td> <?= $al->guia_serie. '-' .$al->guia_correlativo;?></td>
                                                    <td>
                                                        <?= $motivo;?>
                                                    </td>
                                                    <td><?= $transporte;?></td>
                                                    <td>
                                                        <!--<a type="button" target='_blank' href="<?= _SERVER_ ; ?>index.php?c=Ventas&a=nueva_guia_pdf&id=<?= $al->id_guia?>" style="color: red" ><i class="fa fa-file-pdf-o"></i></a>-->
                                                        <p style="color: red; font-size: 9pt">EL PDF ESTARÁ LISTO DESPUES DE SER ENVIADO</p>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(file_exists($al->guia_rutaXML)){
                                                            ?>
                                                            <center><a type="button" target='_blank' href="<?= _SERVER_.$al->guia_rutaXML;?>" style="color: blue;" ><i class="fa fa-file-text"></i></a></center>
                                                            <?php
                                                        $nombre_xml = explode('/', $al->guia_rutaXML)
                                                            ?>
                                                            <a class="text-dark" download="<?= $nombre_xml[3];?>" href="<?php echo _SERVER_ . $al->guia_rutaXML;?>" data-toggle="tooltip" title="Descargar"><i class="fa fa-download pdf"></i></a>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(file_exists($al->guia_rutaCDR)){
                                                            ?>
                                                            <center><a type="button" target='_blank' href="<?= _SERVER_.$al->guia_rutaCDR;?>" style="color: green" ><i class="fa fa-file"></i></a></center>
                                                            <?php
                                                        $nombre_cdr = explode('/', $al->guia_rutaCDR)
                                                            ?>
                                                            <a class="text-dark" download="<?= $nombre_cdr[3];?>" href="<?php echo _SERVER_ . $al->venta_rutaCDR;?>" data-toggle="tooltip" title="Descargar"><i class="fa fa-download pdf"></i></a>
                                                            <?php
                                                        }
                                                        ?>

                                                    </td>

                                                    <td style="color: red"><?= (!empty($al->guia_respuesta_sunat))?$al->guia_respuesta_sunat:'Sin Enviar';?></td>
                                                    <td style="text-align: left" >
                                                        <a target="_blank" type="button" title="Ver detalle" class="btn btn-sm btn-primary btne" style="color: white" href="<?php echo _SERVER_. 'ventas/ver_guia/' . $al->id_guia;?>" ><i class="fa fa-eye ver_detalle"></i></a>
                                                        <?php
                                                        if($al->guia_estado_sunat == "0" && $al->guia_respuesta_sunat != 'Envio en proceso'){ ?>
                                                            <a id="btn_enviar<?= $al->id_guia;?>" type="button" title="Enviar a Sunat" class="btn btn-sm btn-success btne" style="color: white" onclick="preguntar('¿Está seguro que desea enviar a la Sunat esta Guia Electrónica?','enviar_guia_sunat','Si','No',<?= $al->id_guia;?>)"><i class="fa fa-check margen"></i></a>
                                                            <?php
                                                        }
                                                        if(!empty($al->guia_remision_numTicket)){
                                                            ?>
                                                            <a type="button" title="Consultar Envio" id="btn_consultar_ticket<?= $al->id_guia;?>" class="btn btn-sm btn-warning btne" style="color: white" onclick="consultar_ticket_guia(<?= $al->id_guia ?>)" ><i class="fa fa-circle-o-notch"></i></a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $a++;
                                            }
                                            ?>
                                            </tbody>

                                        </table>
                                        <input type="hidden" id="numero_envio" value="<?= $a ?>">
                                    </div>
                                </div>

                            </div>

                        </div>

                        <?php
                    }
                    ?>

                </div>

            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script type="text/javascript">

    /*async function mensaje(){
            Swal.fire({
                position: 'top-end',
                title: 'Recargar Pagina',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
    }*/
    function consultar_ticket_guia(id){
        var boton = "btn_consultar_ticket" + id;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/consultar_ticket_guia",
            data: 'id=' + id,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'actualizando...', true);
            },
            success:function (r) {

                switch (r.result.code) {
                    case 1:
                        respuesta('¡Fue actualizada como enviada y aceptada!', 'success');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 300);
                        break;
                    default:
                        respuesta('¡La Guia tiene Error!', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 300)
                        break;
                }
            }

        });
    }
    function enviar_guia_sunat(id_guia){
        var cadena = "id_guia=" + id_guia;
        var boton = 'btn_enviar'+id_guia;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/crear_xml_guia_enviar_sunat",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'enviando...', true);
                $('#btn_enviar'+id_guia).attr('disabled', true)
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i style=\"font-size: 16pt;\" class=\"fa fa-check margen\"></i>", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Guia Remisión Enviado a Sunat!', 'success');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al Enviar la Guía de Remisión', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 3:
                        respuesta('Error, Sunat rechazó el comprobante', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 4:
                        respuesta('Hubo o existe un problema de conexión', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 5:
                        respuesta('Error al guardar en base de datos', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 6:
                        respuesta('Error al obtener el token, comunicarse con BufeoTec', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 7:
                        respuesta('Error de comunicación, comunicarse con BufeoTec', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 8:
                        respuesta('Rechazo al enviar Guia, comunicarse con BufeoTec', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 9:
                        respuesta('Error en la Guía, comunicarse con BufeoTec', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 100:
                        respuesta('Envio en proceso, Click en boton AMARILLO', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    case 10:
                        respuesta('Guia Enviada con Error', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        setTimeout(function () {
                            location.reload();
                            //location.href = urlweb +  'Pedido/gestionar';
                        }, 1000);
                        break;
                }
                $('#btn_enviar'+id_guia).attr('disabled', false)
            }

        });
    }
</script>
