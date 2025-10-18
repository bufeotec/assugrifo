
<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
    </section>
    <section class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>Nombre Caja</label>
                            <input type="text" class="form-control" id="caja_numero_nombre" placeholder="Ingresar Nombre Caja...">
                        </div>
                    </div>
                <div class="box-footer">
                    <button id="btn-agregar_caja" type="submit" class="btn btn-primary" onclick="agregar_caja()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar Caja</button>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>caja.js"></script>