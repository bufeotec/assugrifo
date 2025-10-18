
function agregar(){
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var turno_nombre = $('#turno_nombre').val();
    var turno_apertura = $('#turno_apertura').val();
    var turno_cierre = $('#turno_cierre').val();

    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_campo_vacio('turno_nombre', turno_nombre, valor);
    valor = validar_campo_vacio('turno_apertura', turno_apertura, valor);
    valor = validar_campo_vacio('turno_cierre', turno_cierre, valor);

    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-agregar-turno";
        //Cadena donde enviaremos los parametros por POST
        var cadena = "turno_nombre=" + turno_nombre +
            "&turno_apertura=" + turno_apertura +
            "&turno_cierre=" + turno_cierre;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Turno/agregar_turno",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Turno Agregado Exitosamente!', 'success');
                        setTimeout(function () {
                            location = urlweb + "Turno/listar";
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al agregar Turno', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}


function eliminar_turno(id_turno) {
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_parametro_vacio(id_turno, valor);
    if(valor) {
        var boton = "btn-eliminar_turno" + id_turno;
        var cadena = "id_turno=" + id_turno;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Turno/eliminar_turno",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#turno' + id_turno).remove();
                        respuesta('¡Turno Eliminado!', 'success');
                        location.reload();
                        break;
                    case 2:
                        respuesta('Error al eliminar turno', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}