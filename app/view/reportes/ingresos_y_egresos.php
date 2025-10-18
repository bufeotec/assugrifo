<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <section class="container-fluid">
        <hr><h2 class="concss">
            <a href="<?= _SERVER_?>"><i class="fa fa-fire"></i> INICIO</a> >
            <a href="<?= _SERVER_?>Reporte"><i class="<?php echo $_SESSION['icono'];?>"></i> <?php echo $_SESSION['controlador'];?></a> >
            <i class="<?php echo $_SESSION['icono'];?>"></i> REPORTE POR DÍA
        </h2><hr>
    </section>
    <section class="content" style="background-color: white;box-shadow: 2px 2px 2px #888888;border-radius: 5px; padding: 10px; margin: 10px; min-height: 500px">
        <!-- /.row -->
            <input type="hidden" id="enviar_fecha" name="enviar_fecha" value="1">
            <div class="row">
                <div class="col-lg-8"></div>
                <div class="col-lg-1" >
                    <button class="btn btn-success" onclick="fecha_reporte(1)"><i class="fa fa-angle-double-left"></i></button>
                </div>
                <div class="col-lg-2">
                    <label style="text-align: center" id="fecha_hoy"><?= $fecha_hoy;?></label>
                </div>
                <div class="col-lg-1" >
                    <button class="btn btn-success" onclick="fecha_reporte(2)"><i class="fa fa-angle-double-right"></i></button>
                </div>

            </div>
        <br><br>
        <!-- Main row -->
        <!-- /.row (main row) -->
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div id="tabla_ingresos"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-5">

                <div id="tabla_egresos"></div>
            </div>

            <br>
            <div class="col-lg-5">
                <div id="tabla_datos"></div>
            </div>
        </div>
        <div class="row" style="display:none;">
            <div class="col-lg-12">
                <center><a onclick="pdf_reporte()" style="color: white" class="btn btn-primary"><i class="fa fa-print"></i> Imprimir Reporte</a></center>
            </div>
        </div>

    </section>

</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>

<script>

   window.onload(fecha_reporte(0));

    function fecha_reporte(identificador){
        var fecha = $("#fecha_hoy").html();

        $.ajax({
            type: "POST",
            url: urlweb + "api/Reporte/datos_x_fecha",
            data: "fecha="+fecha + "&identificador=" + identificador,
            dataType: 'json',
            success:function (r) {
                $("#tabla_ingresos").html(r.tabla_ingresos);
                $("#tabla_egresos").html(r.tabla_egresos);
                $("#tabla_datos").html(r.tabla_datos);
                $("#fecha_hoy").html(r.nueva_fecha);
            }
        });
    }

    function pdf_reporte(){
        var fecha = $("#fecha_hoy").html();
        $.ajax({
            type: "POST",
            url: urlweb + "api/Reporte/ingresos_egresos_pdf",
            data: "fecha=" + fecha,
            dataType: 'json',
        });
    }
</script>
