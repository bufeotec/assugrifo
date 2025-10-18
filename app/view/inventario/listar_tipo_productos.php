

<div id="modal_add_rec" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 60% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Guardar Tipo de Recurso</h4>
            </div>
            <div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="recurso">Denomicación</label>
                            <input type="text" onkeyup="mayuscula(this.id)" id="talla_nombre" class="form-control" name="talla_nombre" maxlength="100">
                        </div>
                        <div class="col-md-2">
                            <label>Stock</label>
                            <input class="form-control" onkeyup="validar_numeros(this.id)" type="text" id="talla_stock" name="talla_stock">
                        </div>
                        <div class="col-md-3">
                            <label for="">Precio</label>
                            <input type="text" class="form-control" onkeyup="validar_numeros_decimales_dos(this.id)" id="producto_precio_valor" name="producto_precio_valor">
                        </div>
                        <div class="col-md-3">
                            <label >Precio X Mayor</label>
                            <input type="text" class="form-control" onkeyup="validar_numeros_decimales_dos(this.id)" id="producto_precio_valor_xmayor">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Codigo de Barra</label>
                            <input class="form-control" onkeyup="mayuscula(this.id)" type="text" id="talla_codigo_barra" name="talla_codigo_barra">
                        </div>
                        <div class="col-md-3">
                            <label >Proveedor del Producto</label>
                            <select class="form-control" id= "id_proveedor">
                                <option value="">Seleccionar Proveedor</option>
                                <?php
                                foreach($proveedor as $p){
                                    ($p->id_proveedor == "1")?$selec='selected':$selec='';
                                    ?>
                                    <option value="<?php echo $p->id_proveedor;?>" <?= $selec;?>><?php echo $p->proveedor_nombre;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label >Unidad de Medida</label>
                            <select class="form-control" id="id_medida">
                                <option value="">Seleccione...</option>
                                <?php
                                foreach ($unimedida as $um){
                                    ($um->id_medida == "58")?$selec='selected':$selec='';
                                    ?>
                                    <option value="<?php echo $um->id_medida;?>" <?= $selec;?>><?php echo $um->medida_nombre;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label >Tipo Afectación</label>
                            <select class="form-control" id="id_tipoafectacion" disabled>
                                <option value="">Seleccione...</option>
                                <?php
                                foreach ($codigoafectacion as $um){
                                    ($um->codigo == "20")?$selec='selected':$selec='';
                                    ?>
                                    <option value="<?php echo $um->codigo;?>" <?= $selec;?>><?php echo $um->descripcion;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="btn-agregar-talla" type="submit" onclick="guardar_recurso_tipo()" class="btn btn-primary" value="Agregar">
                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Regresar">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_add_rec_" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Recursos</h4>
            </div>
            <div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-6">
                            <label for="recurso">Denomicación</label>
                            <input type="hidden" id="id_talla" name="id_talla">
                            <input type="hidden" id="id_producto" class="form-control" name="id_producto" value="<?= $id;?>">
                            <input type="text" onkeyup="mayuscula(this.id)" id="talla_nombre_" class="form-control" name="talla_nombre_" maxlength="100">
                        </div>
                        <div class="col-md-6 col-6">
                            <label>Precio</label>
                            <input class="form-control" type="text" name="producto_precio_valor_ca" id="producto_precio_valor_ca">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="btn-agregar-talla" type="submit" onclick="guardar_recurso_tipo_()" class="btn btn-primary" value="Agregar">
                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Regresar">
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <hr><h2 class="concss">
            <a href="http://localhost/fire"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="http://localhost/fire/Inventario/productos"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
        </h2><hr>
    </section>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Listado de Recursos</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row" >
                        <div class="form-group col-md-3">
                            <label for="tipo">Tipo: <strong><?= $datos->familia_nombre;?></strong></label>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="clase">Clase: <strong><?= $datos->categoria_nombre;?></strong></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nombre">Recurso: <strong><?= $datos->producto_nombre;?></strong></label>
                        </div>
                        <!--<div class="form-group col-md-3" >
                            <button data-toggle="modal" data-target="#modal_add_rec" onclick="agregacion_recurso_tipo()" class="btn btn-danger">Agregar Denominacion</button>
                        </div>-->
                    </div>
                    <div class="form-row" >
                        <div class="form-group col-md-9">
                        </div>
                        <div class="form-group col-md-3" >
                            <button data-toggle="modal" data-target="#modal_add_rec" onclick="agregacion_recurso_tipo()" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar Denominacion</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado de Tallas / Medidas</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable1" width="100%" cellspacing="0">
                            <thead class="text-capitalize">
                            <tr>
                                <th>#</th>
                                <th>Codigo Barra</th>
                                <th>Talla / Medida</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th>Precio x Mayor</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            foreach ($tipos as $m){
                                ?>
                                <tr id="fila_recurso_<?= $m->id_talla ?>">
                                    <td><?php echo $a;?></td>
                                    <td><?php echo $m->talla_codigo_barra;?></td>
                                    <td><?php echo $m->talla_nombre;?></td>
                                    <td><?php echo $m->talla_stock;?></td>
                                    <td><?php echo $m->producto_precio_valor;?></td>
                                    <td><?php echo $m->producto_precio_valor_xmayor;?></td>
                                    <td>
                                        <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_rec_" title="Editar" onclick="editar_recurso_tipo(<?= $m->id_talla ?>,'<?= $m->talla_nombre; ?>','<?= $m->producto_precio_valor?>')" type="button"><i class="fa fa-edit ver_detalle text-white"></i></a>
                                        <a class="btn btn-danger" id="btn btn-eliminar" type="button" onclick="preguntar('Esta seguro de eliminar este registro?','eliminar_recurso_tipo','SI','NO',<?= $m->id_talla;?>)"><i class="fa fas fa-times adjuntar text-white" title="Eliminar"></i></a>
                                        <a class="btn btn-info" href="<?php echo _SERVER_;?>Inventario/agregar_stock/<?= $m->id_talla ?>"><i class="fa fa-arrow-down" title="Agregar stock"></i> </a>
                                        <a class="btn btn-primary" href="<?php echo _SERVER_;?>Inventario/salida_stock/<?php echo $m->id_talla;?>"><i class="fa fa-arrow-right" title="Salida de stock"></i></a>
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

        </div>
    </div>

</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>inventario.js"></script>

<script>
    function agregacion_recurso_tipo(){
        $('#id_talla').val("");
        $('#talla_nombre').val("");
        $('#talla_stock').val("");
    }

    function editar_recurso_tipo(id_talla, talla_nombre,precio){
        $('#id_talla').val(id_talla);
        $('#talla_nombre_').val(talla_nombre);
        $('#producto_precio_valor_ca').val(precio);
    }

    function guardar_recurso_tipo(){
        var valor = true;
        //Extraemos las variable según los valores del campo consultado
        var id_producto = $('#id_producto').val();
        var id_talla = $('#id_talla').val();
        var talla_nombre = $('#talla_nombre').val();
        var talla_stock = $('#talla_stock').val();
        var talla_codigo_barra = $('#talla_codigo_barra').val();
        //VALORES PARA LA TABLA DE PRECIOS
        var producto_precio_valor = $('#producto_precio_valor').val();
        var producto_precio_valor_xmayor = $('#producto_precio_valor_xmayor').val();
        var id_medida = $('#id_medida').val();
        var id_proveedor = $('#id_proveedor').val();
        var id_tipoafectacion = $('#id_tipoafectacion').val();


        //valor = validar_campo_vacio('id_producto', id_producto, valor);
        valor = validar_campo_vacio('talla_nombre', talla_nombre, valor);
        valor = validar_campo_vacio('talla_stock', talla_stock, valor);
        //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
        if(valor){
            //Definimos el mensaje y boton a afectar
            var boton = "btn-agregar-talla";
            //Cadena donde enviaremos los parametros por POST
            var cadena = "id_producto=" + id_producto +
                "&id_talla=" + id_talla +
                "&id_proveedor=" + id_proveedor +
                "&talla_stock=" + talla_stock +
                "&talla_codigo_barra=" + talla_codigo_barra +
                "&producto_precio_valor=" + producto_precio_valor +
                "&producto_precio_valor_xmayor=" + producto_precio_valor_xmayor +
                "&id_tipoafectacion=" + id_tipoafectacion +
                "&id_medida=" + id_medida +
                "&talla_nombre=" + talla_nombre;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Inventario/guardar_tipo_productos",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, "Guardando...", true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Agregado Exitosamente!', 'success');
                            setTimeout(function () {
                                location.reload();
                            }, 300);
                            break;
                        case 2:
                            respuesta('Error al agregar', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }

    function guardar_recurso_tipo_(){
        var valor = true;
        //Extraemos las variable según los valores del campo consultado
        var id_producto = $('#id_producto').val();
        var id_talla = $('#id_talla').val();
        var talla_nombre = $('#talla_nombre_').val();
        var producto_precio_valor_ca = $('#producto_precio_valor_ca').val();


        //valor = validar_campo_vacio('id_producto', id_producto, valor);
        valor = validar_campo_vacio('talla_nombre', talla_nombre, valor);
        //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
        if(valor){
            //Definimos el mensaje y boton a afectar
            var boton = "btn-agregar-talla";
            //Cadena donde enviaremos los parametros por POST
            var cadena = "id_producto=" + id_producto +
                "&id_talla=" + id_talla +
                "&talla_nombre=" + talla_nombre +
                "&producto_precio_valor_ca=" + producto_precio_valor_ca;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Inventario/guardar_tipo_productos_e",
                data: cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, "Guardando...", true);
                },
                success:function (r) {
                    cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                    switch (r.result.code) {
                        case 1:
                            respuesta('¡Operacion realizado Exitosamente!', 'success');
                            setTimeout(function () {
                                location.reload();
                            }, 300);
                            break;
                        case 2:
                            respuesta('Error al realizar operacion', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }

    function eliminar_recurso_tipo(id_talla){
        var valor = "correcto";
        var boton = "btn btn-eliminar";
        if (valor == "correcto"){
            var cadena="id_talla="+id_talla;
            $.ajax({
                url:urlweb + "api/Inventario/eliminar_talla_producto",
                type:"POST",
                data:cadena,
                dataType: 'json',
                beforeSend: function () {
                    cambiar_estado_boton(boton, "Eliminando...", true);
                },
                success: function (r) {
                    cambiar_estado_boton(boton, "Eliminar Registro", false);
                    switch (r.result.code) {
                        case 1:
                            $('#fila_recurso_' + id_talla).remove();
                            respuesta('¡Eliminado correctamente...!', 'success');
                            setTimeout(function () {
                                location.reload();
                            }, 200);
                            break;
                        case 2:
                            respuesta('Error al eliminar', 'error');
                            break;
                        default:
                            respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                            break;
                    }
                }
            });
        }
    }
</script>