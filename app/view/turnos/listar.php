<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . '/' . $_SESSION['accion'];?></h1>
        <a href="<?= _SERVER_?>Turno/agregar"  class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fa fa-search fa-sm text-white-50"></i> Agregar Turnos</a>
    </div>

    <section class="container-fluid">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="col-lg-12" style="text-align: center; padding-bottom:5px; "><h2>Listado de Turnos Agregados</h2></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0" border="0">
                        <thead class="text-capitalize">
                        <tr>
                            <th>ID</th>
                            <th>Turno Nombre</th>
                            <th>Hora de Apertura</th>
                            <th>Hora de Cierre</th>
                            <th>Eliminar</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $a = 1;
                        foreach ($turnos as $t){

                            ?>
                            <tr id="turnos<?= $t->id_turno;?>">
                                <td><?= $a;?></td>
                                <td><?= $t->turno_nombre;?></td>
                                <td><?= $t->turno_apertura;?></td>
                                <td><?= $t->turno_cierre;?></td>
                                <td><button id="btn-eliminar_turno<?= $t->id_turno;?>" class="btn btn-xs btn-danger btne" onclick="preguntar('¿Está seguro que desea eliminar este turno?','eliminar_turno','Si','No',<?= $t->id_turno;?>)"><i class="fa fa-close"></i> Eliminar Turno</button></td>
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
    </section>
</div>
<!-- End of Main Content -->
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>turnos.js"></script>

