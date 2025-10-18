<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE
 * Date: 22/02/2024
 * Time: 11:55
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
                    <p><i class="fa fa-calendar"></i> Fecha: <br> <?php echo date('Y-m-d');?></p>
                </div>
                <!--<div class="col-lg-3 col-sm-3" style="text-align: left">
                    <p>RUC ó DNI: <br> <strong></strong></p>
                    <p>Serie y Correlativo: <strong> <br> </strong></p>
                </div>-->

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

            <!-- /.row (main row) -->
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
                                <select class="form-control" name="id_doc_destinatario" id="id_doc_destinatario">
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
                            <td><input type="text" id="destinatario_nombre" class="form-control" value="20609569752"></td>
                            <td><input type="text" id="destinatario_ruc" class="form-control" value="CRALM GROUP E.I.R.L."></td>
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
                            <td><input type="text" id="proveedor_nombre" class="form-control" value=""></td>
                            <td><input type="text" id="proveedor_ruc" class="form-control" value=""></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered table-hover" style="border-color: black">
                        <thead class="text-white font-weight-bold" style="background-color: #5D6D7E">
                        <tr >
                            <th style="width: 100px;">CANTIDAD</th>
                            <th>U. MED</th>
                            <th>DESCRIPCION</th>
                            <!--<th>PESO TOTAL</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <!--<tr>
                            <td><?php //echo $p->id_productforsale;?></td>
                            <td></td>
                            <td>UNIDAD</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>-->
                        <tr>
                            <td><input type="text" id="cant_gas" class="form-control" value="0"></td>
                            <td>GALON</td>
                            <td><input type="text" id="den_gas" class="form-control" value="GASOLINA 84 OCT"></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="cant_die" class="form-control" value="0"></td>
                            <td>GALON</td>
                            <td><input type="text" id="den_die" class="form-control" value="DIESEL B5 UV"></td>
                        </tr>
                        </tbody>
                    </table>
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

                    <a onclick="guardar_remision(1,39)" class="btn btn-success text-white"><i class="fa fa-save"></i> GUARDAR</a>
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

        $('#datos_proveedor').hide();
    })

    document.getElementById('motivo_tras').addEventListener('change', function() {
        if(this.value=='02'){
            $('#datos_proveedor').show();
            $('#datos_destinatario').hide();

            $('#id_doc_destinatario').val(6);
            $('#destinatario_nombre').val('20609569752');
            $('#destinatario_ruc').val('CRALM GROUP E.I.R.L.');
        } else {
            $('#datos_proveedor').hide();
            $('#datos_destinatario').show();
        }
    });

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
        let id_cliente = 0;
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

        let id_doc_destinatario = $('#id_doc_destinatario').val();
        let destinatario_nombre = $('#destinatario_nombre').val();
        let destinatario_ruc = $('#destinatario_ruc').val();

        let proveedor_nombre = $('#proveedor_nombre').val();
        let proveedor_ruc = $('#proveedor_ruc').val();

        let cant_gas = $('#cant_gas').val();
        cant_gas = cant_gas * 1;
        let den_gas = $('#den_gas').val();
        let cant_die = $('#cant_die').val();
        cant_die = cant_die * 1;
        let den_die = $('#den_die').val();

        valor = validar_campo_vacio('id_doc_destinatario',id_doc_destinatario, valor)
        valor = validar_campo_vacio('destinatario_nombre',destinatario_nombre, valor)
        valor = validar_campo_vacio('destinatario_ruc',destinatario_ruc, valor)

        valor = validar_campo_vacio('motivo_tras',motivo_tras, valor)
        valor = validar_campo_vacio('tipo_trans',tipo_trans, valor)
        valor = validar_campo_vacio('peso_bruto',peso_bruto, valor)
        valor = validar_campo_vacio('peso_unidad_medida',peso_unidad_medida, valor)
        valor = validar_campo_vacio('numero_bultos',numero_bultos, valor)
        valor = validar_campo_vacio('fecha_tras',fecha_tras, valor)
        valor = validar_campo_vacio('tipo_documento_trans',tipo_documento_trans, valor)
        valor = validar_campo_vacio('numero_doc_trans',numero_doc_trans, valor)
        valor = validar_campo_vacio('denominacion_trans',denominacion_trans, valor)
        valor = validar_campo_vacio('den_gas',den_gas, valor)
        valor = validar_campo_vacio('den_die',den_die, valor)

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

        if(cant_gas < 0 && cant_die < 0){
            valor = false;
            respuesta('¡No existen items!', 'error');
        }
        //valor = false
        if (valor){
            $.ajax({
                type: "POST",
                url: urlweb + "api/Ventas/guardar_guia",
                data: {
                    'venta': 1,
                    'id_cliente': 0,
                    'caja':39,
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
                    'cantidad_gas': cant_gas ,
                    'denominacion_gas': den_gas ,
                    'cantidad_die': cant_die ,
                    'denominacion_die': den_die ,
                    'id_doc_destinatario': id_doc_destinatario ,
                    'destinatario_nombre': destinatario_nombre ,
                    'destinatario_ruc': destinatario_ruc ,
                    'proveedor_nombre': proveedor_nombre ,
                    'proveedor_ruc': proveedor_ruc ,
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
                            respuesta('¡Ocurrió Un Error, Contacte con Soporte!', 'error');
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

