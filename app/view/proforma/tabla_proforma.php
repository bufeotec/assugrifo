<?php
/**
 * Created by PhpStorm
 * User: KYLLLAX
 * Date: 20/05/2021
 * Time: 18:25
 */
?>
<div class="row" style="display: none">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <button class="btn btn-secondary btn-xs" type="button" style="width: 100%" data-toggle="modal" data-target="#largeModal"><i class="fa fa-plus"></i> Agregar Producto</button>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-10">
        <table class="table table-bordered table-hover" style="border-color: black">
            <thead>
            <tr style="background-color: #ebebeb">
                <th>COD.</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Accion</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totales = count($_SESSION['productos']);
            $monto = 0;
            $igv = 0;
            $gravada = 0;
            $inafecta = 0;
            $exonerada = 0;
            $gratuita = 0;
            $ICBPER = 0;
            $des_global = 0;
            $des_total = 0;
            $des_producto = 0;
            $igv_porcentaje=0.18;
            $fecha_bolsa = date("Y");
            if ($fecha_bolsa == "2020"){
                $impuesto_icbper = 0.20;
            } else if ($fecha_bolsa == "2021"){
                $impuesto_icbper = 0.30;
            } else if ($fecha_bolsa == "2022") {
                $impuesto_icbper = 0.40;
            } else{
                $impuesto_icbper = 0.50;
            }

            if($totales != 0){
                $item = 1;
                foreach ($_SESSION['productos'] as $p){
                    if($p[5] == 1){
                        $ver = "Por Menor";
                    }else{
                        $ver = "Por Mayor";
                    }
                    $subtotal = round($p[3] * $p[2],2);
                    $monto = round($monto + $subtotal, 2);
                ?>
                <tr> <!--De esta tapla se jala los valores por la posicion de los arrays-->
                    <td><?php echo $item;?></td>
                    <td><?php echo $ver;?></td>
                    <td>
                        <textarea type="text" style="width: 90%" class="form-control" name="valor_concepto_nuevo<?= $item;?>" onblur="cambiar_descripcion_proforma(<?= $item;?>,'<?= $p[0];?>')" id="valor_concepto_nuevo<?= $item;?>" cols="30" rows="2"><?php echo $p[1];?></textarea>
                        <!--<input type="text" class="form-control" style="width: 100%" id="texto_descripcion" onblur="editar_texto_tabla(<?= $p[0];?>)" value="<?= $p[1];?>">-->
                    </td>
                    <td>s/. <?php echo $p[2];?></td>
                    <td>
                        <!-- <input type="text" class="form-control" style="width: 60%" id="valor_nueva_cantidad<?= $item;?>" onblur="editar_cantidad_tabla_proforma(<?= $item;?>,'<?= $p[0];?>')" value="<?php echo $p[3];?>">-->
                        <?php echo $p[3];?>
                    </td>
                    <td>s/. <?php echo $subtotal;?></td>
                    <td><a type="button" class="btn btn-xs btn-warning btne" onclick="quitarProducto(<?php echo $p[0];?>)" ><i class="fa fa-times"></i> Quitar</a></td>
                </tr>
                <?php
                    $item++;
                    $afectacion = $p[4];
                    if($afectacion == "10"){
                        $gravada = $gravada + $subtotal;
                    }
                    if($afectacion == "20"){
                        $exonerada = $exonerada + $subtotal;
                    }
                    if($afectacion == "30"){
                        $inafecta = $inafecta + $subtotal;
                    }
                    if($afectacion == "21"){
                        $gratuita = $gratuita + $subtotal;
                    }
                    if($p[1] == "BOLSA PLASTICA"){
                        $ICBPER = $impuesto_icbper * $p[3];
                    }
                    $des_producto = $des_producto + $p[5];
                    $igv = $gravada * $igv_porcentaje;
                    $monto = $gravada + $exonerada + $inafecta + $igv + $ICBPER;
                }
                $des_total = $des_total + $des_producto;
            }
            ?>
            </tbody>
        </table>
        <!--<div class="row">
            <div id="espacio" class="col-lg-8"></div>

            <div class="col-lg-4">
                <h5>OP. GRATUITA: s/. <?php echo number_format($gratuita ,2);?></h5>
                <input type="hidden" value="<?php echo $gratuita;?>" id="gratuita">
                <h5>OP. EXONERADA: s/. <?php echo number_format($exonerada ,2);?></h5>
                <input type="hidden" value="<?php echo $exonerada;?>" id="exonerada">
                <h5>OP. INAFECTA: s/. <?php echo number_format($inafecta , 2);?></h5>
                <input type="hidden" value="<?php echo $inafecta;?>" id="inafecta">
                <h5>OP. GRAVADA: s/. <?php echo number_format($gravada , 2);?></h5>
                <input type="hidden" value="<?php echo $gravada;?>" id="gravada">
                <h5>IGV: s/. <?php echo number_format($igv , 2);?></h5>
                <input type="hidden" value="<?php echo $igv;?>" id="igv">
                <?php
                if ($ICBPER > 0){ ?>
                    <h5>ICBPER: s/. <?php echo number_format($ICBPER , 2);?></h5>
                <?php }
                ?>
                <input type="hidden" value="<?php echo $ICBPER;?>" id="icbper">
                <h4>PRECIO TOTAL: s/. <?php echo number_format($monto , 2);?></h4>
                <input type="hidden" value="<?php echo $monto;?>" id="montototal">
                <h5>Pagó con: s/. <span id="pago_con">0.00</span></h5>
                <input type="hidden" id="pago_con_">
                <h5>Vuelto: s/. <span id="vuelto">0.00</span></h5>
                <input type="hidden" id="vuelto_">
                <input type="hidden" id="descuento_global">
            </div>
        </div>-->
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-9" style="text-align: right">
                        <label for="" style="font-size: 14px;">% DESCUENTO GLOBAL</label><br>
                        <label for="" style="font-size: 14px;">DESCUENTO GLOBAL (-)</label><br>
                        <label for="" style="font-size: 14px;">DESCUENTO X ITEM (-)</label><br>
                        <label for="" style="font-size: 14px;">DESCUENTO TOTAL (-)</label><br>
                        <?php
                        if($gravada > 0){ ?>
                            <label for="" style="font-size: 14px;">OP. GRAVADAS</label><br>
                            <label for="" style="font-size: 14px;">IGV(18%)</label><br>
                            <?php
                        }
                        ?>

                        <label for="" style="font-size: 14px;">OP. EXONERADAS</label><br>
                        <?php
                        if($inafecta > 0){ ?>
                            <label for="" style="font-size: 14px;">OP. INAFECTAS</label><br>
                            <?php
                        }
                        ?>
                        <label for="" style="font-size: 14px;">OP. GRATUITAS</label><br>
                        <label for="" style="font-size: 14px;">ICBPER</label><br>
                        <label for="" style="font-size: 17px;"><strong>TOTAL</strong></label><br>
                        <label for="" style="font-size: 14px;">PAGÓ CON</label><br>
                        <label for="" style="font-size: 14px;">VUELTO</label>
                    </div>
                    <div class="col-lg-2" style="text-align: right;" >
                        <input style="width: 45%; border-color: #87adbd" onkeyup="calcular_descuento(this.value)" type="text" id="des_global_porcentaje_"><br>
                        <label for="" style="font-size: 14px;"><span id="des_global_"><?php echo number_format($des_global, 2);?></span></label><br>
                        <label for="" style="font-size: 14px;"><span id="des_item_"><?php echo number_format($des_producto, 2);?></span></label><br>
                        <label for="" style="font-size: 14px;"><span id="des_total_"><?php echo number_format($des_total, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $des_global;?>" id="des_global">
                        <input type="hidden" value="<?php echo $des_producto;?>" id="des_item">
                        <input type="hidden" value="<?php echo $des_total;?>" id="des_total">

                        <?php
                        if($gravada > 0){ ?>
                            <label for="" style="font-size: 14px;"><span id="gravada_"><?php echo number_format($gravada, 2);?></span></label><br>
                            <label for="" style="font-size: 14px;"><span id="igv_"><?php echo number_format($igv, 2);?></span></label><br>
                            <?php
                        }
                        ?>
                        <input type="hidden" value="<?php echo $gravada;?>" id="gravada">
                        <input type="hidden" value="<?php echo $gravada;?>" id="gravada__">

                        <input type="hidden" value="<?php echo $igv;?>" id="igv">
                        <label for="" style="font-size: 14px;"><span id="exonerada_"><?php echo number_format($exonerada, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $exonerada;?>" id="exonerada">
                        <input type="hidden" value="<?php echo $exonerada;?>" id="exonerada__">
                        <?php
                        if($inafecta > 0){ ?>
                            <label for="" style="font-size: 14px;"><span id="inafecta_"><?php echo number_format($inafecta, 2);?></span></label><br>
                            <?php
                        }
                        ?>
                        <input type="hidden" value="<?php echo $inafecta;?>" id="inafecta">
                        <input type="hidden" value="<?php echo $inafecta;?>" id="inafecta__">
                        <label for="" style="font-size: 14px;"><span id="gratuita_"><?php echo number_format($gratuita, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $gratuita;?>" id="gratuita">
                        <label for="" style="font-size: 14px;"><span id="icbper_"><?php echo number_format($ICBPER, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $ICBPER;?>" id="icbper">
                        <label for="" style="font-size: 17px;"><span id="montototal_"><?php echo number_format($monto, 2);?></span></label><br>
                        <input type="hidden" value="<?php echo $monto;?>" id="montototal">
                        <input type="hidden" value="<?php echo $monto;?>" id="montototal__">
                        <label for="" style="font-size: 14px;"><span id="pago_con">0.00</span></label><br>
                        <input type="hidden" id="pago_con_">
                        <label for="" style="font-size: 14px;"><span id="vuelto">0.00</span></label>
                        <input type="hidden" id="vuelto_">
                        <input type="hidden" id="descuento_global">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function calcular_descuento(valor){
        var desc_porcentaje = valor / 100;
        var des_item_ = $('#des_item').val() * 1;
        var montototal = $('#montototal__').val();
        var exonerada = $('#exonerada__').val();
        var inafecta = $('#inafecta__').val();
        var gravada = $('#gravada__').val();

        //var montototal = $('#montototal').val();
        var desc_total_ = montototal * desc_porcentaje;
        var desc_total = desc_total_.toFixed(2);
        $('#des_global_').html(desc_total);
        $('#des_global').val(desc_total);
        var total_descuento = desc_total_ + des_item_;
        $('#des_total_').html(total_descuento.toFixed(2));
        $('#des_total').val(total_descuento.toFixed(2));
        $('#descuento_global').val(desc_porcentaje);
        var total_exonerado = 0;
        var total_gravada = 0;
        var igv = 0;
        var total_ = 0;
        if(exonerada > 0){
            var desc_exonerado_ = exonerada * desc_porcentaje * 1;
            var desc_exonerado = exonerada - desc_exonerado_;
            total_exonerado = desc_exonerado.toFixed(2);
            $('#exonerada_').html(total_exonerado);
            $('#exonerada').val(total_exonerado);
        }
        if(gravada > 0){
            var desc_gravada_ = gravada * desc_porcentaje * 1;
            var desc_gravada = gravada - desc_gravada_;
            total_gravada = desc_gravada.toFixed(2);
            var igv_ = desc_gravada * 0.18 ;
            igv = igv_.toFixed(2) * 1;
            $('#gravada_').html(total_gravada);
            $('#gravada').val(total_gravada);
            $('#igv_').html(igv);
            $('#igv').val(igv);
        }
        var exo = $('#exonerada').val() * 1;
        var gra = $('#gravada').val() * 1;
        var ig = $('#igv').val() * 1;
        total_ = (exo + gra + ig) * 1;
        var total = total_.toFixed(2);
        var total = total_.toFixed(2);
        $('#montototal_').html(total);
        $('#montototal').val(total);

    }

    function cambiar_descripcion_proforma(item,id){
        let variable_unida = id+'_'+item;
        console.log(variable_unida)
        var valor_concepto_nuevo = $("#valor_concepto_nuevo"+item).val();
        $.ajax({
            type:"POST",
            url: urlweb + "api/Proforma/editar_descripcion",
            data : {
                "valor_concepto_nuevo" : valor_concepto_nuevo,
                "id" : id,
                "union" : variable_unida,
                "item" : item
            },
            success:function (r) {
                switch (r) {
                    case "1":
                        respuesta('¡Concepto editado correctamente!', 'success');
                        $('#tabla_productos').load(urlweb + 'Ventas/tabla_productos');
                        break;
                    case "2":
                        respuesta('Hubo Un Error al editar');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

    function editar_cantidad_tabla_proforma(item,id){
        var valor_nueva_cantidad = $("#valor_nueva_cantidad"+item).val() * 1;
        var cadena = "valor_nueva_cantidad=" + valor_nueva_cantidad + "&id=" + id;
        $.ajax({
            type:"POST",
            url: urlweb + "api/Ventas/editar_cantidad_tabla",
            data : cadena,
            success:function (r) {
                switch (r) {
                    case "1":
                        respuesta('¡Editado correctamente!', 'success');
                        $('#tabla_productos').load(urlweb + 'Ventas/tabla_productos');
                        break;
                    case "2":
                        respuesta('Hubo Un Error al editar');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }

</script>