<hr><h2 class="concss">
    <a href="<?= _SERVER_?>"><i class="fa fa-fire"></i> INICIO</a> >
    <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
    <a class="button" href="<?= _SERVER_?>Proforma/ver_proforma"><i class="fa fa-paper-plane"></i> Ver Proformas</a>
</h2><hr>

<!--Modal para Productos-->
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 80% !important;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Listado de Productos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <table id="dataTable2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 200px;">Producto</th>
                            <th style="width: 100px;">Talla / Medida</th>
                            <th style="width: 100px;">Tipo</th>
                            <th>Stock</th>
                            <th style="width: 100px;">Código</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>SubTotal</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $num = 0;
                        foreach ($productos as $p){
                            $productnamefull = $p->producto_nombre;
                            $jalar_tallas = $this->ventas->jalar_tallas_producto($p->id_producto);
                            ?>
                            <tr>
                                <input type="hidden" id="datos_ta<?= $num?>">
                                <input type="hidden" id="datos_pp<?= $num?>">
                                <td><?php echo $productnamefull;?></td>
                                <td>
                                    <select class="form-control" onclick="jalar_datos_talla(<?= $num ?>)" name="id_talla<?= $num ?>" id="id_talla<?= $num ?>">
                                        <option value="">Elija</option>
                                        <?php
                                        foreach ($jalar_tallas as $e){
                                            ?>
                                            <option value="<?= $e->id_talla;?>"><?= $e->talla_nombre;?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" onchange="escoger_t(<?= $num;?>)" name="tipo_mayor_menor<?= $num;?>" id="tipo_mayor_menor<?= $num;?>">
                                        <option value="1">Por Menor</option>
                                        <option value="2">Por Mayor</option>
                                    </select>
                                </td>
                                <td><span id="datos_talla<?= $num ?>"></span></td>
                                <td><span id="datos_cod_barra<?= $num ?>"></span></td>
                                <td style="display: flex; flex-direction: row">S/. <span id="datos_precio<?= $num ?>"></span></td>


                                <td><input type="text" class="form-control" onchange="onchangeund_nuevo(<?= $num?>)" style="width: 70px;" id="total_product<?= $num;?>" value="1"></td>
                                <td style="display: flex; flex-direction: row"><span style="padding: 3px">S/.</span><input type="text" class="form-control" onchange="onchangetotalprice_nuevo(<?= $num?>)"  style="width: 80px;" id="total_price<?= $num;?>" onkeypress="return valida(event)" value="">
                                    <input type="hidden" id="tipo_igv<?= $num;?>" name="tipo_igv<?= $num;?>" value="<?= $p->producto_precio_codigoafectacion;?>">
                                </td>
                                <td><button class="btn btn-success btn-xs" type="button" onclick="agregarProducto_nuevo_(<?php echo $num;?>,'<?php echo $productnamefull;?>')"><i class="fa fa-check-circle"></i></button></td>
                            </tr>
                            <?php
                            $num++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--Modal para Clientes-->
<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 80% !important;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Clientes Registrados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <a style="float: right;" href="<?php echo _SERVER_;?>Clientes/agregar" class="btn btn-success"><i class="fa fa-pencil"></i> Cliente Nuevo</a>
                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                        <thead class="text-capitalize">
                        <tr>
                            <th>Nombre</th>
                            <th>DNI ó RUC </th>
                            <th>Dirección</th>
                            <th>Telefono</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $a = 1;
                        foreach ($clientes as $m){
                            ?>
                            <tr>
                                <td><?php echo $m->cliente_nombre.$m->cliente_razonsocial;?></td>
                                <td><?php echo $m->cliente_numero;?></td>
                                <td><?php echo $m->cliente_direccion;?></td>
                                <td><?php echo $m->cliente_telefono;?></td>
                                <td><button type="button" class="btn btn-xs btn-success btne" onclick="agregarPersona('<?php echo $m->cliente_nombre.$m->cliente_razonsocial;?>','<?php echo $m->cliente_numero;?>','<?php echo $m->cliente_direccion;?>','<?php echo $m->cliente_telefono;?>','<?= $m->id_tipodocumento;?>')" ><i class="fa fa-check-circle"></i> Elegir Cliente</button></td>
                            </tr>
                            <?php
                            $a++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!--<section class="container-fluid">
        <h1>
            <?php echo $_SESSION['controlador'];?> /
            <small><?php echo $_SESSION['accion'];?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a></li>
        </ol>
    </section>-->
    <section class="content" style="background-color: #ffffff; box-shadow: 10px 10px 5px #888888;border-radius: 30px; padding: 15px; margin: 50px; min-height: 500px">
        <div class="row">
            <div class="col-lg-12">
                <h3 style="text-align: center">PROFORMA DE VENTA</h3>
            </div>
        </div><br>

        <div class="row">
            <div class="col-lg-12" style="text-align: center">
                <h5><strong>Datos del Cliente</strong></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2">
            </div>
            <div class="col-lg-2">
                <label>Tipo de Pago</label>
                <select class="form-control" id="id_tipo_pago" name="id_tipo_pago">
                    <?php
                    foreach ($tipo_pago as $tp){
                        ?>
                        <option <?php echo ($tp->id_tipo_pago == 3) ? 'selected' : '';?> value="<?php echo $tp->id_tipo_pago;?>"><?php echo $tp->tipo_pago_nombre;?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-3">
                <label>Tipo Documento</label>
                <select  class="form-control" name="select_tipodocumento" id="select_tipodocumento">
                    <option value="">Seleccionar...</option>
                    <?php
                    foreach ($tipos_documento as $td){
                        ($td->id_tipodocumento == 2)?$sele='selected':$sele='';
                        echo "<option value='".$td->id_tipodocumento."' ".$sele.">".$td->tipodocumento_identidad."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-2" style="margin-top: 8px">
                <br>
                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#basicModal" style="width: 100%"><i class="fa fa-search"></i> Buscar Cliente</button>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-2">
                <label for="client_number">DNI ó RUC:</label>
                <input class="form-control" type="text" id="client_number" value="11111111" onchange="consultar_documento(this.value)">
            </div>
            <div class="col-lg-5">
                <label for="client_name">Nombre:</label>
                <input class="form-control" type="text" id="client_name" value="ANONIMO" placeholder="Ingrese Nombre...">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-5">
                <label for="client_address">Direccion:</label>
                <textarea class="form-control" name="client_address" id="client_address"  rows="2" placeholder="Ingrese Dirección..."></textarea>
            </div>
            <div class="col-lg-2">
                <label for="client_telefono">Telefono:</label>
                <input class="form-control" type="text" id="client_telefono" placeholder="Ingrese telefono...">
            </div>

        </div><hr>
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-4">
                <label for="">Nota : </label>
                <textarea class="form-control" name="proforma_nota" id="proforma_nota" cols="30" rows="3"></textarea>
            </div>
            <div class="col-lg-2">
                <label for="">Fecha de Vigencia</label>
                <input type="date" class="form-control" id="proforma_fecha_vigencia" name="proforma_fecha_vigencia" min="<?= date('Y-m-d')?>">
            </div>
            <div></div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-3">
                <button style="width: 100%; padding: 1.2rem; font-size: 1.5rem" class="btn btn-success" type="button" data-toggle="modal" data-target="#largeModal"><i class="fa fa-search"></i> Buscar Producto</button>
            </div>
            <div class="col-lg-1" style="text-align: center; font-size: 3rem;"> Ó </div>
            <div class="col-lg-4">
                <label for="client_address">Código de Barra:</label>
                <input class="form-control" type="text" id="product_barcode" onchange="buscar_producto_barcode()">
            </div>
            <div class="col-lg-2">
            <div class="form-group">
                <label for="tipo_moneda">Moneda</label><br>
                <select class="form-control" id="tipo_moneda" name="tipo_moneda">
                    <option value="1">SOLES</option>
                    <option value="2">DOLARES</option>
                </select>
            </div>
        </div>
        </div><hr>

        <div id="general">
            <div class="row" id="busqueda">
                <div class="col-lg-12" style="text-align: center; font-size: 1.5rem">BÚSQUEDA DE PRODUCTOS - CÓDIGO DE BARRA</div>
            </div><br>

            <div class="row" id="detalle">
                <div class="col-lg-1"></div>
                <div class="col-lg-5">
                    <label for="client_address">Nombre Producto:</label>
                    <input class="form-control" type="text" id="product_nameb" readonly>
                </div>
                <div class="col-lg-3">
                    <label for="client_address">Cód. Producto:</label>
                    <input class="form-control" type="text" id="id_productforsaleb" readonly>
                </div>
                <div class="col-lg-2">
                    <label for="client_address">Stock:</label>
                    <input class="form-control" type="text" id="product_stockb" readonly>
                </div>
            </div><br>

            <div class="row" id="detalle_">
                <div class="col-lg-1"></div>
                <div class="col-lg-2">
                    <input type="hidden" id="id_talla_pro" name="id_talla_pro">
                    <label for="client_address">Tipo:</label>
                    <select class="form-control" onchange="escoger_tp()" name="tipo_mayor_menor_b" id="tipo_mayor_menor_b">
                        <option value="1">Por Menor</option>
                        <option value="2">Por Mayor</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="client_address">Cantidad:</label>
                    <input class="form-control" type="text" id="product_cantb" onchange="onchangeundZ()" value="1" onkeypress="return valida(event);">
                </div>
                <div class="col-lg-2">
                    <label for="client_address">Precio(S/.):</label><br>
                    <input class="form-control" type="text"  onchange="onchangeundpriceZ()" id="product_priceb">
                </div>
                <div class="col-lg-2">
                    <label for="client_address">Total(S/.):</label><br>
                    <input class="form-control" type="text" id="product_totalb" onchange="onchangetotalpriceZ()">
                </div>
                <div class="col-lg-2">
                    <br>
                    <button style="margin-top: 8px; width: 100%" class="btn btn-primary" type="button" onclick="agregarProductoZ()" ><i class="fa fa-plus"></i> Agregar Producto</button>
                </div>
            </div><hr>
        </div>
        <div style="display: none" class="row">
            <div class="col-lg-1"></div>
            <div id="credito_debito">
                <div class="col-lg-3">
                    <label>Documento a modificar</label>
                    <select name="" class="form-control" id="Tipo_documento_modificar">
                        <option value="03">BOLETA</option>
                        <option value="01">FACTURA</option>
                    </select>
                </div>
                <div class="col-lg-3" id="SerieyNumero">
                    <label>Serie y numero</label>
                    <input class="form-control" type="text" id="serie_numero_modifiar" >
                </div>
                <div class="col-lg-4" id="notaCredito">
                    <label>Tipo Nota de Crédito</label>
                    <select name="" class="form-control" id="TipoNotaCredito">
                        <option>Seleccionar...</option>
                        <?php
                        foreach ($tiponotacredito as $nc){
                            ?>
                            <option value="<?= $nc->id_tipo_ncreditos; ?>"><?= $nc->tipo_ncreditos_nota_descripcion; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class = "col-lg-4" id="notaDebito">
                    <label>Tipo Nota de Débito</label>
                    <select name="" class="form-control" id="TipoNotaDebito">
                        <option>Seleccionar...</option>
                        <?php
                        foreach ($tiponotadebito as $nd){
                            ?>
                            <option value="<?= $nd->id_tipo_ndebitos; ?>"><?= $nd->tipo_ndebitos_nota_descripcion; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-1"></div>
            </div>
        </div>

        <div id="tabla_proforma"></div><br>

        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <button type="button" id="btn_generarproforma" class="btn font-weight-bold" style="width: 100%; padding: 1.2rem; font-size: 1.5rem; width: 100%; background-color: orangered; color: #FFFFFF;" onclick="preguntar('¿Está seguro que desea realizar esta Proforma?','realizar_proforma','Si','No')">
                    <i class="fa fa-money"></i> GENERAR PROFORMA</button>
            </div>
        </div>
    </section>
</div>


<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>proforma.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#tabla_proforma').load('<?php echo _SERVER_;?>Proforma/tabla_proforma');
        $("#product_barcode").focus();
        $("#credito_debito").hide();
        $("#detalle").hide();
        $("#detalle_").hide();
        $("#busqueda").hide();
        $("#general").hide();
    });
    var productfull = "";
    var productfull_ = "";
    var productfull_nombre_talla = "";
    var unid = "";

    function jalar_datos_talla(num){
        var id_talla = $('#id_talla'+num).val();
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/jalar_datos_talla",
            data: "id_talla="+id_talla + "&num=" + num,
            dataType: 'json',
            success:function (r) {
                $("#datos_talla"+num).html(r.datos_stock);
                $("#datos_cod_barra"+num).html(r.cod_barra);
                $("#datos_precio"+num).html(r.precio);
                $("#datos_ta"+num).val(r.datos_ta);
                $("#datos_pp"+num).val(r.datos_pp);
                onchangeundprice_nuevo(num);
            }
        });
    }

    function onchangeundprice_nuevo(num) {
        var cant = $("#total_product"+num).val();
        var precio = $("#producto_precio_valor"+num).val();
        var subtotal = cant * precio;
        subtotal.toFixed(2);
        subtotal = parseFloat(subtotal);
        $("#total_price"+num).val(subtotal);
    }

    function onchangetotalprice_nuevo(num) {
        var subtotal = $("#producto_precio_valor"+num).val();
        var cant = $("#total_product"+num).val();
        var precio = subtotal / cant;
        precio.toFixed(2);
        $("#producto_precio_valor"+num).val(precio);
    }

    function onchangeund_nuevo(num) {
        var cant = $("#total_product"+num).val();
        var precio = $("#producto_precio_valor"+num).val();
        var subtotal = cant * precio;
        subtotal.toFixed(2);
        $("#total_price"+num).val(subtotal);
    }

    function selecttipoventa(valor){
        if (valor == "07" || valor == "08"){
            $('#credito_debito').show();

            if(valor == "07"){
                $('#notaCredito').show();
                $('#notaDebito').hide();
            }else{
                $('#notaCredito').hide();
                $('#notaDebito').show();
            }
        } else{
            $('#credito_debito').hide();
        }
    }

    function escoger_t(num){
        var tipo_mayor_menor = $("#tipo_mayor_menor"+num).val();
        var id_tallita = $("#id_talla"+num).val();
        console.log(tipo_mayor_menor);
        console.log(id_tallita);
        $.ajax({
            type: "POST",
            url: urlweb + "api/Proforma/jalar_venta_mm",
            data: "num="+num + "&id_tallita=" + id_tallita +
                "&tipo_mayor_menor="+tipo_mayor_menor,
            dataType: 'json',
            beforeSend: function (){

            },
            success:function (r) {
                $("#datos_precio"+num).html(r);
                onchangeundprice_nuevo(num);
            }
        });
    }

    function escoger_tp(){
        var tipo_mayor_menor = $("#tipo_mayor_menor_b").val();
        var id_tallita = $("#id_talla_pro").val();
        $.ajax({
            type: "POST",
            url: urlweb + "api/Proforma/jalar_venta_mm_",
            data: "id_tallita=" + id_tallita +
                "&tipo_mayor_menor="+tipo_mayor_menor,
            dataType: 'json',
            beforeSend: function (){

            },
            success:function (r) {
                $("#product_priceb").val(r);
                onchangeundpriceZ();
            }
        });
    }

    function agregarProducto_nuevo_(num, producto){
        var cant = $("#total_product"+num).val() * 1;
        var precio = $("#producto_precio_valor"+num).val() * 1;
        var product_descuento = $("#product_descuento"+num).val() * 1;
        var tipo_igv = $("#datos_ta"+num).val() * 1;
        var datos_pp = $("#datos_pp"+num).val() * 1;
        var id_talla = $("#id_talla"+num).val();
        var tipo_mayor_menor = $("#tipo_mayor_menor"+num).val();
        var nombre = $("#id_talla"+num+" option:selected").text();
        var nombre_completo = producto+" / "+ nombre;
        var cadena = "producto=" + nombre_completo +
            "&num=" + num +
            "&id_talla=" + id_talla +
            "&nombre=" + nombre+
            "&precio=" + precio +
            "&product_descuento=" + product_descuento +
            "&tipo_igv=" + tipo_igv +
            "&datos_pp=" + datos_pp +
            "&tipo_mayor_menor=" + tipo_mayor_menor +
            "&cantidad=" + cant;
        /*if(stock >= cant){*/
        $.ajax({
            type:"POST",
            url: urlweb + "api/Proforma/addproduct",
            data : cadena,
            success:function (r) {
                switch (r) {
                    case "1":
                        respuesta('¡Producto Agregado!', 'success');
                        $('#tabla_proforma').load(urlweb + 'Proforma/tabla_proforma');
                        break;
                    case "2":
                        respuesta('Hubo Un Error');
                        break;
                    case "3":
                        respuesta('El Producto YA ESTA AGREGADO', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
        /*} else {
           respuesta('NO HAY STOCK DISPONIBLE','error');
        }*/

    }


    function recargar_productos() {
        $('#tabla_proforma').load('<?php echo _SERVER_;?>Proforma/tabla_proforma');
    }

    <?php
    foreach ($productos as $p){
    ?>
    function agregarProducto_<?php echo $p->id_producto_precio;?>(cod, producto, unids, stock) {
        var cant = $("#total_product<?php echo $p->id_producto_precio;?>").val() * 1;
        var precio = $("#product_price<?php echo $p->id_producto_precio;?>").val() * 1;
        var tipo_mayor_menor = $("#tipo_mayor_menor<?php echo $p->id_producto_precio;?>").val();
        //var tipo_igv = $("#tipo_igv<?php echo $p->id_producto_precio;?>").val() * 1;
        var cadena = "codigo=" + cod +
            "&producto=" + producto +
            "&unids=" + unids +
            "&precio=" + precio +
            "&tipo_mayor_menor=" + tipo_mayor_menor +
            "&cantidad=" + cant;
        if(stock >= cant){
            $.ajax({
                type:"POST",
                url: urlweb + "api/Proforma/addproduct",
                data : cadena,
                success:function (r) {
                    switch (r) {
                        case "1":
                            respuesta('¡Producto Agregado!', 'success');
                            $('#tabla_proforma').load(urlweb + 'Proforma/tabla_proforma');
                            break;
                        case "2":
                            respuesta('Hubo Un Error','error');
                            break;
                        case "3":
                            respuesta('El Producto YA ESTA AGREGADO','error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        } else {
            respuesta('NO HAY STOCK DISPONIBLE','error');
        }
    }

    function onchangeund<?php echo $p->id_producto_precio;?>() {
        var cant = $("#total_product<?php echo $p->id_producto_precio;?>").val();
        var precio = $("#product_price<?php echo $p->id_producto_precio;?>").val();
        var subtotal = cant * precio;
        subtotal.toFixed(2);
        $("#total_price<?php echo $p->id_producto_precio;?>").val(subtotal);
    }

    function onchangeundprice<?php echo $p->id_producto_precio;?>() {
        var cant = $("#total_product<?php echo $p->id_producto_precio;?>").val();
        var precio = $("#product_price<?php echo $p->id_producto_precio;?>").val();
        var subtotal = cant * precio;
        subtotal.toFixed(2);
        subtotal = parseFloat(subtotal);
        $("#total_price<?php echo $p->id_producto_precio;?>").val(subtotal);
    }

    function onchangetotalprice<?php echo $p->id_producto_precio;?>() {
        var subtotal = $("#total_price<?php echo $p->id_producto_precio;?>").val();
        var cant = $("#total_product<?php echo $p->id_producto_precio;?>").val();
        var precio = subtotal / cant;
        precio.toFixed(2);
        $("#product_price<?php echo $p->id_producto_precio;?>").val(precio);
    }

    function escoger_<?php echo $p->id_producto_precio;?>(id_productforsaleb){
        var tipo_mayor_menor = $("#tipo_mayor_menor<?php echo $p->id_producto_precio;?>").val();

        $.ajax({
            type: "POST",
            url: urlweb + "api/Proforma/jalar_venta_mm",
            data: "id_productforsaleb="+id_productforsaleb +
                "&tipo_mayor_menor="+tipo_mayor_menor,
            dataType: 'json',
            success:function (r) {
                $("#product_price<?php echo $p->id_producto_precio;?>").val(r);
                onchangeundprice<?php echo $p->id_producto_precio;?>();
            }
        });
    }

    <?php
    }
    ?>

    function agregarPersona(nombre, numero, direccion, telefono,id_tipodocumento) {
        $("#client_number").val(numero);
        $("#client_name").val(nombre);
        $("#client_address").val(direccion);
        $("#client_telefono").val(telefono);
        $("#select_tipodocumento").val(id_tipodocumento);
        respuesta('El cliente se agregó correctamente!','success');

    }

    function onchangeundZ() {
        var cant = $("#product_cantb").val();
        var precio = $("#product_priceb").val();
        var subtotal = cant * precio;
        subtotal.toFixed(2);
        $("#product_totalb").val(subtotal);
    }

    function onchangeundpriceZ() {
        var cant = $("#product_cantb").val();
        var precio = $("#product_priceb").val();
        var subtotal = cant * precio;
        subtotal.toFixed(2);
        subtotal = parseFloat(subtotal);
        $("#product_totalb").val(subtotal);
    }

    function onchangetotalpriceZ() {
        var subtotal = $("#product_totalb").val();
        var cant = $("#product_cantb").val();
        var precio = subtotal / cant;
        precio.toFixed(2);
        $("#product_priceb").val(precio);
    }

    function buscar_producto_barcode() {
        var valor = "correcto";
        var product_barcode = $('#product_barcode').val();
        if(product_barcode == ""){
            respuesta('El campo Código de Barra está vacío','error');
            $('#product_barcode').css('border','solid red');
            valor = "incorrecto";
        } else {
            $('#product_barcode').css('border','');
        }

        if (valor == "correcto"){
            var cadena = "product_barcode=" + product_barcode;
            $.ajax({
                type:"POST",
                url: urlweb + "api/Proforma/search_by_barcode",
                data: cadena,
                success:function (r) {
                    if(r=="2"){
                        respuesta("ERROR O PRODUCTO NO REGISTRADO",'error');
                        $('#product_nameb').val('');
                        $('#id_productforsaleb').val('');
                        $('#product_stockb').val('');
                        $('#product_priceb').val('');
                        $('#product_totalb').val('');
                        $('#product_cantb').val(1);
                        productfull = "";
                        productfull_ = "";
                        productfull_nombre_talla = "";
                        unid = "";
                    } else {
                        var productoinfo = r.split('|');
                        var fullproductname = productoinfo[0]+" / "+productoinfo[1];
                        productfull = fullproductname;
                        productfull_ = productoinfo[0];
                        productfull_nombre_talla = productoinfo[1];
                        unid =  productoinfo[1];
                        $('#product_nameb').val(fullproductname);
                        $('#id_productforsaleb').val(productoinfo[3]);
                        $('#product_stockb').val(productoinfo[2]);
                        $('#product_priceb').val(productoinfo[4]);
                        $('#product_totalb').val(productoinfo[4]);
                        $('#product_cantb').val(1);
                        $('#codigo_afectacion').val(productoinfo[6]);
                        $('#id_talla_pro').val(productoinfo[7]);

                        $("#busqueda").show();
                        $("#detalle").show();
                        $("#detalle_").show();
                        $("#general").show();
                        respuesta('PRODUCTO ENCONTRADO','success');
                    }
                }
            });
        }
    }

    function agregarProductoZ() {
        var cod = $('#id_productforsaleb').val();
        var cant = $("#product_cantb").val() * 1;
        var precio = $("#product_priceb").val() * 1;
        var stock = $("#product_stockb").val() * 1;
        var tipo_igv = $("#tipo_igv").val();
        //var product_descuento = $("#product_descuento").val() * 1;
        var tipo_mayor_menor = $("#tipo_mayor_menor_b").val();
        var id_talla = $("#id_talla_pro").val();
        var cadena = "datos_pp=" + cod +
            "&producto=" + productfull +
            "&nombre=" + productfull_nombre_talla +
            "&unids=" + unid +
            "&precio=" + precio +
            "&cantidad=" + cant +
            //"&product_descuento=" + product_descuento +
            "&tipo_mayor_menor=" + tipo_mayor_menor +
            "&tipo_igv=" + tipo_igv +
            "&id_talla=" + id_talla;

        if(stock >= cant){
            $.ajax({
                type:"POST",
                url: urlweb + "api/Proforma/addproduct",
                data : cadena,
                success:function (r) {
                    switch (r) {
                        case "1":
                            respuesta('Producto Agregado','success');
                            $('#tabla_proforma').load(urlweb + 'Proforma/tabla_proforma');
                            $('#product_nameb').val('');
                            $('#id_productforsaleb').val('');
                            $('#product_stockb').val('');
                            $('#product_priceb').val('');
                            $('#product_totalb').val('');
                            $('#product_barcode').val('');
                            productfull = "";
                            unid = "";
                            $("#product_barcode").focus();
                            break;
                        case "2":
                            respuesta('Hubo Un Error','error');
                            break;
                        case "3":
                            respuesta('El Producto YA ESTA AGREGADO','success');
                            break;
                        default:
                            respuesta('Hubo Un Error','error');
                            break;
                    }
                }
            });
        } else {
            respuesta('NO HAY STOCK DISPONIBLE','error');
        }
    }

    function consultar_documento(valor){
        var tipo_doc = $('#select_tipodocumento').val();
        if(tipo_doc == "2"){
            ObtenerDatosDni(valor);
        }else if(tipo_doc == "4"){
            ObtenerDatosRuc(valor);
        }
    }

    function ObtenerDatosDni(valor){
        var numero_dni =  valor;

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/obtener_datos_x_dni",
            data: "numero_dni="+numero_dni,
            dataType: 'json',
            success:function (r) {
                $("#client_name").val(r.result.name+ ' ' + r.result.first_name+ ' ' + r.result.last_name);
            }
        });
    }

    function ObtenerDatosRuc(valor){
        var numero_ruc =  valor;

        $.ajax({
            type: "POST",
            url: urlweb + "api/Clientes/obtener_datos_x_ruc",
            data: "numero_ruc="+numero_ruc,
            dataType: 'json',
            success:function (r) {
                $("#client_name").val(r.result.razon_social);
            }
        });
    }

    function escoger(){
        var id_productforsaleb = $("#id_productforsaleb").val();
        var tipo_mayor_menor = $("#tipo_mayor_menor").val();
        $.ajax({
            type: "POST",
            url: urlweb + "api/Proforma/jalar_venta_mm",
            data: "id_productforsaleb="+id_productforsaleb +
            "&tipo_mayor_menor="+tipo_mayor_menor,
            dataType: 'json',
            success:function (r) {
                $("#product_priceb").val(r);
                onchangeundpriceZ();
                //$("#conversion").html(r.conversion);
            }
        });
    }


    function editar_texto_tabla(id){
        var texto_descripcion = $("#texto_descripcion").val();

        var cadena = "texto_descripcion=" + texto_descripcion + "&id=" + id;
        $.ajax({
            type:"POST",
            url: urlweb + "api/Proforma/editar_descripcion",
            data : cadena,
            success:function (r) {
                switch (r) {
                    case "1":
                        respuesta('¡Editado correctamente!', 'success');
                        $('#tabla_proforma').load(urlweb + 'Proforma/tabla_proforma');
                        break;
                    case "2":
                        respuesta('Hubo Un Error al editar');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }


</script>
