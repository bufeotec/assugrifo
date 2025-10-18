<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 10/05/2021
 * Time: 6:00 p. m.
 */
?>
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <hr><h2 class="concss">
        <a href="<?= _SERVER_;?>"><i class="fa fa-fire"></i> INICIO</a> >
        <i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['accion'];?>
    </h2><hr>
    <div class="d-sm-flex align-items-center justify-content-end mb-4">
        <a class="btn btn-block btn-success btn-sm" style="width: 10%" href="<?php echo _SERVER_;?>Proforma/realizar_proforma">
            <i class="fa fa-retweet"></i> VOLVER
        </a>
    </div>

    <form method="post" action="<?= _SERVER_ ?>Proforma/ver_proforma">
        <input type="hidden" id="enviar_dato" name="enviar_dato" value="1">
        <div class="row">
            <div class="col-lg-2">
                <label for="turno">Desde:</label>
                <input type="date" class="form-control" id="fecha_filtro" name="fecha_filtro" step="1" value="<?php echo $fecha_i;?>">
            </div>
            <div class="col-lg-2">
                <label for="turno">Hasta:</label>
                <input type="date" class="form-control" id="fecha_filtro_fin" name="fecha_filtro_fin" value="<?php echo $fecha_f;?>">
            </div>
            <div class="col-lg-3">
                <button style="margin-top: 35px;" class="btn btn-success" ><i class="fa fa-search"></i> Buscar Proformas</button>
            </div>
        </div>
    </form>
    <br>

    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-earning" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="text-capitalize">
                                    <tr>
                                        <th>#</th>
                                        <th>Correlativo</th>
                                        <th>Cliente</th>
                                        <th>Telefono</th>
                                        <th>Fecha Vigencia</th>
                                        <th>PDF</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $a = 1;
                                    foreach ($proformas as $al){
                                        if($al->id_moneda == 1){
                                            $moneda = "SOLES";
                                        }else{
                                            $moneda = "DOLARES";
                                        }
                                        if(empty($al->cliente_telefono)){
                                            $telefono = "---";
                                        }else{
                                            $telefono = $al->cliente_telefono;
                                        }
                                        ?>
                                        <tr <?= $stylee?>>
                                           <td><?= $a;?></td>
                                            <td>PROFORMA N° <?= $al->proforma_correlativo;?></td>
                                           <td><?= $al->cliente_nombre.$al->cliente_razonsocial;?></td>
                                           <td><?= $telefono;?></td>
                                           <td><?= date('d-m-Y',strtotime($al->proforma_fecha_vigencia));?></td>
                                           <td>
                                               <a target="" href="<?php echo _SERVER_ . 'Proforma/proforma_pdf/'. $al->id_proforma; ?>"><i class="fa fa-print"></i> Ver Proforma</a>
                                           </td>
                                           <td>
                                               <button id="btn-eliminar_profroma<?= $al->id_proforma;?>" class="btn btn-sm btn-danger btne" onclick="preguntar('¿Está seguro que desea eliminar esta proforma?','eliminar_proforma','Si','No',<?= $al->id_proforma;?>)"><i class="fa fa-trash"></i></button>
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
    </section>
    <br>
    <br>
</div>


<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>proforma.js"></script>

