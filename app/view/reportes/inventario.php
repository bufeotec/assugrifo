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
        <div class="row">
            <div class="col-lg-12">
                <h4 style="text-align: center">INVENTARIO <i class="fa fa-calendar"></i> <?php echo $fecha;?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered table-hover" style="border-color: black">
                    <thead>
                    <tr style="background-color: #ebebeb">
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>SALDO ANT</th>
                        <th>ENTRADA</th>
                        <th>SALIDA</th>
                        <th>VENDIDO</th>
                        <th>SALDO</th>
                        <th>PRECIO UNIT</th>
                        <th>TOTAL S/. </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //Calculo de Todo lo que es productos
                    $ingresos_productos = 0;
                    foreach ($productos as $p){

                        $total_por_producto = $this->reporte->total_por_producto($fecha,$p->id_producto);
                        ?>
                        <tr style="text-align: right; border-bottom: 2px solid #a3a6a5">
                            <th style="text-align: center"><?php echo $p->id_producto;?></th>
                            <th style="text-align: center"><?php echo $p->producto_nombre;?></th>
                            <td><?php echo $inventario_inicial ?? 0;?></td>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <td><?php echo $total_por_producto;?></td>
                        </tr>
                        <?php
                    }
                    //Fin de Calculo Todo Lo Que Es Productos
                    ?>
                    <tr style="text-align: right"><td colspan="8">Total Ingresos Ventas Productos:</td><td style="background-color: #f9f17f"><b> S/. <?php echo $ingresos_productos ?? 0;?></b></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: center;">
               <a  href="<?php echo _SERVER_;?>/Reporte/inventario_pdf" target="_blank" class="btn btn-primary">Imprimir Reporte</a>
            </div>
        </div>

    </section>

</div>