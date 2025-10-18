


function agregar_egreso(){
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var egreso_descripcion = $('#egreso_descripcion').val();
    var egreso_monto = $('#egreso_monto').val();
    var movimiento_tipo = $('#movimiento_tipo').val();

    valor = validar_campo_vacio('egreso_descripcion', egreso_descripcion, valor);
    valor = validar_campo_vacio('egreso_monto', egreso_monto, valor);
    valor = validar_campo_vacio('movimiento_tipo', movimiento_tipo, valor);

    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-agregar-egreso";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "egreso_descripcion=" + egreso_descripcion +
            "&movimiento_tipo=" + movimiento_tipo +
            "&egreso_monto=" + egreso_monto;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Egresos/agregar_egreso",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Movimiento Agregado Correctamente...!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Egresos/listar';
                        }, 400);
                        break;
                    case 2:
                        respuesta('Error al agregar egreso', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function eliminar_egreso(id_egreso) {
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_parametro_vacio(id_egreso, valor);
    if(valor) {
        var boton = "btn-eliminar_egreso" + id_egreso;
        var cadena = "id_egreso=" + id_egreso;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Egresos/eliminar_egreso",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#egresos' + id_egreso).remove();
                        respuesta('¡Registro Eliminado!', 'success');
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

function eliminar_comprobante(id_comprobante){
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_parametro_vacio(id_comprobante, valor);
    if(valor) {
        var boton = "btn-eliminar_comprobante" + id_comprobante;
        var cadena = "id_comprobante=" + id_comprobante;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Egresos/eliminar_comprobante",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Comprobante", false);
                switch (r.result.code) {
                    case 1:
                        $('#comprobantes' + id_comprobante).remove();
                        respuesta('¡Registro Eliminado!', 'success');
                        setTimeout(function () {
                            location.reload()
                        }, 400);
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