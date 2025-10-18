<hr><h2 class="concss">
    <a href="http://localhost/fire"><i class="fa fa-fire"></i> INICIO</a> >
    <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
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
                    <table id="example3" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 200px;">Producto</th>
                            <th>Talla / Medida</th>
                            <th>Stock</th>
                            <th style="width: 100px;">Código</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>SubTotal</th>
                            <th>Descuento</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($productos as $p){
                            $productnamefull = $p->producto_nombre;
                            ?>
                            <tr>
                                <td><?php echo $productnamefull;?></td>
                                <td><?php echo $p->producto_stock;?></td>
                                <td>S/. <input type="text" class="form-control" onchange="onchangeundprice<?php echo $p->id_producto_precio;?>()"  style="width: 80px;" onkeypress="return valida(event)" id="product_price<?php echo $p->id_producto_precio;?>" value="<?php echo $p->producto_precio_valor;?>"> </td>
                                <td><input type="text" class="form-control" onchange="onchangeund<?php echo $p->id_producto_precio;?>()" style="width: 70px;" id="total_product<?php echo $p->id_producto_precio;?>" onkeypress="return valida(event)" value="1"></td>
                                <td>S/. <input type="text" class="form-control" onchange="onchangetotalprice<?php echo $p->id_producto_precio;?>()"  style="width: 80px;" id="total_price<?php echo $p->id_producto_precio;?>" onkeypress="return valida(event)" value="<?php echo $p->producto_precio_valor;?>"></td>
                                <td><?php $igv = $this->ventas->listAllIgv(); ?>
                                    <select class="form-control" id="tipo_igv<?php echo $p->id_producto_precio;?>" style="background: whitesmoke; color: #000000; width: 200px" >
                                        <?php
                                        foreach ($igv as $ig){
                                            ?>
                                            <option <?php echo ($ig->id_igv == 3) ? 'selected' : '';?> value="<?php echo $ig->id_igv;?>"><?php echo $ig->igv_tipodeafectacion;?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td><button class="btn btn-success btn-xs" type="button" onclick="agregarProducto<?php echo $p->id_producto_precio;?>(<?php echo $p->id_producto_precio;?>, '<?php echo $productnamefull;?>',<?php echo $p->id_medida;?>,<?php echo $p->producto_stock;?>)"><i class="fa fa-check-circle"></i> Elegir Producto</button></td>
                            </tr>
                            <?php
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
                                <td><button type="button" class="btn btn-xs btn-success btne" onclick="agregarPersona('<?php echo $m->cliente_nombre.$m->cliente_razonsocial;?>','<?php echo $m->cliente_numero;?>','<?php echo $m->cliente_direccion;?>')" ><i class="fa fa-check-circle"></i> Elegir Cliente</button></td>
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
    <section class="content" style="background-color: #ffffff; box-shadow: 10px 10px 5px #888888;border-radius: 30px; padding: 15px; margin: 50px; min-height: 500px">
        <div class="row">
            <div class="col-lg-12">
                <h3 style="text-align: center">PROFORMA DE VENTA</h3>
            </div>
        </div><br>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-2">
                <label for="client_number">DNI ó RUC:</label>
                <input class="form-control" type="text" id="client_number" readonly value="11111111">
            </div>
            <div class="col-lg-3">
                <label for="client_name">Nombre:</label>
                <input class="form-control" type="text" id="client_name" readonly>
            </div>
            <div class="col-lg-3">
                <label for="client_address">Direccion:</label>
                <input class="form-control" type="text" id="client_address" readonly>
            </div>
            <div class="col-lg-2" style="margin-top: 8px">
                <br>
                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#basicModal" style="width: 100%"><i class="fa fa-search"></i> Buscar Cliente</button>
            </div>
        </div><hr>

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
                <br>
                <button style="margin-top: 8px; width: 100%" class="btn btn-success" type="button" onclick="buscar_producto_barcode()" ><i class="fa fa-search"></i> Buscar Código</button>
            </div>
        </div><hr>

        <div class="row">
            <div class="col-lg-12" style="text-align: center; font-size: 1.5rem">BÚSQUEDA DE PRODUCTOS - CÓDIGO DE BARRA</div>
        </div><br>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-4">
                <label for="client_address">Nombre Producto:</label>
                <input class="form-control" type="text" id="product_nameb" readonly>
            </div>
            <div class="col-lg-3">
                <label for="client_address">Cód. Producto:</label>
                <input class="form-control" type="text" id="id_productforsaleb" readonly>
            </div>
            <div class="col-lg-3">
                <label for="tipo_igv">Tipo de IGV</label><br>

                <select class="form-control" id="tipo_igv" style="background: whitesmoke; color: #000000" disabled>
                    <?php
                    foreach ($igv as $ig){
                        ?>
                        <option <?php echo ($ig->id_igv == 3) ? 'selected' : '';?> value="<?php echo $ig->id_igv;?>"><?php echo $ig->igv_tipodeafectacion;?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div><br>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-2">
                <label for="client_address">Stock:</label>
                <input class="form-control" type="text" id="product_stockb" readonly>
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

        <div id="tabla_proforma"> </div><br>

        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <button type="button" id="btn_generarproforma" class="btn" style="background-color: orangered; color: #FFFFFF; width: 100%; padding: 1.2rem; font-size: 1.5rem; width: 100%" onclick="preguntar('¿Está seguro que desea realizar esta PROFORMA?','proforma','Si','No')">
                    <i class="fa fa-money"></i> GENERAR PROFORMA</button>
            </div>
        </div>
    </section>

    <script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
    <script src="<?php echo _SERVER_ . _JS_;?>proforma.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#tabla_productos').load('<?php echo _SERVER_;?>Ventas/tabla_productos');
            $("#product_barcode").focus();
            $("#credito_debito").hide();
        });
        var productfull = "";
        var unid = "";

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

        function calcular_vuelto(valor){
            var monto_cliente = valor;
            var monto_total = $('#montototal').val();
            var vuelto_sin = monto_cliente - monto_total;
            vuelto_sin.toFixed(2);
            $('#pago_con').html(monto_cliente);
            $('#pago_con_').val(monto_cliente);
            $('#vuelto').html(vuelto_sin);
            $('#vuelto_').val(vuelto_sin);
        }

        function recargar_productos() {
            $('#tabla_productos').load('<?php echo _SERVER_;?>Ventas/tabla_productos');
        }

        <?php
        foreach ($productos as $p){
        ?>
        function agregarProducto<?php echo $p->id_producto_precio;?>(cod, producto, unids, stock) {
            var cant = $("#total_product<?php echo $p->id_producto_precio;?>").val() * 1;
            var precio = $("#product_price<?php echo $p->id_producto_precio;?>").val() * 1;
            var tipo_igv = $("#tipo_igv<?php echo $p->id_producto_precio;?>").val() * 1;
            var cadena = "codigo=" + cod +
                "&producto=" + producto +
                "&unids=" + unids +
                "&precio=" + precio +
                "&tipo_igv=" + tipo_igv +
                "&cantidad=" + cant;
            if(stock >= cant){
                $.ajax({
                    type:"POST",
                    url: urlweb + "api/Ventas/addproduct",
                    data : cadena,
                    success:function (r) {
                        switch (r) {
                            case "1":
                                respuesta('¡Producto Agregado!', 'success');
                                $('#tabla_productos').load(urlweb + 'Ventas/tabla_productos');
                                break;
                            case "2":
                                respuesta('Hubo Un Error');
                                break;
                            case "3":
                                respuesta('El Producto YA ESTA AGREGADO');
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
        <?php
        }
        ?>

        function agregarPersona(nombre, numero, direccion) {
            $("#client_number").val(numero);
            $("#client_name").val(nombre);
            $("#client_address").val(direccion);
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
                alertify.error('El campo Código de Barra está vacío');
                $('#product_barcode').css('border','solid red');
                valor = "incorrecto";
            } else {
                $('#product_barcode').css('border','');
            }

            if (valor == "correcto"){
                var cadena = "product_barcode=" + product_barcode;
                $.ajax({
                    type:"POST",
                    url: urlweb + "api/Ventas/search_by_barcode",
                    data: cadena,
                    success:function (r) {
                        if(r=="2"){
                            alertify.error("ERROR O PRODUCTO NO REGISTRADO");
                            $('#product_nameb').val('');
                            $('#id_productforsaleb').val('');
                            $('#product_stockb').val('');
                            $('#product_priceb').val('');
                            $('#product_totalb').val('');
                            $('#product_cantb').val(1);
                            productfull = "";
                            unid = "";
                        } else {
                            var productoinfo = r.split('|');
                            var fullproductname = productoinfo[0];
                            productfull = fullproductname;
                            unid =  productoinfo[1];
                            $('#product_nameb').val(fullproductname);
                            $('#id_productforsaleb').val(productoinfo[3]);
                            $('#product_stockb').val(productoinfo[2]);
                            $('#product_priceb').val(productoinfo[5]);
                            $('#product_totalb').val(productoinfo[5]);
                            $('#product_cantb').val(1);
                            respuesta('PRODUCTO ENCONTRADO');
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
            var cadena = "codigo=" + cod +
                "&producto=" + productfull +
                "&unids=" + unid +
                "&precio=" + precio +
                "&cantidad=" + cant +
                "&tipo_igv=" + tipo_igv;

            if(stock >= cant){
                $.ajax({
                    type:"POST",
                    url: urlweb + "api/Ventas/addproduct",
                    data : cadena,
                    success:function (r) {
                        switch (r) {
                            case "1":
                                respuesta('Producto Agregado');
                                $('#tabla_productos').load(urlweb + 'Ventas/tabla_productos');
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

    </script>