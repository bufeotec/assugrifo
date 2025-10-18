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
    <section class="content-header">
        <hr><h2 class="concss">
            <a href="<?= _SERVER_?>"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="<?= _SERVER_?>Clientes"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
        </h2><hr>
    </section>

    <!-- /.row (main row) -->
    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4" style="text-align: center;">
                            <h5 class="font-weight-bold text-primary">LISTA DE PROVEEDORES REGISTRADOS</h5>
                        </div>
                        <div class="col-lg-1"></div>
                        <div class="col-lg-3">
                            <a href="<?= _SERVER_?>Proveedor/agregar" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm font-weight-bold" style="width: 100%"><i class="fa fa-plus fa-sm text-white-50"></i> AGREGAR NUEVO PROVEEDOR</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="text-capitalize">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Doc. Identidad</th>
                                <th>Telefono</th>
                                <th>Direccion</th>
                                <th>Correo</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            foreach ($proveedores as $p){
                                ?>
                                <tr id="proveedor<?= $p->id_proveedor;?>">
                                    <td><?= $a;?></td>
                                    <td><?= $p->proveedor_nombre;?></td>
                                    <td><?= $p->proveedor_documento_identidad;?></td>
                                    <td><?= $p->proveedor_telefono;?></td>
                                    <td><?= $p->proveedor_direccion;?></td>
                                    <td><?= $p->proveedor_correo;?></td>
                                    <td>
                                        <a href="<?= _SERVER_ ?>Proveedor/editar/<?= $p->id_proveedor ?>" class="btn btn-chico btn-primary btn-xs"><i class="fa fa-edit"></i> Editar</a>
                                        <button id="btn-eliminar_proveedor<?= $p->id_proveedor;?>" class="btn btn-chico btn-danger btn-xs" onclick="preguntar('¿Está seguro que desea eliminar este proveedor?','eliminarproveedor','Si','No',<?= $p->id_proveedor;?>)"><i class="fa fa-times"></i> Eliminar</button>
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
<script src="<?php echo _SERVER_ . _JS_;?>proveedor.js"></script>