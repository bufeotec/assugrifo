<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 04/12/2020
 * Time: 12:05 a. m.
 */
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="d-sm-flex align-items-center mb-4">
        <!--        <a class="btn btn-success" href="javascript:history.back()" role="button"><i class="fa fa-backward"></i> Regresar</a>
        -->
    </div>
    <div class="card">
        <div class="card-body">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-7 col-sm-7">
                    <div class="row">
                        <div class="col-lg-12 col-sm-6 align-middle">
                            <div class="row">
                                <div class=" col-lg-3" ><img style="margin-top: 5px; margin-left: 130px" src="<?= _SERVER_ ?>media/logo/logo_cralm.png" width="50%"></div>
                                <div class="col-lg-6 text-right mt-2 " style="border-left: 3px solid #e5e8e8>
                        <label style=">CRALM GROUP E.I.R.L.
                                    <label style="font-weight: 500">RUC 20609569752</label></div>

                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 text-center " style="border-left: 3px solid #e5e8e8" >
                            <div class="row">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-8"> <p class="form-control-label" style="font-weight: 400;font-size: 15px ; margin-bottom: 0px">CAL.ESTADO DE ISRAEL NRO. 256 URB. VIRGEN DE LORETO (ALT 13 Y 14 CALVO DE ARAUJO)</p>
                                    <p style="font-weight: 400; font-size: 14px; margin-bottom: 0px" >LORETO - MAYNAS - IQUITOS</p></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-sm-5 " style="padding-right: 3rem">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 text-center" style="border: 2px solid #16A085; border-radius: 5px" >
                            <br>
                            <h2 class="concss" style="text-align: center;">GUIA DE REMISIÓN ELECTRÓNICA</h2>
                            <h3><?= $serie->serie ?> - <?= $serie->correlativo+1 ?>   </h3>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Main row -->
            <div class="row mt-1">
                <div class="col-lg-4 col-sm-4">
                    <p><i class="fa fa-user"></i> Nombre Del Cliente: <br> <?php if(strlen($sale->cliente_numero)==11){ echo $sale->cliente_razonsocial;}else{ echo $sale->cliente_nombre;}  ?></p>
                    <p><i class="fa fa-calendar"></i> Fecha de Venta: <br> <?php echo $sale->venta_fecha;?></p>
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $sale->id_cliente;?>">
                </div>
                <div class="col-lg-3 col-sm-3" style="text-align: left">
                    <p>RUC ó DNI: <br> <strong><?php echo $sale->cliente_numero;?></strong></p>
                    <p>Serie y Correlativo: <strong> <br> <?php echo $sale->venta_serie.'-'.$sale->venta_correlativo?></strong></p>

                </div>
                <div class="col-lg-3 col-sm-3">
                    <p>Email: <br> <strong><?php echo $sale->cliente_correo;?></strong></p>
                    <p>Telefono: <strong> <br> <?php echo $sale->cliente_telefono ?></strong></p>

                </div>
                <div class="col-lg-2 col-sm-2">
                    <?php
                    $idroleUser = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);
                    if($sale->anulado_sunat == 0){
                        ?>
                        <p style="color: green; float: right;font-size: 14px"><i class="fa fa-check-circle"></i> Venta Realizada Correctamente</p>
                        <?php
                        /*if ($idroleUser == 4){
                            if($id_turn == $sale->id_turn){ //si la venta no es del dia actual no se genera la anulacion
                                ?>
                                <a type="button" class="btn btn-xs btn-danger" style="float: right" onclick="preguntarSiNoA(<?php echo $sale->id_saleproduct;?>)"><i class="fa fa-times-circle"></i> Anular Venta</a>
                                <?php
                            }
                        } else{
                            ?>
                            <a type="button" class="btn btn-xs btn-danger" style="float: right" onclick="preguntarSiNoA(<?php echo $sale->id_saleproduct;?>)"><i class="fa fa-times-circle"></i> Anular Venta</a>
                            <?php
                        }       */
                    } else {
                        ?>
                        <p style="color: red; float: right;"><i class="fa fa-times-circle"></i> Esta Venta fue ANULADA</p>
                        <?php
                    }
                    ?>

                </div>

            </div>
            <br>

            <!-- /.row (main row) -->
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered table-hover" style="border-color: black">
                        <thead class="text-white font-weight-bold" style="background-color: #5D6D7E">
                        <tr >
                            <th>CANTIDAD</th>
                            <th>U. MED</th>
                            <th>DESCRIPCION</th>
                            <!--<th>PESO TOTAL</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totales = count($productssale);
                        $monto = 0;
                        if($totales == 0){
                            ?>
                            <center><h2>Aún no hay productos</h2></center>
                            <?php
                        } else {
                            foreach ($productssale as $p){
                                $subtotal = 0;
                                $subtotal = $p->venta_detalle_valor_total;
                                $monto = $monto + $subtotal;
                                ?>
                                <tr>
                                    <!--<td><?php //echo $p->id_productforsale;?></td>-->
                                    <td><?php echo $p->venta_detalle_cantidad;?></td>
                                    <td>UNIDAD</td>
                                    <!--<td><?php echo $p->venta_detalle_valor_unitario ;?></td>-->
                                    <td><?php echo $p->venta_detalle_nombre_producto;?></td>
                                    <!--<td></td>-->
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                        <!--<tfoot>
                        <tr>
                            <td colspan="3" style="text-align:right;">PRECIO TOTAL</td>
                            <?php $num_ = explode(".",$monto);
                        $dec = round($num_[1],2);
                        if(strlen($dec)==1){
                            $dec = $dec ."0";
                            ($dec==0) ? $monto = $monto.".00": $monto = $monto."0";
                        } ?>
                            <td style="background-color: #f9f17f; font-weight: bold">S/. <?php echo $monto;?></td>
                        </tr>
                    </tfoot>-->
                    </table>
                    <div class="row no-show">

                        <div id="espacio" class = "col-lg-8">
                        </div>

                        <div class="col-lg-4">
                            <?php
                            if($sale->venta_totaldescuento > 0){
                                ?>
                                <h6>DESCUENTO(-): s/. <?php echo number_format($sale->venta_totaldescuento ,2);?></h6>
                                <?php
                            }
                            ?>
                            <h6 style="display: none" >OP. GRATUITA: s/. <?php echo number_format($sale->venta_totalgratuita ,2);?></h6>
                            <h6 style="display: none">OP. EXONERADA: s/. <?php echo number_format($sale->venta_totalexonerada ,2);?></h6>
                            <h6 style="display: none">OP. INAFECTA: s/. <?php echo number_format($sale->venta_totalinafecta, 2);?></h6>
                            <h6 style="display: none">OP. GRAVADA: s/. <?php echo number_format($sale->venta_totalgravada , 2);?></h6>
                            <br>
                            <h6 class="mt-4" style="display: none">IGV: s/. <?php echo number_format($sale->venta_totaligv , 2);?></h6>
                            <?php
                            if ($sale->venta_icbper > 0){ ?>
                                <h6>ICBPER: s/. <?php echo number_format($sale->venta_icbper , 2);?></h6>
                            <?php }
                            ?>

                            <table class="table table-responsive">
                                <tbody>
                                <td class="text-white font-weight-bold" style="background: #34495E">PRECIO TOTAL:</td>
                                <td class="text-right " style="padding-right: 2px ;border: 1px solid #34495E" >S/. <?php echo number_format($sale->venta_total , 2);?></td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" >
                    <div class="card" style="border: 1px solid #34495E; border-radius: 0%">
                        <div class="card-header" style="background: #34495E">
                            <h4 class="text-white">DATOS DE GUIA</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label for="fecha_emision">Fecha Emisión</label>
                                    <input type="date" id="fecha_emision" class="form-control" name="fecha_emision" value="<?= date('Y-m-d') ?>" readonly>
                                </div>
                                <div class="col-lg-3">
                                    <label for="motivo_tras">Motivo de Traslado</label>
                                    <select class="form-control" name="motivo_tras" id="motivo_tras">
                                        <option value="01">VENTA</option>
                                        <option value="02">COMPRA</option>
                                        <option value="03">VENTA CON ENTREGA A TERCEROS</option>
                                        <option value="04">TRASLADO ENTRE ESTABLECIMIENTOS</option>
                                        <option value="05">CONSIGNACIÓN</option>
                                        <option value="06">DEVOLUCIÓN</option>
                                        <option value="07">RECOJO DE BIENES TRANSFORMADOS</option>
                                        <option value="08">IMPORTACION</option>
                                        <option value="09">EXPORTACION</option>
                                        <option value="13">OTROS</option>
                                        <option value="14">VENTA SUJETA A CONFIRMACION DEL COMPRADOR</option>
                                        <option value="17">TRASLADO DE BIENES PARA TRANSFORMACIÓN</option>
                                        <option value="18">TRASLADO EMISOR ITINERANTE CP</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="tipo_trans">Tipo de Transporte</label>
                                    <select name="tipo_trans" class="form-control" id="tipo_trans">
                                        <option value="">Seleccionar...</option>
                                        <option value="01">TRANSPORTE PÚBLICO</option>
                                        <option value="02">TRANSPORTE PRIVADO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" >
                    <div class="card" style="border: 1px solid #34495E; border-radius: 0%">
                        <div class="card-header" style="background: #34495E">
                            <h4 data-toggle="collapse" href="#div_traslado" class="text-white">DATOS DEL TRASLADO</h4>
                        </div>
                        <div id="div_traslado" class="card-body collapse">
                            <div class="row"  >
                                <div class="col-lg-2">
                                    <label for="fecha_traslado">Fecha Traslado</label>
                                    <input type="date" id="fecha_tras" class="form-control" name="fecha_tras" value="<?= date('Y-m-d') ?>" onchange="validar_fecha_traslado()">
                                </div>
                                <div class="col-lg-2">
                                    <label for="peso_bruto">Peso Bruto</label>
                                    <input type="text" id="peso_bruto" value="0" onkeyup="validar_numeros_decimales_dos(this.id)" class="form-control">
                                </div>
                                <div class="col-lg-3">
                                    <label for="peso_unidad_medida">Peso - unidad de medidad</label>
                                    <select name="peso_unidad_medida" class="form-control" id="peso_unidad_medida">
                                        <option value="KGM" selected>KGM - KILOGRAMO</option>
                                        <option value="TNE" >TNE - TONELADA</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="numero_bultos">N° de Bultos</label>
                                    <input type="text" onkeyup="validar_numeros_decimales_dos(this.id)" value="0" id="numero_bultos" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="datos_transportista" >
                <div class="col-lg-12">
                    <div class="card" style="border: 1px solid #34495E; border-radius: 0% ">
                        <div class="card-header text-white" style="background: #34495E">
                            <h4 data-toggle="collapse" href="#div_transportista">DATOS TRANSPORTISTA</h4>
                        </div>
                        <div id="div_transportista" class="card-body collapse">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="tipo_documento_trans">Tipo Documento</label>
                                    <select class="form-control" name="tipo_documento_trans" id="tipo_documento_trans">
                                        <option value="">Seleccionar...</option>
                                        <option value="6">RUC</option>
                                        <!--<option value="1">DNI</option>
                                        <option value="-">VARIOS- VENTAS MENORES DE S/. 700</option>
                                        <option value="4">CARNET DE EXTRANJERIA</option>
                                        <option value="7">PASAPORTE</option>
                                        <option value="A">CEDULA</option>
                                        <option value="0">NO DOMICIALIADO</option>-->
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="numero_doc_trans">Número Documento</label>
                                    <input type="text" id="numero_doc_trans" class="form-control">
                                </div>
                                <div class="col-lg-4">
                                    <label for="denominacion_trans">Transporte Denominación</label>
                                    <input type="text" id="denominacion_trans" onkeyup="mayuscula(this.id)" class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    <label for="num_placa_trans">Transportista placa número</label>
                                    <input type="text" id="num_placa_trans" class="form-control">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="datos_conductor">
                <div class="col-lg-12">
                    <div class="card" style="border: 1px solid #34495E; border-radius: 0%">
                        <div class="card-header text-white" style="background: #34495E">
                            <h4 data-toggle="collapse" href="#div_conductor"> DATOS DEL CONDUCTOR</h4>
                        </div>
                        <div id="div_conductor" class="card-body collapse">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label for="tipo_documento_con">Tipo Documento</label>
                                    <select name="tipo_documento_con" id="tipo_documento_con" class="form-control">
                                        <option value="6">RUC</option>
                                        <option value="1">DNI</option>
                                        <option value="4">CARNET DE EXTRANJERIA</option>
                                        <option value="7">PASAPORTE</option>
                                        <option value="A">CEDULA</option>
                                        <option value="0">NO DOMICIALIADO</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="numero_doc_con">Número documento</label>
                                    <input type="text" id="numero_doc_con" class="form-control">
                                </div>
                                <div class="col-lg-3">
                                    <label for="nombre_con">Nombre del Conductor</label>
                                    <input type="text" id="nombre_con" onkeyup="mayuscula(this.id)" name="nombre_con" class="form-control" maxlength="250">
                                </div>
                                <div class="col-lg-3">
                                    <label for="apellido_con">Apellidos del Conductor</label>
                                    <input type="text" id="apellido_con" onkeyup="mayuscula(this.id)" name="apellido_con" class="form-control" maxlength="250">
                                </div>
                                <div class="col-lg-2">
                                    <label for="licencia_con">Licencia de conducir</label>
                                    <input type="text" id="licencia_con" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="border: 1px solid #34495E; border-radius: 0%">
                        <div class="card-header text-white" style="background: #34495E">
                            <h4 data-toggle="collapse" href="#div_partida"> PUNTO PARTIDA</h4>
                        </div>
                        <div  id="div_partida" class="card-body collapse">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="ubigeo_partida">Ubigeo dirección de partida</label>
                                    <select name="ubigeo_partida" id="ubigeo_partida" class="form-control">
                                        <?php foreach ( $ubigeo as $u ){
                                            ($u->ubigeo_distrito=='IQUITOS')? $select='selected' : $select='' ;
                                            ?>
                                            <option value="<?= $u->ubigeo_cod ?>" <?= $select ?> > <?= $u->ubigeo_distrito.' | '.$u->ubigeo_provincia.' | '.$u->ubigeo_departamento.' | '.$u->ubigeo_cod  ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="direccion_partida">Dirección punto de partida</label>
                                    <input type="text" id="direccion_partida" onkeyup="mayuscula(this.id)" name="direccion_partida" class="form-control" value="CAL. JORGE CHAVEZ 122">

                                </div>
                                <div class="col-lg-3">
                                    <label for="cod_establec_partida">Código establecimiento</label>
                                    <input type="text" id="cod_establec_partida" name="cod_establec_partida" class="form-control" placeholder="Por defecto '0000'" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="border: 1px solid #34495E; border-radius: 0%">
                        <div class="card-header text-white" style="background: #34495E">
                            <h4 data-toggle="collapse" href="#div_llegada"> PUNTO DE LLEGADA</h4>
                        </div>
                        <div id="div_llegada" class="card-body collapse">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="ubigeo_llegada">Ubigeo dirección de partida</label>
                                    <select name="ubigeo_llegada" id="ubigeo_llegada" class="form-control">
                                        <?php foreach ( $ubigeo as $u ){
                                            ($u->ubigeo_distrito=='IQUITOS')? $select='selected' : $select='' ;
                                            ?>
                                            <option value="<?= $u->ubigeo_cod ?>" <?= $select ?> > <?= $u->ubigeo_distrito.' | '.$u->ubigeo_provincia.' | '.$u->ubigeo_departamento.' | '.$u->ubigeo_cod  ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="direccion_llegada">Dirección punto de llegada</label>
                                    <input type="text" id="direccion_llegada" onkeyup="mayuscula(this.id)" name="direccion_llegada" class="form-control">

                                </div>
                                <div class="col-lg-3">
                                    <label for="cod_establec_llegada">Código establecimiento</label>
                                    <input type="text" id="cod_establec_llegada" name="cod_establec_llegada" class="form-control" placeholder="Por defecto '0000'" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" >
                    <div class="card" style="border: 1px solid #34495E">
                        <div class="card-header text-white" style="background: #34495E">
                            <h4 data-toggle="collapse" href="#div_observacion">OBSERVACION</h4>
                        </div>
                        <div id="div_observacion" class="card-body collapse">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="">Observaciones</label>
                                    <textarea class="form-control" name="observacion" id="observacion" maxlength="250" rows="1"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <br>
            <div class="row">
                <div class="col-lg-12" style="text-align: center">
                    <!--<a class="btn btn-primary" target="_blank" href="<?php echo _SERVER_. 'Ventas/print_comprobante/' . $id;?>"><i class="fa fa-print"></i> Imprimir Comprabante</a>-->
                    <!--<a id="imprimir_ticket" class="btn btn-success mt-2" style="color: white;" target="_blank" onclick="ticket_venta(<?/*= $id;*/?>)"><i class="fa fa-print"></i> Imprimir</a>-->

                    <a onclick="guardar_remision(<?= $id_venta = $_GET['id'].','. $id_caja= $sale->id_caja  ?>)" class="btn btn-success text-white"><i class="fa fa-save"></i> GUARDAR</a>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#ubigeo_llegada").select2();
        $("#ubigeo_partida").select2();
    })

    document.getElementById('tipo_trans').addEventListener('change', function() {
        if(this.value=='02'){
            $('#datos_conductor').show();
        }else if(this.value=='01'){
            $('#datos_conductor').hide();
        }
    });
    document.getElementById('fecha_tras').addEventListener('change', function() {
        let fecha_emision = $('#fecha_emision').val()
        let fecha_traslado = this.value

        var fecha_inicial = new Date(fecha_emision);
        var fecha_final = new Date(fecha_traslado);

        if(fecha_inicial > fecha_final){
            respuesta('La Fecha de Traslado no debe ser menor que la Fecha de Emisión','error')
            $('#fecha_tras').val(fecha_emision);
        }
    });

    document.getElementById('numero_doc_trans').addEventListener('change', function() {
        let num = $('#tipo_documento_trans').val();
        switch (num){
            case '6': if(this.value.length!=11){ respuesta('RUC debe ser 11 Digitos','error');this.value=''  } break;

            case '1': if(this.value.length!=8){ respuesta('DNI debe ser 8 Digitos','error');this.value=''  } break;
            case '-': if(this.value.length <= 8){ respuesta('Minimo 8 Digitos','error');this.value='11111111'  } break;
            default: break;
        }
    });
    document.getElementById('numero_doc_con').addEventListener('change', function() {
        let num = $('#tipo_documento_con').val();
        switch (num){
            case '6': if(this.value.length!=11){ respuesta('RUC debe ser 11 Digitos','error');this.value=''  } break;

            case '1': if(this.value.length!=8){ respuesta('DNI debe ser 8 Digitos','error');this.value=''  } break;
            case '-': if(this.value.length <= 8){ respuesta('Minimo 8 Digitos','error');this.value='11111111'  } break;
            default: break;
        }
    });

    function guardar_remision(id_venta, id_caja){
        var valor = true;
        var cadena = '';
        let razon_conductor = '';
        cadena= 'venta='+id_venta+'&caja='+id_caja;
        let id_cliente = $('#id_cliente').val();
        let fecha_emision = $('#fecha_emision').val();
        let motivo_tras = $('#motivo_tras').val();
        let tipo_trans = $('#tipo_trans').val();
        let fecha_tras = $('#fecha_tras').val();
        let peso_bruto = $('#peso_bruto').val();
        let peso_unidad_medida = $('#peso_unidad_medida').val();
        let numero_bultos = $('#numero_bultos').val();
        let tipo_documento_trans = $('#tipo_documento_trans').val();
        let numero_doc_trans = $('#numero_doc_trans').val();
        let denominacion_trans = $('#denominacion_trans').val();
        let num_placa_trans = $('#num_placa_trans').val();
        let tipo_documento_con = $('#tipo_documento_con').val();
        let numero_doc_con = $('#numero_doc_con').val();
        let nombre_con = $('#nombre_con').val();
        let apellido_con = $('#apellido_con').val();
        let licencia_con = $('#licencia_con').val();
        let ubigeo_partida = $('#ubigeo_partida').val();
        let direccion_partida = $('#direccion_partida').val();
        let cod_establec_partida = '0000';
        let ubigeo_llegada = $('#ubigeo_llegada').val();
        let direccion_llegada = $('#direccion_llegada').val();
        let cod_establec_llegada = '0000';
        let observacion = $('#observacion').val();

        valor = validar_campo_vacio('motivo_tras',motivo_tras, valor)
        valor = validar_campo_vacio('tipo_trans',tipo_trans, valor)
        valor = validar_campo_vacio('peso_bruto',peso_bruto, valor)
        valor = validar_campo_vacio('peso_unidad_medida',peso_unidad_medida, valor)
        valor = validar_campo_vacio('numero_bultos',numero_bultos, valor)
        valor = validar_campo_vacio('fecha_tras',fecha_tras, valor)
        valor = validar_campo_vacio('tipo_documento_trans',tipo_documento_trans, valor)
        valor = validar_campo_vacio('numero_doc_trans',numero_doc_trans, valor)
        valor = validar_campo_vacio('denominacion_trans',denominacion_trans, valor)
        if(tipo_trans==='02'){
            valor = validar_campo_vacio('num_placa_trans',num_placa_trans, valor)
            valor = validar_campo_vacio('tipo_documento_con',tipo_documento_con, valor)
            valor = validar_campo_vacio('numero_doc_con',numero_doc_con, valor)
            valor = validar_campo_vacio('nombre_con',nombre_con, valor)
            valor = validar_campo_vacio('apellido_con',apellido_con, valor)
            valor = validar_campo_vacio('licencia_con',licencia_con, valor)
            razon_conductor = nombre_con + ' // ' + apellido_con
        }
        valor = validar_campo_vacio('ubigeo_partida',ubigeo_partida, valor)
        valor = validar_campo_vacio('direccion_partida',direccion_partida, valor)
        valor = validar_campo_vacio('ubigeo_llegada',ubigeo_llegada, valor)
        valor = validar_campo_vacio('direccion_llegada',direccion_llegada, valor)
        //valor = false
        if (valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/guardar_guia",
                data: {
                    'venta': id_venta,
                    'id_cliente': id_cliente,
                    'caja':id_caja,
                    'fecha_emision':fecha_emision ,
                    'motivo_tras': motivo_tras,
                    'tipo_trans':tipo_trans ,
                    'fecha_tansp':fecha_tras ,
                    'peso_bruto':peso_bruto ,
                    'peso_unidad_medida':peso_unidad_medida ,
                    'numero_bultos': numero_bultos ,
                    'tipo_documento_trans': tipo_documento_trans ,
                    'numero_doc_trans': numero_doc_trans ,
                    'denominacion_trans': denominacion_trans ,
                    'num_placa_trans': num_placa_trans ,
                    'tipo_documento_con': tipo_documento_con ,
                    'numero_doc_con': numero_doc_con ,
                    'nombre_con':razon_conductor ,
                    'licencia_con': licencia_con ,
                    'ubigeo_partida': ubigeo_partida ,
                    'direccion_partida': direccion_partida ,
                    'cod_establec_partida': cod_establec_partida ,
                    'ubigeo_llegada': ubigeo_llegada ,
                    'direccion_llegada': direccion_llegada ,
                    'cod_establec_llegada': cod_establec_llegada ,
                    'observacion': observacion
                },
                dataType: 'json',
                success:function (r) {
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Guardado Correctamente!...', 'success');
                            setTimeout(function () {
                                location.href = urlweb +'Ventas/historial_guias'
                            }, 800);
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }

    }
    function ticket_venta(id){
        var boton = 'imprimir_ticket';
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/ticket_electronico",
            data: "id=" + id,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'imprimiendo...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-print\"></i> Imprimir", false);
                console.log(r.result.code);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Éxito!...', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 800);
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
</script>
