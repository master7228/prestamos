<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION["nombre"]))
{
    //echo $_SESSION["nombre"];
    header("Location: login.html");
}
else
{
require 'header.php';

if ($_SESSION['Escritorio']==1)
{
?>
    <!-- Inicio Contenido PHP-->
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <header class="main-box-header clearfix">
                    <h2 class="box-title">Escritorio</h2>
                </header>
                
                <div class="row">
                    <div class="main-box-body clearfix" >
                        <div class="col-sm-12">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h4 style="font-size: 17px;">Pagos del Dia</h4>
                                </div>
                                <div class="main-box-body clearfix" id="listadoregistros">
                                    <div class="table-responsive" style="padding: 5px;">
                                        <table class="table table-striped table-bordered table-condensed table-hover" id="tbllistadopagosdeldia">
                                            <thead>
                                                <tr>
                                                    <th>Opciones</th>
                                                    <th>Número de Obligación</th>
                                                    <th>Cliente</th>
                                                    <th>Prestamista</th>
                                                    <th>Fecha</th>
                                                    <th>Monto</th>
                                                    <th>Estado</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="main-box-body clearfix" >
                        <div class="col-sm-12">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h4 style="font-size: 17px;">Prestamos del Día</h4>
                                </div>
                                <div class="main-box-body clearfix" id="listadoregistros">
                                    <div class="table-responsive" style="padding: 5px;">
                                        <table class="table table-striped table-bordered table-condensed table-hover" id="tbllistadoprestamosdeldia">
                                            <thead>
                                                <tr>
                                                    <th>Opciones</th>
                                                    <th>Nro Obligación</th>
                                                    <th>Cliente</th>
                                                    <th>Prestamista</th>
                                                    <th>Fecha</th>
                                                    <th>Monto</th>
                                                    <th>Interes</th>
                                                    <th>Pagos</th>
                                                    <th>Plazo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
</div>
                <div class="main-box-body clearfix" >
                </div>
                
            </div>
        </div>
    </div>
    <!-- Fin Contenido PHP-->
    <?php
}
else
{
 require 'noacceso.php';
}

require 'footer.php';
?>
   
        <script type="text/javascript" src="scripts/concepto.js"></script>
<?php 
}
ob_end_flush();
?>

