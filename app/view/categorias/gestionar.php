

<div id="modal_add_cat" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Guardar Tipo de Recurso</h4>
            </div>
            <div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-6">
                            <label for="recurso">Familia</label>
                            <input type="hidden" id="id_categoria" name="id_categoria">
                            <select class="form-control" id="id_familia" name="id_familia">
                                <option value="">Seleccione...</option>
                                <?php
                                foreach ($familia as $ca){
                                    ?>
                                    <option value="<?php echo $ca->id_familia;?>"><?php echo $ca->familia_nombre;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Cateogoria</label>
                            <input class="form-control" onkeyup="mayuscula(this.id)" type="text" id="categoria_nombre" name="categoria_nombre">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="btn-agregar-categoria" type="submit" onclick="guardar_categoria()" class="btn btn-primary" value="Agregar">
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
        <div class="col-md-3">
            <button class="btn btn-success" data-toggle="modal" data-target="#modal_add_cat"><i class="fa fa-plus"></i> AGREGAR CATEGORIAS</button>
        </div>
    </div>
    <br>
    <form method="post" action="<?= _SERVER_ ?>Categorias/gestionar">
        <input type="hidden" id="enviar_dato" name="enviar_dato" value="1">
        <div class="row">
            <div class="col-lg-3 col-xs-6 col-md-6 col-sm-6">
                <label for="">FAMILIA</label>
                <select class="form-control" name="id_familia" id="id_familia">
                    <option value="">Seleccione...</option>
                    <?php
                    (isset($familia_))?$familiaa=$familia_->id_familia:$familiaa=0;
                    foreach ($familia as $e){
                        ($e->id_familia == $familiaa)?$sele='selected':$sele='';
                        ?>
                        <option value="<?= $e->id_familia;?>" <?= $sele; ?>><?= $e->familia_nombre;?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-3 col-xs-6 col-md-6 col-sm-6">
                <button style="margin-top: 32px; width: 50%" class="btn btn-primary" ><i class="fa fa-search"></i> BUSCAR</button>
            </div>
        </div>
    </form>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado de Categorias</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable1" width="100%" cellspacing="0">
                            <thead class="text-capitalize">
                            <tr>
                                <th>#</th>
                                <th>Familia</th>
                                <th>Categoria</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            foreach ($categorias as $m){
                                if($m->categoria_estado == "0"){
                                    $estilo = "style=\"background-color: #FF6B70\"";
                                }
                                ?>
                                <tr id="categoria<?= $m->id_categoria?>" <?= $estilo;?>>
                                    <td><?php echo $a;?></td>
                                    <td><?php echo $m->familia_nombre;?></td>
                                    <td><?php echo $m->categoria_nombre;?></td>
                                    <td>
                                        <a class="btn btn-success" data-toggle="modal" data-target="#modal_add_cat" title="Editar" onclick="editar_categoria(<?= $m->id_categoria ?>,'<?= $m->id_familia ?>','<?= $m->categoria_nombre; ?>')" type="button"><i class="fa fa-edit ver_detalle text-white"></i></a>
                                        <a class="btn btn-danger" id="btn btn-eliminar" type="button" onclick="preguntar('¿Esta seguro de eliminar esta categoria?','eliminar_categoria','SI','NO',<?= $m->id_categoria;?>)"><i class="fa fas fa-times adjuntar text-white" title="Eliminar"></i></a>
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
    function guardar_categoria(){
        var valor = true;
        //Extraemos las variable según los valores del campo consultado
        var id_familia = $('#id_familia').val();
        var id_categoria = $('#id_categoria').val();
        var categoria_nombre = $('#categoria_nombre').val();

        //valor = validar_campo_vacio('id_producto', id_producto, valor);
        valor = validar_campo_vacio('id_familia', id_familia, valor);
        valor = validar_campo_vacio('categoria_nombre', categoria_nombre, valor);
        //Si var valor no ha cambiado de valor, procedemos a hacer la llamada de ajax
        if(valor){
            //Definimos el mensaje y boton a afectar
            var boton = "btn-agregar-categoria";
            //Cadena donde enviaremos los parametros por POST
            var cadena = "id_familia=" + id_familia +
                "&id_categoria=" + id_categoria +
                "&categoria_nombre=" + categoria_nombre;
            $.ajax({
                type: "POST",
                url: urlweb + "api/Categorias/guardar_categoria",
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
                            location.href = urlweb + 'Categorias/gestionar/';
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

    function editar_categoria(id_categoria,id_familia,categoria_nombre){
        $('#id_categoria').val(id_categoria);
        $('#id_familia').val(id_familia);
        $('#categoria_nombre').val(categoria_nombre);
    }
</script>


