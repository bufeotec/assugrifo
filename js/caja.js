function agregar_caja(){
    var valor = true;
    //Extraemos las variable según los valores del campo consultado
    var caja_numero_nombre = $('#caja_numero_nombre').val();
    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_campo_vacio('caja_numero_nombre', caja_numero_nombre, valor);

    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        var boton = "btn-agregar_caja";
        var cadena = "caja_numero_nombre=" + caja_numero_nombre ;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Caja/agregar_caja",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Guardando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar Caja", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Caja Agregada Exitosamente!', 'success');
                        setTimeout(function () {
                            location.href = urlweb + 'Caja/listar';
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al agregar Caja', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}

function eliminarcaja(id_caja_numero, boton) {
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    if(valor) {
        var cadena = "id_caja_numero=" + id_caja_numero;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Caja/eliminarcaja",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success: function (r) {
                cambiar_estado_boton(boton, "Eliminar Registro", false);
                switch (r.result.code) {
                    case 1:
                        $('#caja' + id_caja_numero).remove();
                        respuesta('¡Caja Eliminada!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al eliminar Caja', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}