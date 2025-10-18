<?php
/**
 * Created by PhpStorm
 * User: LuisSalazar
 * Date: 10/12/2020
 * Time: 12:12 a. m.
 */
?>

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
        <div class="col-md-12">
            <h5 class="font-weight-bold" style="text-align: center">PRODUCTO A AGREGAR</h5>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <label >Familia</label>
            <select class="form-control" onchange="jalar_categorias()" id="id_familia">
                <option value="">Seleccione...</option>
                <?php
                foreach ($familia as $ca){
                    ?>
                    <option value="<?php echo $ca->id_familia;?>"><?php echo $ca->familia_nombre;?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Categoria</label>
            <div id="datos_categoria"></div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <label >Nombre Producto</label>
            <input type="text" class="form-control" onkeyup="mayuscula(this.id)" id="producto_nombre" placeholder="Ingresar Nombre Producto...">
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-3" style="display: none">
            <label >Código del Producto</label>
            <input type="text" class="form-control" onkeyup="mayuscula(this.id)" id="producto_codigo_barra" placeholder="Ingresar Código...">
        </div>
        <div class="col-md-6">
            <label for="">Descripción</label>
            <textarea class="form-control" name="producto_descripcion" id="producto_descripcion" cols="30" rows="3"></textarea>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4 text-center">
            <button id="btn-agregar_producto" type="submit" class="btn btn-primary" style="margin-top:33px; width:70%" onclick="add()"><i class="fa fa-save fa-sm text-white-50"></i> GUARDAR PRODUCTO</button>
        </div>
        <div class="col-md-4"></div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-3" style="display: none">
            <label >Proveedor del Producto</label>
            <select class="form-control" id= "id_proveedor">
                <option value="">Seleccionar Proveedor</option>
                <?php
                foreach($proveedor as $p){
                    ($p->id_proveedor == "1")?$selec='selected':$selec='';
                    ?>
                    <option value="<?php echo $p->id_proveedor;?>" <?= $selec;?>><?php echo $p->proveedor_nombre;?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-2" style="display: none">
            <label >Precio Por Unidad Compra</label>
            <input type="text" class="form-control" id="producto_precio_compra" placeholder="Ingrese Precio Compra..." onkeypress="return validar_numeros_decimales_dos(this.id)">
        </div>
        <div class="col-md-2" style="display: none">
            <label >Precio Unidad Venta</label>
            <input type="text" class="form-control" id="producto_precio_valor" placeholder="Ingrese Precio Venta..." onkeypress="return validar_numeros_decimales_dos(this.id)">
        </div>
        <div class="col-md-2" style="display: none">
            <label >Precio Venta x Mayor</label>
            <input type="text" class="form-control" id="producto_precio_valor_xmayor" placeholder="Ingrese Precio x mayor..." onkeypress="return validar_numeros_decimales_dos(this.id)">
        </div>
        <div class="col-md-2" style="display: none">
            <label >Unidad de Medida</label>
            <select class="form-control" id="id_medida">
                <option value="">Seleccione Una unidad de medida...</option>
                <?php
                foreach ($unimedida as $um){
                    ($um->id_medida == "58")?$selec='selected':$selec='';
                    ?>
                    <option value="<?php echo $um->id_medida;?>" <?= $selec;?>><?php echo $um->medida_nombre;?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-md-2" style="display: none">
            <label >Tipo Afectación</label>
            <select class="form-control" id="id_tipoafectacion">
                <option value="">Seleccione...</option>
                <?php
                foreach ($codigoafectacion as $um){
                    ($um->codigo == "20")?$selec='selected':$selec='';
                    ?>
                    <option value="<?php echo $um->codigo;?>" <?= $selec;?>><?php echo $um->descripcion;?></option>
                    <?php
                }
                ?>
            </select>
        </div>

    </div>
    <br>

    <div class="row">
        <div class="col-md-4" style="display: none;">
            <label >Descripcion Producto</label>
            <input type="text" class="form-control" id="producto_descripcion" placeholder="Ingresar Descripción Producto..." value="--">
        </div>
        <div class="col-md-3" style="display:none;">
            <label >Stock Producto</label>
            <input type="text" class="form-control" id="producto_stock" placeholder="Ingresar Stock Producto..." onkeypress="return validar_numeros(this.id)">
        </div>
    </div>
</div>
<!-- /.box-body -->


<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>inventario.js"></script>

