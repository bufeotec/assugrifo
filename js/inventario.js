

function add() {
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var producto_nombre = $('#producto_nombre').val();
    var id_categoria = $('#id_categoria').val();
    var producto_codigo_barra = $('#producto_codigo_barra').val();
    var producto_descripcion = $('#producto_descripcion').val();
    var id_medida = $('#id_medida').val();
    var id_tipoafectacion = $('#id_tipoafectacion').val();
    var id_proveedor = $('#id_proveedor').val();
    var producto_stock = $('#producto_stock').val();
    var producto_precio_valor = $('#producto_precio_valor').val();
    var producto_precio_valor_xmayor = $('#producto_precio_valor_xmayor').val();
    var producto_precio_compra = $('#producto_precio_compra').val();

    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_campo_vacio('producto_nombre', producto_nombre, valor);
    valor = validar_campo_vacio('id_categoria', id_categoria, valor);
    //valor = validar_campo_vacio('producto_descripcion', producto_descripcion, valor);
    //valor = validar_campo_vacio('id_medida', id_medida, valor);
    //valor = validar_campo_vacio('id_tipoafectacion', id_tipoafectacion, valor);
    //valor = validar_campo_vacio('id_proveedor', id_proveedor, valor);
    //valor = validar_campo_vacio('producto_stock', producto_stock, valor);
    //valor = validar_campo_vacio('producto_precio_valor', producto_precio_valor, valor);
    //valor = validar_campo_vacio('producto_precio_compra', producto_precio_compra, valor);
    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-agregar-producto";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "producto_nombre=" + producto_nombre +
            "&id_categoria=" + id_categoria +
            "&producto_codigo_barra=" + producto_codigo_barra +
            "&producto_descripcion=" + producto_descripcion +
            "&id_medida=" + id_medida +
            "&id_tipoafectacion=" + id_tipoafectacion +
            "&id_proveedor=" + id_proveedor +
            "&producto_stock=" + producto_stock +
            "&producto_precio_valor=" + producto_precio_valor +
            "&producto_precio_valor_xmayor=" + producto_precio_valor_xmayor +
            "&producto_precio_compra=" + producto_precio_compra;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/guardar_producto_nuevo",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Producto Agregado Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Inventario/listarproductos';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al agregar Producto', 'error');
                        break;
                    case 3:
                        respuesta('El Producto ya se encuentra registrado', 'warning');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function editar() {
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var id_producto = $('#id_producto').val();
    var id_categoria = $('#id_categoria').val();
    //var id_proveedor = $('#id_proveedor').val();
    var producto_nombre = $('#producto_nombre').val();
    //var producto_codigo_barra = $('#producto_codigo_barra').val();
    //var producto_precio_valor = $('#producto_precio_valor').val();
    var producto_stock = $('#producto_stock').val();

    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_campo_vacio('producto_nombre', producto_nombre, valor);
    valor = validar_campo_vacio('id_categoria', id_categoria, valor);
    //valor = validar_campo_vacio('producto_precio_valor', producto_precio_valor, valor);

    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-editar-producto";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "id_producto=" + id_producto +
            "&id_categoria=" + id_categoria +
            "&producto_stock=" + producto_stock +
            "&producto_nombre=" + producto_nombre;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/guardar_producto_precio",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Producto Editado Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Inventario/listarproductos';
                        }, 500);
                        break;
                    case 2:
                        respuesta('Error al editar Producto', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function eliminarproducto(id_producto, boton) {
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    if(valor) {
        var cadena = "id_producto=" + id_producto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/eliminar_producto",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#producto' + id_producto).remove();
                        respuesta('¡Producto Eliminado!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al eliminar producto', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function eliminar_categoria(id_categoria, boton){
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    if(valor) {
        var cadena = "id_categoria=" + id_categoria;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Categorias/eliminar_categoria",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#categoria' + id_categoria).remove();
                        respuesta('¡Registro Eliminado!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 300);
                        break;
                    case 2:
                        respuesta('Error al eliminar registro', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function quitarproducto(id_producto, id_producto_precio, boton){
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    if(valor) {
        var cadena = "id_producto=" + id_producto + "&id_producto_precio=" + id_producto_precio;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/quitar_producto",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#producto' + id_producto).remove();
                        respuesta('¡Producto Eliminado!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al eliminar producto', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function agregarstock(){
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var producto_stock = $('#producto_stock').val();
    var id_producto = $('#id_producto').val();
    var id_talla = $('#id_talla').val();
    var stock_nuevo = $('#stock_nuevo').val();
    var producto_descripcion = $('#producto_descripcion').val();
    var id_proveedor = $('#id_proveedor').val();
    var producto_precio_compra = $('#producto_precio_compra').val();
    var producto_precio_valor = $('#producto_precio_valor').val();

    var stocklog_guide = $('#stocklog_guide').val();
    var stocklog_description = $('#stocklog_description').val();

    valor = validar_campo_vacio('id_producto', id_producto, valor);
    valor = validar_campo_vacio('id_talla', id_talla, valor);

    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-editar-stock";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "id_producto=" + id_producto +
            "&id_talla=" + id_talla +
            "&producto_stock=" + producto_stock +
            "&stock_nuevo=" + stock_nuevo +
            "&producto_descripcion=" + producto_descripcion +
            "&id_proveedor=" + id_proveedor +
            "&producto_precio_valor=" + producto_precio_valor +
            "&producto_precio_compra=" + producto_precio_compra +
            "&stocklog_guide=" + stocklog_guide +
            "&stocklog_description=" + stocklog_description;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/editar_stock",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Stock Agregado Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Inventario/listar_tipo_productos/' + id_producto;
                        }, 400);
                        break;
                    case 2:
                        respuesta('Error al agregar stock', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function salida_stock(){
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var id_producto = $('#id_producto').val();
    var id_talla = $('#id_talla').val();
    var stockout_out = $('#stockout_out').val();
    var stockout_guide = $('#stockout_guide').val();
    var stockout_description = $('#stockout_description').val();
    var stockout_ruc = $('#stockout_ruc').val();
    var stockout_origin = $('#stockout_origin').val();
    var stockout_destiny = $('#stockout_destiny').val();

    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-salida_stock";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "id_producto=" + id_producto +
            "&id_talla=" + id_talla +
            "&stockout_out=" + stockout_out +
            "&stockout_guide=" + stockout_guide +
            "&stockout_description=" + stockout_description +
            "&stockout_ruc=" + stockout_ruc +
            "&stockout_origin=" + stockout_origin +
            "&stockout_destiny=" + stockout_destiny;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Inventario/salidastock",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Se Realizo Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Inventario/listar_tipo_productos/' + id_producto;
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al agregar stock', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function jalar_categorias(){
    var id_familia = $("#id_familia").val();
    $.ajax({
        type: "POST",
        url: urlweb + "api/Inventario/jalar_categorias",
        data: "id_familia="+id_familia,
        dataType: 'json',
        success:function (r) {
            $("#datos_categoria").html(r);
        }
    });
}

function  validar_fechas2(){
    var fecha_inicio = $('#fecha_inicio').val();
    var fecha_fin = $('#fecha_fin').val();

    var fecha_inicial = new Date(fecha_inicio);
    var fecha_final = new Date(fecha_fin);

    if(fecha_inicial > fecha_final){
        //alertify.warning('Las Fecha de Inicio No Puede Ser Mayor a la Fecha Final');
        $('#fecha_fin').val(fecha_inicio);
    }
}

function consultar_kardex_item(id) {
    var valor = true;
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    valor = validar_campo_vacio('fecha_inicio', fecha_inicio, valor);
    valor = validar_campo_vacio('fecha_fin', fecha_fin, valor);
    if(valor){
        /*alertify.message('Cargando Recursos de Sede');
        //$("#nuevo_recurso").removeClass('no-show');
        $('#informacion_almacen').load(urlweb + 'almacen/listar_recursos_almacen/' + id_sede);*/
        location.href = urlweb + 'index.php?c=inventario&a=kardex_item&id=' + id + '&fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin;
    }
}
