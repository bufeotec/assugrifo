<?php
/**
 * Created by PhpStorm
 * User: CESARJOSE
 * Date: 15/11/2025
 * Time: 00:20
 */
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . '/' . $_SESSION['accion'];?></h1>

    </div>
    <!-- Main row -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Consultar Registros de Ingresos y Egresos de Item: </h4>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input name="fecha_inicio" onchange="validar_fechas2()" type="date" value="<?= $fecha_inicio ;?>" class="form-control" id="fecha_inicio" >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fecha_fin">Fecha de Término</label>
                            <input name="fecha_fin" onchange="validar_fechas2()" type="date" value="<?= $fecha_fin ;?>" class="form-control" id="fecha_fin" >
                        </div>

                        <div class="form-group col-md-4">
                            <button onclick="consultar_kardex_item('<?= $_GET['id'];?>')" class="btn btn-success submitBtn" style="width: 100%; margin-top: 30px;"><i class="fa fa-search"></i> Buscar Ahora</button>
                        </div>
                    </div>
                    <div id="informacion_almacen">
                        <div class="row">
                            <div class="form-group ocul col-md-12"
                                 style="text-align: center; margin-top: 20px;">
                                <h3>Recurso: <?= $producto->producto_nombre; ?>
                                    - <?= $producto->talla_nombre; ?> | Stock de Inicio: <?= $recurso_inicial; ?></h3>
                            </div>
                            <div class="form-group ocul col-md-12" style="text-align: center; margin-top: 20px; display: none;">
                                <h3>Adquirido: <span id="item_adquirido">0</span> | Consumido: <span id="item_consumido">0</span> | Stock Final: <span id="item_stock_final">0</span></h3>
                            </div>
                            <div class="form-group ocul col-md-12">
                                <div class="table-responsive">
                                    <table class="table" id="dataTable2">
                                        <thead>
                                        <tr style="font-weight: bold;text-align: center">
                                            <td>#</td>
                                            <td>Fecha de Movimiento</td>
                                            <td>Documento</td>
                                            <td>Comentario</td>
                                            <td>Cant.</td>
                                            <td>Registrado Por:</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $alo = 1;
                                        $adquirido = 0;
                                        $consumido = 0;
                                        foreach ($recursos_almacen as $r){
                                            if($r->id_usuario != ""){
                                                if(is_numeric($r->id_usuario)){
                                                    $usuario = $this->inventario->listar_usuario($r->id_usuario);
                                                    $usuario_nombre = explode(" ", $usuario->persona_nombre);
                                                    $solicitador = $usuario_nombre[0] . ' ' . $usuario->persona_apellido_paterno;
                                                } else {
                                                    $solicitador = "--";
                                                }
                                            } else {
                                                $solicitador = "--";
                                            }

                                            if ($r->venta_detalle_movimiento_stock < 0) {
                                                $consumido = $consumido + $r->venta_detalle_movimiento_stock;
                                            } else {
                                                $adquirido = $adquirido + $r->venta_detalle_movimiento_stock;
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $alo; ?></td>
                                                <td><?= $r->venta_fecha; ?></td>
                                                <td><?= $r->venta_serie . '-' . $r->venta_correlativo; ?></td>
                                                <td><?= $r->venta_nota_dato; ?> </td>
                                                <td><?= $r->venta_detalle_movimiento_stock; ?> </td>
                                                <td><?= $solicitador; ?> </td>
                                            </tr>
                                            <?php
                                            $alo++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group ocul col-md-12" style="text-align: center; margin-top: 20px;">
                                <h3>Adquirido: <?= number_format($adquirido,2,'.','');?> | Consumido: <?= number_format($consumido,2,'.','');?> | Stock Final: <?= $stock_final;?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-12" style="text-align: right; ">
            <?php
            if(false){
                ?>
                <a class="btn btn-success" href="<?= _SERVER_; ?>index.php?c=inventario&a=excel_kardex_productos&id=<?= $_GET['id'];?>&fecha_inicio=<?= $_GET['fecha_inicio'];?>&fecha_fin=<?= $_GET['fecha_fin'];?>" target="_blank" role="button"><i class="fa fa-file-excel"></i> Generar Excel</a>
                <?php
            }
            ?>
            <a class="btn btn-secondary" href="<?= _SERVER_; ?>Almacen/consultar" role="button"><i class="fa fa-backward"></i> Regresar</a>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>inventario.js"></script>
