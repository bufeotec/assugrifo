<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 02/11/2018
 * Time: 0:36
 */
?>

<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
    </section>

    <br>
    <section class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">

                </div>

                <div class="box-body">
                    <input type="hidden" id="id_proveedor" name="id_proveedor" value="<?= $id;?>">
                    <div class="form-group">
                        <label>Nombre Proveedor</label>
                        <input type="text" class="form-control" id="proveedor_nombre" value="<?= $proveedor->proveedor_nombre;?>" placeholder="Ingresar Nombre Proveedor...">
                    </div>
                    <div class="form-group">
                        <label>RUC ó DNI</label>
                        <input type="text" class="form-control" id="proveedor_documento_identidad" value="<?= $proveedor->proveedor_documento_identidad;?>" placeholder="Ingresar RUC ó DNI...">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" class="form-control" id="proveedor_telefono" value="<?= $proveedor->proveedor_telefono;?>" placeholder="Ingresar Numero de Telefono...">
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" class="form-control" id="proveedor_direccion" value="<?= $proveedor->proveedor_direccion;?>" placeholder="Ingresar Direccion..." value="">
                    </div>
                    <div class="form-group">
                        <label>Correo Electronico</label>
                        <input type="text" class="form-control" id="proveedor_correo" value="<?= $proveedor->proveedor_correo;?>" placeholder="Ingresar correo..." value="">
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button id="btn-editar_proveedor" type="submit" class="btn btn-primary" onclick="editar_proveedor()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar Proveedor</button>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>

</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>proveedor.js"></script>