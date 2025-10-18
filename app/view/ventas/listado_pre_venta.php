

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
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado de Pre Ventas</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable1" width="100%" cellspacing="0">
                            <thead class="text-capitalize">
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Código Venta</th>
                                <th>Producto</th>
                                <th>P. Unitario</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $a = 1;
                            foreach ($ventas_ as $v){
                                $sacar_datos_p = $this->ventas->sacar_datos_pventa($v->id_venta);
                                ?>
                                <tr id="pre_venta_<?= $v->id_venta?>">
                                    <td><?= $a?></td>
                                    <td><?= $v->venta_fecha;?></td>
                                    <td><?= $v->id_venta;?></td>
                                    <?php
                                    $nom="";
                                    $val = "";
                                    $cant = "";
                                    $total = "";
                                    foreach ($sacar_datos_p as $al){
                                        $nom .= "- ".$al->venta_detalle_nombre_producto. "<br>";
                                        $val .= "- ".$al->venta_detalle_valor_unitario. "<br>";
                                        $cant .= "- ".$al->venta_detalle_cantidad. "<br>";
                                        $total .= "S/. ".$al->venta_detalle_valor_total. "<br>";
                                    }
                                    ?>
                                    <td><?= $nom;?></td>
                                    <td><?= $val?></td>
                                    <td><?= $cant?></td>
                                    <td><?=$total?></td>
                                    <td>
                                        <a type="button" class="btn btn-success" target='_blank' href="<?= _SERVER_ . 'Ventas/cobrar_venta/' . $v->id_venta ;?>" style="color: black" ><i class="fa fa-cc-mastercard"></i></a>
                                        <a type="button" class="btn btn-danger" onclick="preguntar('¿Esta seguro de eliminar toda la pre-venta?','eliminar_pre_venta','SI','NO',<?= $v->id_venta?>)"><i class="fa fa-times text-white"></i></a>
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
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>