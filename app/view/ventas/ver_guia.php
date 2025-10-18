<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 24/02/2023
 * Time: 01:52 p. m.
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
                            <h3><?= $guia->guia_serie ?> - <?= $guia->guia_correlativo ?></h3>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Main row -->
            <div class="row mt-1">
                <div class="col-lg-4 col-sm-4">
                    <p><i class="fa fa-user"></i> Nombre Del Cliente: <br> <?php if(strlen($cliente->cliente_numero)==11){ echo $cliente->cliente_razonsocial;}else{ echo $cliente->cliente_nombre;}  ?></p>
                    <?php
                    if(!empty($venta)){
                        ?>
                        <p><i class="fa fa-calendar"></i> Fecha de Venta: <br> <?php echo $venta->venta_fecha;?></p>
                        <?php
                    }
                    ?>
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $guia->id_cliente;?>">
                </div>
                <div class="col-lg-3 col-sm-3" style="text-align: left">
                    <p>RUC ó DNI: <br> <strong><?php echo $cliente->cliente_numero;?></strong></p>
                    <?php
                    if(!empty($venta)){
                        ?>
                        <p>Serie y Correlativo Relación: <strong> <br> <?php echo $venta->venta_serie.'-'.$venta->venta_correlativo?></strong></p>
                        <?php
                    }
                    ?>

                </div>
                <div class="col-lg-3 col-sm-3">
                    <p>Email: <br> <strong><?php echo $cliente->cliente_correo;?></strong></p>
                    <p>Telefono: <strong> <br> <?php echo $cliente->cliente_telefono ?></strong></p>

                </div>
                <div class="col-lg-2 col-sm-2">
                    <?php
                    $idroleUser = $this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_);
                    if($guia->guia_anulado == 0){
                        ?>
                        <p style="color: green; float: right;font-size: 14px"><i class="fa fa-check-circle"></i> Guía Realizada Correctamente</p>
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
                        <p style="color: red; float: right;"><i class="fa fa-times-circle"></i> Esta Guía fue ANULADA</p>
                        <?php
                    }
                    ?>

                </div>

            </div>
            <br>
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
                                    <input type="date" id="fecha_emision" class="form-control" name="fecha_emision" value="<?= date('Y-m-d', strtotime($guia->guia_emision)) ?>" readonly>
                                </div>
                                <div class="col-lg-3">
                                    <label for="motivo_tras">Motivo de Traslado</label>
                                    <select class="form-control" name="motivo_tras" id="motivo_tras" disabled>
                                        <option value="01" <?= ($guia->guia_motivo == '01')?'selected':''?>>VENTA</option>
                                        <option value="02" <?= ($guia->guia_motivo == '02')?'selected':''?>>COMPRA</option>
                                        <option value="03" <?= ($guia->guia_motivo == '03')?'selected':''?>>VENTA CON ENTREGA A TERCEROS</option>
                                        <option value="04" <?= ($guia->guia_motivo == '04')?'selected':''?>>TRASLADO ENTRE ESTABLECIMIENTOS</option>
                                        <option value="05" <?= ($guia->guia_motivo == '05')?'selected':''?>>CONSIGNACIÓN</option>
                                        <option value="06" <?= ($guia->guia_motivo == '06')?'selected':''?>>DEVOLUCIÓN</option>
                                        <option value="07" <?= ($guia->guia_motivo == '07')?'selected':''?>>RECOJO DE BIENES TRANSFORMADOS</option>
                                        <option value="08" <?= ($guia->guia_motivo == '08')?'selected':''?>>IMPORTACION</option>
                                        <option value="09" <?= ($guia->guia_motivo == '09')?'selected':''?>>EXPORTACION</option>
                                        <option value="13" <?= ($guia->guia_motivo == '13')?'selected':''?>>OTROS</option>
                                        <option value="14" <?= ($guia->guia_motivo == '14')?'selected':''?>>VENTA SUJETA A CONFIRMACION DEL COMPRADOR</option>
                                        <option value="17" <?= ($guia->guia_motivo == '17')?'selected':''?>>TRASLADO DE BIENES PARA TRANSFORMACIÓN</option>
                                        <option value="18" <?= ($guia->guia_motivo == '18')?'selected':''?>>TRASLADO EMISOR ITINERANTE CP</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="tipo_trans">Tipo de Transporte</label>
                                    <select name="tipo_trans" class="form-control" id="tipo_trans" disabled>
                                        <option value="">Seleccionar...</option>
                                        <option value="01" <?= ($guia->guia_tipo_trans == '01')?'selected':''?>>TRANSPORTE PÚBLICO</option>
                                        <option value="02" <?= ($guia->guia_tipo_trans == '02')?'selected':''?>>TRANSPORTE PRIVADO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                        $totales = count($guia_detalle);
                        $monto = 0;
                        if($totales == 0){
                            ?>
                            <center><h2>Aún no hay productos</h2></center>
                            <?php
                        } else {
                            foreach ($guia_detalle as $p){
                                ?>
                                <tr>
                                    <!--<td><?php //echo $p->id_productforsale;?></td>-->
                                    <td><?php echo $p->guia_remision_detalle_cantidad;?></td>
                                    <td><?php echo $p->guia_remision_detalle_um;?></td>
                                    <td><?php echo $p->guia_remision_detalle_descripcion;?></td>
                                    <!--<td></td>-->
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="row no-show">

                    </div>
                </div>
            </div>

            <div class="row" id="datos_destinatario">
                <div class="col-lg-12">
                    <table class="table table-bordered table-hover" style="border-color: black">
                        <thead class="text-white font-weight-bold" style="background-color: #5D6D7E">
                        <tr >
                            <th style="width: 240px;">DOC. DESTINATARIO</th>
                            <th>NUM. DOC. DEST.</th>
                            <th>NOMBRE DEST.</th>
                            <!--<th>PESO TOTAL</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <select class="form-control" name="id_doc_destinatario" id="id_doc_destinatario" readonly="">
                                    <option value="">Seleccionar...</option>
                                    <option value="6" selected>RUC</option>
                                    <!--<option value="1">DNI</option>
                                    <option value="-">VARIOS- VENTAS MENORES DE S/. 700</option>
                                    <option value="4">CARNET DE EXTRANJERIA</option>
                                    <option value="7">PASAPORTE</option>
                                    <option value="A">CEDULA</option>
                                    <option value="0">NO DOMICIALIADO</option>-->
                                </select>
                            </td>
                            <td><input type="text" id="destinatario_nombre" readonly  class="form-control" value="<?= $guia->guia_destinatario_nombre;?>"></td>
                            <td><input type="text" id="destinatario_ruc" readonly class="form-control" value="<?= $guia->guia_destinatario_numero;?>"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row" id="datos_proveedor">
                <div class="col-lg-12">
                    <table class="table table-bordered table-hover" style="border-color: black">
                        <thead class="text-white font-weight-bold" style="background-color: #5D6D7E">
                        <tr >
                            <th>NUM. PROVEEDOR</th>
                            <th>NOMBRE PROVEEDOR</th>
                            <!--<th>PESO TOTAL</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <td><input type="text" id="proveedor_nombre" readonly class="form-control" value="<?= $guia->guia_proveedor_nombre;?>"></td>
                        <td><input type="text" id="proveedor_ruc" readonly class="form-control" value="<?= $guia->guia_proveedor_ruc;?>"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12" >
                    <div class="card" style="border: 1px solid #34495E; border-radius: 0%">
                        <div class="card-header" style="background: #34495E">
                            <h4 class="text-white">DATOS DEL TRASLADO</h4>
                        </div>
                        <div class="card-body">
                            <div class="row"  >
                                <div class="col-lg-2">
                                    <label for="fecha_traslado">Fecha Traslado</label>
                                    <input type="date" id="fecha_tras" class="form-control" name="fecha_tras" value="<?= date('Y-m-d', strtotime($guia->guia_fecha_traslado)) ?>" readonly>
                                </div>
                                <div class="col-lg-2">
                                    <label for="peso_bruto">Peso Bruto</label>
                                    <input type="text" id="peso_bruto" value="<?= $guia->guia_peso_bruto; ?>" onkeyup="validar_numeros_decimales_dos(this.id)" class="form-control" readonly>
                                </div>
                                <div class="col-lg-3">
                                    <label for="peso_unidad_medida">Peso - unidad de medidad</label>
                                    <select name="peso_unidad_medida" class="form-control" id="peso_unidad_medida" disabled>
                                        <option value="KGM" <?= ($guia->guia_unidad_medida == 'KGM')?'selected':''?>>KGM - KILOGRAMO</option>
                                        <option value="TNE" <?= ($guia->guia_unidad_medida == 'TNE')?'selected':''?>>TNE - TONELADA</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="numero_bultos">N° de Bultos</label>
                                    <input type="text" onkeyup="validar_numeros_decimales_dos(this.id)" value="<?= $guia->guia_n_bulto; ?>" id="numero_bultos" class="form-control" readonly>
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
                            <h4>DATOS TRANSPORTISTA</h4>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="tipo_documento_trans">Tipo Documento</label>
                                    <select class="form-control" name="tipo_documento_trans" id="tipo_documento_trans" disabled>
                                        <option value="">Seleccionar...</option>
                                        <option value="6" <?= ($guia->guia_tipo_doc == '6')?'selected':''?>>RUC</option>
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
                                    <input type="text" id="numero_doc_trans" class="form-control" value="<?= $guia->guia_num_doc; ?>" readonly>
                                </div>
                                <div class="col-lg-4">
                                    <label for="denominacion_trans">Transporte Denominación</label>
                                    <input type="text" id="denominacion_trans" onkeyup="mayuscula(this.id)" class="form-control" value="<?= $guia->guia_denominacion; ?>" readonly>
                                </div>
                                <div class="col-lg-2">
                                    <label for="num_placa_trans">Transportista placa número</label>
                                    <input type="text" id="num_placa_trans" class="form-control" value="<?= $guia->guia_placa; ?>" readonly>
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
                            <h4> DATOS DEL CONDUCTOR</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label for="tipo_documento_con">Tipo Documento</label>
                                    <select name="tipo_documento_con" id="tipo_documento_con" class="form-control" disabled>
                                        <option value="6" <?= ($guia->guia_tipo_doc == '6')?'selected':''?>>RUC</option>
                                        <option value="1" <?= ($guia->guia_tipo_doc == '1')?'selected':''?>>DNI</option>
                                        <option value="4" <?= ($guia->guia_tipo_doc == '4')?'selected':''?>>CARNET DE EXTRANJERIA</option>
                                        <option value="7" <?= ($guia->guia_tipo_doc == '7')?'selected':''?>>PASAPORTE</option>
                                        <option value="A" <?= ($guia->guia_tipo_doc == 'A')?'selected':''?>>CEDULA</option>
                                        <option value="0" <?= ($guia->guia_tipo_doc == '0')?'selected':''?>>NO DOMICIALIADO</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="numero_doc_con">Número documento</label>
                                    <input type="text" id="numero_doc_con" class="form-control" value="<?= $guia->guia_doc_cond; ?>" readonly>
                                </div>
                                <div class="col-lg-3">
                                    <label for="nombre_con">Nombre del Conductor</label>
                                    <input type="text" id="nombre_con" onkeyup="mayuscula(this.id)" name="nombre_con" class="form-control" maxlength="250" value="<?= $conductor[0]; ?>" readonly>
                                </div>
                                <div class="col-lg-3">
                                    <label for="apellido_con">Apellidos del Conductor</label>
                                    <input type="text" id="apellido_con" onkeyup="mayuscula(this.id)" name="apellido_con" class="form-control" maxlength="250" value="<?= $conductor[1]; ?>" readonly>
                                </div>
                                <div class="col-lg-2">
                                    <label for="licencia_con">Licencia de conducir</label>
                                    <input type="text" id="licencia_con" class="form-control" value="<?= $guia->guia_licencia_cond; ?>" readonly>
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
                            <h4> PUNTO PARTIDA</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="ubigeo_partida">Ubigeo dirección de partida</label>
                                    <select name="ubigeo_partida" id="ubigeo_partida" class="form-control" disabled>
                                        <?php foreach ( $ubigeo as $u ){
                                            ($u->ubigeo_cod==$guia->guia_ubigeo_par)? $select='selected' : $select='' ;
                                            ?>
                                            <option value="<?= $u->ubigeo_cod ?>" <?= $select ?> > <?= $u->ubigeo_distrito.' | '.$u->ubigeo_provincia.' | '.$u->ubigeo_departamento.' | '.$u->ubigeo_cod  ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="direccion_partida">Dirección punto de partida</label>
                                    <input type="text" id="direccion_partida" onkeyup="mayuscula(this.id)" name="direccion_partida" class="form-control" value="<?= $guia->guia_direccion_part; ?>" readonly>

                                </div>
                                <div class="col-lg-3">
                                    <label for="cod_establec_partida">Código establecimiento</label>
                                    <input type="text" id="cod_establec_partida" name="cod_establec_partida" class="form-control" placeholder="Por defecto '0000'" value="<?= $guia->guia_cod_establec_par; ?>" readonly>
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
                            <h4> PUNTO DE LLEGADA</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="ubigeo_llegada">Ubigeo dirección de partida</label>
                                    <select name="ubigeo_llegada" id="ubigeo_llegada" class="form-control" disabled>
                                        <?php foreach ( $ubigeo as $u ){
                                            ($u->ubigeo_distrito==$guia->guia_ubigeo_llega)? $select='selected' : $select='' ;
                                            ?>
                                            <option value="<?= $u->ubigeo_cod ?>" <?= $select ?> > <?= $u->ubigeo_distrito.' | '.$u->ubigeo_provincia.' | '.$u->ubigeo_departamento.' | '.$u->ubigeo_cod  ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="direccion_llegada">Dirección punto de llegada</label>
                                    <input type="text" id="direccion_llegada" onkeyup="mayuscula(this.id)" name="direccion_llegada" class="form-control" value="<?= $guia->guia_direccion_llega; ?>" readonly>

                                </div>
                                <div class="col-lg-3">
                                    <label for="cod_establec_llegada">Código establecimiento</label>
                                    <input type="text" id="cod_establec_llegada" name="cod_establec_llegada" class="form-control" placeholder="Por defecto '0000'" value="<?= $guia->guia_cod_establec_llega; ?>" readonly>
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
                            <h4>OBSERVACION</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="">Observaciones</label>
                                    <textarea class="form-control" name="observacion" id="observacion" maxlength="250" rows="1" readonly><?= $guia->guia_observacion; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

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
        valor = validar_campo_vacio('num_placa_trans',num_placa_trans, valor)
        if(tipo_trans==='02'){
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
