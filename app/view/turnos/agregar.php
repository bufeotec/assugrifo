<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $_SESSION['controlador'] . '/' . $_SESSION['accion'];?></h1>
        <a href="#"  class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fa fa-retweet fa-sm text-white-50"></i> Volver</a>
    </div>

    <!-- /.row (main row) -->
    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Agregar Turnos</h6>
                </div>
            <section class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-form-label" id="nickname_persona">Turno</label>
                                <select class="form-control" id= "turno_nombre" name="turno_nombre">
                                    <option value="">Seleccionar Turno</option>
                                    <option value="mañana">MAÑANA</option>
                                    <option value="tarde">TARDE</option>
                                    <option value="noche">NOCHE</option>
                                    <option value="madrugada">MADRUGADA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label" id="nickname_persona">Hora Inicio</label>
                                    <input class="form-control" type="text" id="turno_cierre" name="turno_cierre">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label" id="nickname_persona">Hora Fin</label>
                                    <input class="form-control" type="text" id="turno_apertura" name="turno_apertura">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6" style="margin-left: 150px">
                                <button type="button" class="btn btn-success" id="btn-agregar-turno" onclick="agregar()" ><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close fa-sm text-white-50"></i> Cerrar</button>
                            </div>
                        </div>
                <br>
            </section>
            </div>
        </div>
    </div>
</div>

<!-- End of Main Content -->
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>turnos.js"></script>