function agregar_proveedor(){
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var proveedor_nombre = $('#proveedor_nombre').val();
    var proveedor_documento_identidad = $('#proveedor_documento_identidad').val();
    var proveedor_telefono = $('#proveedor_telefono').val();
    var proveedor_direccion = $('#proveedor_direccion').val();
    var proveedor_correo = $('#proveedor_correo').val();

    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_campo_vacio('proveedor_nombre', proveedor_nombre, valor);
    valor = validar_campo_vacio('proveedor_documento_identidad', proveedor_documento_identidad, valor);
    //valor = validar_campo_vacio('proveedor_telefono', proveedor_telefono, valor);
    //valor = validar_campo_vacio('proveedor_direccion', proveedor_direccion, valor);
    //valor = validar_campo_vacio('proveedor_correo', proveedor_correo, valor);

    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-agregar_proveedor";
        var cadena = "proveedor_nombre=" + proveedor_nombre +
            "&proveedor_documento_identidad=" + proveedor_documento_identidad +
            "&proveedor_telefono=" + proveedor_telefono +
            "&proveedor_direccion=" + proveedor_direccion +
            "&proveedor_correo=" + proveedor_correo;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Proveedor/guardar_proveedor",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar Proveedor", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Proveedor Agregado Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Proveedor/listar';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al agregar Proveedor', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function eliminarproveedor(id_proveedor, boton) {
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    if(valor) {
        var cadena = "id_proveedor=" + id_proveedor;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Proveedor/eliminar_proveedor",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#proveedor' + id_proveedor).remove();
                        respuesta('¡Proveedor Eliminado!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al eliminar Proveedor', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function editar_proveedor(){
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var proveedor_nombre = $('#proveedor_nombre').val();
    var proveedor_documento_identidad = $('#proveedor_documento_identidad').val();
    var proveedor_telefono = $('#proveedor_telefono').val();
    var proveedor_direccion = $('#proveedor_direccion').val();
    var proveedor_correo = $('#proveedor_correo').val();
    var id_proveedor = $('#id_proveedor').val();

    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_campo_vacio('proveedor_nombre', proveedor_nombre, valor);
    valor = validar_campo_vacio('proveedor_documento_identidad', proveedor_documento_identidad, valor);
    valor = validar_campo_vacio('proveedor_telefono', proveedor_telefono, valor);
    valor = validar_campo_vacio('proveedor_direccion', proveedor_direccion, valor);
    valor = validar_campo_vacio('proveedor_correo', proveedor_correo, valor);
    valor = validar_campo_vacio('id_proveedor', id_proveedor, valor);
    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-agregar_proveedor";
        var cadena = "proveedor_nombre=" + proveedor_nombre +
            "&proveedor_documento_identidad=" + proveedor_documento_identidad +
            "&proveedor_telefono=" + proveedor_telefono +
            "&proveedor_direccion=" + proveedor_direccion +
            "&proveedor_correo=" + proveedor_correo +
            "&id_proveedor=" + id_proveedor;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Proveedor/guardar_proveedor",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar Proveedor", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Proveedor Agregado Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Proveedor/listar';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al agregar Proveedor', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

}
