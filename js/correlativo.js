
function editar(){
    var valor = true;
    var id_correlativo = $('#id_correlativo').val();
    var correlativo_b = $('#correlativo_b').val();
    var correlativo_f = $('#correlativo_f').val();
    var correlativo_in = $('#correlativo_in').val();
    var correlativo_out = $('#correlativo_out').val();
    var correlativo_nc = $('#correlativo_nc').val();
    var correlativo_nd = $('#correlativo_nd').val();
    var correlativo_venta = $('#correlativo_venta').val();
    //Validamos si los campos a usar no se encuentran vacios

    valor = validar_campo_vacio('id_correlativo', id_correlativo, valor);
    valor = validar_campo_vacio('correlativo_b', correlativo_b, valor);
    valor = validar_campo_vacio('correlativo_f', correlativo_f, valor);
    valor = validar_campo_vacio('correlativo_in', correlativo_in, valor);
    valor = validar_campo_vacio('correlativo_out', correlativo_out, valor);
    valor = validar_campo_vacio('correlativo_nc', correlativo_nc, valor);
    valor = validar_campo_vacio('correlativo_nd', correlativo_nd, valor);
    valor = validar_campo_vacio('correlativo_venta', correlativo_venta, valor);

    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-editar-producto";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "id_correlativo=" + id_correlativo +
            "&correlativo_b=" + correlativo_b +
            "&correlativo_f=" + correlativo_f +
            "&correlativo_in=" + correlativo_in +
            "&correlativo_out=" + correlativo_out +
            "&correlativo_nc=" + correlativo_nc +
            "&correlativo_nd=" + correlativo_nd +
            "&correlativo_venta=" + correlativo_venta;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Correlativo/editar_c",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Correlativo Editado Exitosamente!', 'success');
                        setTimeout(function () {
                            location.reload()
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al editar Correlativos', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}