<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 28/05/2021
 * Time: 12:36 a. m.
 */
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <div class="col-lg-12" style="text-align: center">
                <h4>LISTA DE GUIAS REGISTRADAS</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="<?= _SERVER_ ?>Ventas/historial_guias_enviadas">
                            <input type="hidden" id="enviar_registro" name="enviar_registro" value="1">
                            <div class="row">
                                <!--<div class="col-lg-3">
                                    <label>Estado de Comprobante</label>
                                    <select  id="estado_cpe" name="estado_cpe" class="form-control">
                                        <option <?= ($estado_cpe == "")?'selected':''; ?> value="">Seleccionar...</option>
                                        <option <?= ($estado_cpe == "0")?'selected':''; ?> value="0">Sin Enviar</option>
                                        <option <?= ($estado_cpe == "1")?'selected':''; ?> value="1">Enviado Sunat</option>
                                    </select>
                                </div>-->
                                <div class="col-lg-4">
                                    <label for="">Fecha de Inicio</label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?= $fecha_ini; ?>">
                                </div>
                                <div class="col-lg-4">
                                    <label for="">Fecha Final</label>
                                    <input type="date" id="fecha_final" name="fecha_final" class="form-control" value="<?= $fecha_fin; ?>">
                                </div>
                                <div class="col-lg-4">
                                    <button style="margin-top: 34px;" class="btn btn-success" ><i class="fa fa-search"></i> Buscar</button>
                                </div>
                                <!--<div class="col-lg-3" style="text-align: right;">
                                    <a class="btn btn-primary" style="margin-top: 34px; color: white;" type="button"  data-toggle="modal" data-target="#basicModal"><i class="fa fa-search"></i> Consutar CPE</a>
                                </div>-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row (main row) -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <?php
                    if($filtro) {
                    ?>
                    <div class="card-header py-3">
                        <h5>LISTA DE GUIAS ACEPTADAS
                        </h5>
                    </div>
                    <div class="card-body">
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
                                    $stylee="style= 'text-align: center;'";
                                    if ($al->guia_anulado == 1){
                                        $stylee="style= 'text-align: center; background: #F98892'";
                                    }
                                    ?>
                                    <tr <?= $stylee;?> id="venta_<?= $al->id_guia;?>" >
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
                                            <a type="button" target='_blank' href="<?= $al->guia_linkpdf_sunat;?>" style="color: red" ><i class="fa fa-file-pdf-o"></i></a>
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

                                        <td style="color: green"><?= (!empty($al->guia_respuesta_sunat))?$al->guia_respuesta_sunat:'Sin Enviar';?></td>

                                        <td style="text-align: left">
                                            <a target="_blank" type="button" title="Ver detalle" class="btn btn-sm btn-primary" style="color: white" href="<?php echo _SERVER_. 'Ventas/ver_guia/' . $al->id_guia;?>" ><i class="fa fa-eye ver_detalle"></i></a>
                                            <?php
                                            if($al->guia_anulado == 0){
                                                ?>
                                                <a target="_blank" type="button" id="btn_anular_guia<?= $al->id_guia;?>" class="btn btn-danger" style="color: white" onclick="preguntar('¿Está seguro que desea anular esta Guía?','anular_guia_cambiarestado','Si','No',<?= $al->id_guia;?>)" ><i class="fa fa-ban"></i></a>
                                                <label for="" style="color:red">La anulación se hace desde SUNAT</label>
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
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4">
                        <a id="btnExportar" href="<?= _SERVER_ ; ?>index.php?c=Ventas&a=excel_ventas_enviadas&tipo_venta=<?= $_POST['tipo_venta']?>&fecha_inicio=<?= $_POST['fecha_inicio']?>&fecha_final=<?= $_POST['fecha_final']?>" target="_blank" class="btn btn-success" style="width: 100%"><i class="fa fa-download"></i> Generar Excel</a>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var total_rs = <?= $total_soles; ?>;
        $("#total_soles").html("<b>"+total_rs+"</b>");
    });
</script>
