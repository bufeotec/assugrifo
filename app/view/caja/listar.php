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
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . ' / ' . $_SESSION['accion'];?></h1>
        <a href="<?= _SERVER_?>Caja/agregar" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fa fa-plus fa-sm text-white-50"></i> Agregar Nuevo</a>
    </div>

    <!-- /.row (main row) -->
    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Cajas Registradas en el Sistema</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="text-capitalize">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Fecha de Registro</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            foreach ($cajas as $p){
                                ?>
                                <tr id="caja<?= $p->id_caja_numero;?>">
                                    <td><?= $a;?></td>
                                    <td><?= $p->caja_numero_nombre;?></td>
                                    <td><?= $p->caja_numero_fecha;?></td>
                                    <td>
                                        <a href="<?= _SERVER_ ?>Caja/editar/<?= $p->id_caja_numero;?>" class="btn btn-chico btn-primary btn-xs"><i class="fa fa-edit"></i> Editar</a>
                                        <button id="btn-eliminar_caja<?= $p->id_caja_numero;?>" class="btn btn-chico btn-danger btn-xs" onclick="preguntar('¿Está seguro que desea eliminar esta caja del negocio?','eliminarcaja','Si','No',<?= $p->id_caja_numero;?>)"><i class="fa fa-times"></i> Eliminar</button>
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
<script src="<?php echo _SERVER_ . _JS_;?>caja.js"></script>