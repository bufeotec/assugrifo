
function realizar_proforma(){
    var valor = true;
    //datos cliente
    var client_number = $('#client_number').val();
    var client_name = $('#client_name').val();
    var client_address = $('#client_address').val();
    var saleproduct_direccion = $('#client_address').val();
    var client_telefono = $('#client_telefono').val();
    var select_tipodocumento = $('#select_tipodocumento').val();
    var proforma_fecha_vigencia = $('#proforma_fecha_vigencia').val();
    var proforma_nota = $('#proforma_nota').val();

    var saleproduct_type = $('#type_sell').val();
    var id_moneda = $('#tipo_moneda').val();

    var total = $('#montototal').val();
    var saleproduct_total = total;


    //valor = validar_campo_vacio('saleproduct_type', saleproduct_type, valor);

    if(valor){
        var cadena = "client_number=" + client_number +
            "&cliente_name=" + client_name +
            "&client_address=" + client_address +
            "&saleproduct_direccion=" + saleproduct_direccion +
            "&cliente_telefono=" + client_telefono +
            "&select_tipodocumento=" + select_tipodocumento +
            "&id_moneda=" + id_moneda +
            "&saleproduct_type=" + saleproduct_type +
            "&proforma_fecha_vigencia=" + proforma_fecha_vigencia +
            "&proforma_nota=" + proforma_nota +
            "&saleproduct_total=" + saleproduct_total;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Proforma/guardar_proforma",
            data: cadena,
            dataType: 'json',
            success: function (r) {
                if (r !== 0) {
                    respuesta("Proforma Generada con Exito", "success")
                    location.href = urlweb + 'Proforma/ver_proforma';
                } else {
                    respuesta("No se pudo generar la Proforma, vuelva a intentarlo", "error")
                }
            }
        });
    }
}


function quitarProducto(cod) {
    var cadena = "codigo=" + cod;
    $.ajax({
        type:"POST",
        url: urlweb + "api/Proforma/eliminar_producto",
        data : cadena,

        success:function (r) {
            if(r==1){
                respuesta('¡Producto Eliminado!', 'success');
                $('#tabla_proforma').load(urlweb + 'Proforma/tabla_proforma');
            } else {
                respuesta('Error al eliminar registro', 'error');
            }
        }
    });
}

//Funcion para eliminar una mesa
function eliminar_proforma(id_proforma){
    var valor = true;
    //Validamos si los campos a usar no se encuentran vacios
    valor = validar_parametro_vacio(id_proforma, valor);
    //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
    if(valor){
        //Definimos el mensaje y boton a afectar
        var boton = "btn-eliminar_proforma" + id_proforma;
        //Cadena donde enviaremos los parametros por POST
        var cadena = "id_proforma=" + id_proforma;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Proforma/eliminar_proforma",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, "Eliminando...", true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "Eliminando...", false);
                switch (r.result.code) {
                    case 1:
                        $('#proforma' + id_proforma).remove();
                        respuesta('¡Proforma Eliminada Exitosamente!', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al eliminar proforma', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}