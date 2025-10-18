<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="container-fluid">
        <hr><h2 class="concss">
            <a href="<?= _SERVER_?>"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="<?= _SERVER_?>Reporte"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
        </h2><hr>
    </section>

    <section class="content" style="background-color: white;box-shadow: 2px 2px 2px #888888;border-radius: 5px; padding: 10px; margin: 10px; min-height: 500px">
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <div class="col-lg-12">
                <h4 style="text-align: center;">REPORTE DE COMPRAS DIARIOS</h4>
                <h5 style="text-align: center;"><i class="fa fa-calendar"></i> Fecha: <?php echo date('d-m-Y');?></h5>
            </div>
        </div>

        <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover" style="border-color: black">
                <thead>
                <tr style="background-color: #ebebeb">
                    <th>Producto</th>
                    <th>Proveedor</th>
                    <th>Fecha de Compra</th>
                    <th>Precio de Compra</th>
                    <th>Precio Venta</th>
                    <th>Stock Total</th>
                </tr>
                </thead>
                <tbody>
                <?php

                //Calculo de Todo lo que es productos
                $ingresos_productos = 0;
                foreach ($productos_stock as $p){
                    ?>
                    <tr>
                        <td><?php echo $p->producto_nombre;?></td>
                        <td><?php echo $p->proveedor_nombre;?></td>
                        <td><?php echo $p->stocklog_date;?></td>
                        <td><?php echo $p->producto_precio_compra ?? 0;?></td>
                        <td><?php echo $p->producto_precio_valor ?? 0;?></td>
                        <td><?php echo $p->producto_stock ?? 0;?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
</div>

    </section>