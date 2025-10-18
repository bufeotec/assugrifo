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
        <hr><h2 class="concss">
            <a href="<?= _SERVER_?>"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="<?= _SERVER_?>Clientes"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
        </h2><hr>
    </section>

    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12" style="text-align: center">
                <h5 class="font-weight-bold">AGREGAR NUEVO PROVEEDOR</h5>
            </div>
        </div><br>
        <div class="row">
            <!-- left column -->
            <div class="col-md-4">
                <label>Nombre Proveedor</label>
                <input type="text" class="form-control" id="proveedor_nombre" placeholder="Ingresar Nombre Proveedor...">
            </div>
            <div class="col-md-4">
                <label>RUC ó DNI</label>
                <input type="text" class="form-control" id="proveedor_documento_identidad" placeholder="Ingresar RUC ó DNI...">
            </div>
            <div class="col-md-4">
                <label>Teléfono</label>
                <input type="text" class="form-control" id="proveedor_telefono" placeholder="Ingresar Numero de Telefono...">
            </div>
        </div>
        <br><div class="row">
            <div class="col-md-4">
                <label>Dirección</label>
                <input type="text" class="form-control" id="proveedor_direccion" placeholder="Ingresar Direccion..." value="">
            </div>
            <div class="col-md-4">
                <label>Correo Electronico</label>
                <input type="text" class="form-control" id="proveedor_correo" placeholder="Ingresar correo..." value="">
            </div>
            <div class="col-md-4">
                <button id="btn-agregar_proveedor" type="submit" class="btn btn-primary" style="width:100%; margin-top:33px" onclick="agregar_proveedor()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar Proveedor</button>
            </div>
        </div>
        <!-- /.row -->
    </section>

</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>proveedor.js"></script>