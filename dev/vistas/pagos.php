<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
date_default_timezone_set('America/Bogota');
if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'header.php';

if ($_SESSION['Pagos']==1)
{
?>
    <!-- Inicio Contenido PHP-->
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <header class="main-box-header clearfix">
                    <h2 class="box-title">Pagos <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Nuevo</button></h2>
                </header>
                <div class="main-box-body clearfix" id="listadoregistros">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover" id="tbllistado">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Cliente</th>
                                    <th>Prestamista</th>
                                    <th>Fecha</th>
                                    <th>Cuota</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            </table>
                    </div>
                </div>
                
                <div class="main-box-body clearfix" id="formularioregistros">
                    <form name="formulario" id="formulario" method="POST">
                    <input type="hidden" name="fechapago" id="fechapago" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>">
                        <div class="row">
                            <div class="form-group col-sm-5 col-xs-12">
                                <label>Cliente</label>
                                <input type="hidden" name="idpago" id="idpago">
                                <input type="hidden" name="id_user" id="id_user" value="<?php echo $_SESSION['idusuario']; ?>">
                                <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required></select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-sm-12 col-xs-12" id="datos_prestamos">
                            </div> 
                        </div> 
                        
                        <div class="row" id="formPagos" style="display:none">
                            <div class="form-group col-sm-12 col-xs-12 table-responsive" id="datos_plan_pagos">
                                
                            </div> 
                        </div>
                                        
                        <div class="form-group col-xs-12">
                            <!--<button class="btn btn-primary" type="submit" id="btnGuardar" disabled><i class="fa fa-save"></i> Guardar</button>-->
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                        </div>
                    </form>
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

<script>
    var GenidPrestamo= 0;
    var GeninteresCuotaVencida = 0;

    function acentarCuota(idPlanPagos){
        arrayGeneral = [];
        arrayDet = [];
        fechaActual = $('#fechapago').val();
        cuotaSugerida = $('#ValorPagoSugerido').val();
        cuotaPagada = $('#ValorPago').val();
        contCuotasFaltantes = 0;

        if(cuotaPagada < cuotaSugerida ){
            for (let index = 1; index < $('#tableDatosPagos tr').length; index++) {
                if($('#tableDatosPagos tr:eq('+index+') td:eq(5)').text() == ''){
                    contCuotasFaltantes++;
                }                
            }

          

            for (let index = 1; index < $('#tableDatosPagos tr').length; index++) {
                if($('#tableDatosPagos tr:eq('+index+') td:eq(5)').text() == 'Pagar'){

                    //cuotaPlan = parseInt($('#tableDatosPagos tr:eq('+index+') td:eq(1)').text().replace('$','').replace('.',''));
                    recargo = ((cuotaSugerida - cuotaPagada))/contCuotasFaltantes;
                    
                }

                if($('#tableDatosPagos tr:eq('+index+') td:eq(5)').text() == ''){
                    cuotaPlan = parseInt($('#tableDatosPagos tr:eq('+index+') td:eq(1)').text().replace('$','').replace('.',''));
                    cuotaNuevaCalculada = cuotaPlan + recargo;
                    arrayDet.push($('#tableDatosPagos tr:eq('+index+') td:eq(7)').text());
                    arrayDet.push(cuotaNuevaCalculada);
                    arrayGeneral.push(arrayDet);
                    arrayDet = []
                }

                
            }
           // console.log(arrayGeneral);
        }
        var formData = new FormData();
        formData.append('id', idPlanPagos);
        formData.append('valor_pagado', $('#ValorPago').val());
        formData.append('fecha_pago', fechaActual);
        formData.append('observacion', $('#observacion').val());
        formData.append('id_user', $('#id_user').val());
        formData.append('cuotas', JSON.stringify(arrayGeneral));

        $.ajax({
		url: "../ajax/planpagos.php?op=acentarPago",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert('Pago Acentado correctamente');	          
              gestionarPago(GenidPrestamo,GeninteresCuotaVencida);
	    }

	});
    }

    function gestionarPago(idPrestamo,interesCuotaVencida){
        GenidPrestamo = idPrestamo;
        GeninteresCuotaVencida = interesCuotaVencida;
        $('#id_prestamo').val(idPrestamo);
        $('#formPagos').show();
        $('#datos_plan_pagos').empty();
        $.post("../ajax/prestamos.php?op=mostrarPlanPagosParaPago", {
            idprestamo: idPrestamo
        }, function (data, status) {
            data = JSON.parse(data);
         
            /*$('#nro_cuota').val(data['numero_cuota']);
            $('#cuota').val((parseInt(data['valor_cuota'])).toLocaleString('es-CO'));*/
            $('#datos_plan_pagos').empty();
            tabla = '<table id="tableDatosPagos" class="table-striped table-bordered table-condensed table-hover" style="width:100%"><thead><th>Nro. Cuota</th><th>Monto</th><th>Fecha Proximo Pago</th><th>Estado</th><th>Valor a Pagar</th><th>Fecha Pago</th><th>Observacion</th> </thead><tbody>';
       
            band = 0;
            for (let index = 0; index < data.length; index++) {
                campoValor = '';
                linkPago = '';
                textarea = '';
                if (data[index]['estado'] == 1) {
                    estado = 'Pagado';
                    campoValor = '$'+(parseInt(data[index]['valor_pagado'])).toLocaleString('es-CO');
                    linkPago = data[index]['fecha_pago'];
                    textarea = '<textarea readonly disabled>'+data[index]['descripcion']+'</textarea>';
                }else{
                    estado = 'Pendiente';
                    if(band == 0){
                        valorCuotaCalculada = parseInt(data[index]['valor_cuota']);
                        fecha = data[index]['fecha_proximo_pago'];
                        fechaActual = $('#fechapago').val();
                        var fecha1 = new Date(fechaActual);
                        var fecha2 = new Date(fecha);
                        var diferencia_ms = fecha1.getTime() - fecha2.getTime();
                        var diferencia_dias = Math.floor(diferencia_ms / (1000 * 60 * 60 * 24));
                        console.log(diferencia_dias);
                        console.log(interesCuotaVencida);
                        if(diferencia_dias > 0){
                            valorCuotaCalculada = valorCuotaCalculada+(((parseInt(valorCuotaCalculada) * parseInt(interesCuotaVencida))/100)*parseInt(diferencia_dias));
                        }
                        campoValor = '<input type="number" id="ValorPago" name="ValorPago" value="'+valorCuotaCalculada+'"><input type="hidden" id="ValorPagoSugerido" name="ValorPago" value="'+valorCuotaCalculada+'">';
                        if(diferencia_dias > 0){
                            campoValor +='<b style="color:red">Cuota Atrasada</b>';
                        }
                        linkPago = '<a href="#" onclick="acentarCuota('+data[index]['id']+')">Pagar</a>';
                        textarea = '<textarea id="observacion" name="obaservacion"></textarea>';
                        band = 1;

                    }
                }
                
                
                tabla += '<tr id="'+data[index]['id']+'"><td>'+data[index]['numero_cuota']+'</td><td>$'+ (parseInt(data[index]['valor_cuota'])).toLocaleString('es-CO')+'</td><td>'+data[index]['fecha_proximo_pago'].replace(' 00:00:00','')+' </td><td>'+estado+' </td><td>'+campoValor+'</td><td><b>'+linkPago+'</b></td><td>'+textarea+'</td><td style="display:none">'+data[index]['id']+'</td></tr> ';
              
            }
            tabla += '</tbody></table>';
            

            $('#datos_plan_pagos').append(tabla);
            

        });

        
    }

    function getDataPrestamosByCliente(idcliente)
    {
        $('#datos_plan_pagos').empty();
        $('#datos_prestamos').empty();
        $.post("../ajax/prestamos.php?op=mostrarbycliente", {
            idcliente: idcliente
        }, function (data, status) {
            data = JSON.parse(data);
            
            tabla = '<table class="table-striped table-bordered table-condensed table-hover" style="width:100%"><thead><th>Id Prestamo</th><th>Monto</th><th>Fecha</th><th>Estado</th><th>Gestionar</th></thead><tbody>';
            console.log(data);
            for (let index = 0; index < data.length; index++) {
                if (data[index].estado == 1) {
                    estado = 'Activo';
                }else{
                    estado = 'Inactivo';
                }
                tabla += '<tr id="'+data[index].idprestamo+'"><td>'+data[index].idprestamo+'</td><td>$'+ (parseInt(data[index].monto)).toLocaleString('es-CO')+'</td><td>'+data[index].fecha+' </td><td>'+estado+' </td><td><b><a href="#" onclick="gestionarPago('+data[index].idprestamo+','+data[index].interesCuotaVencida+')">Gesitonar</a></b></td></tr> ';
                
            }
            tabla += '</tbody></table>';
            

            $('#datos_prestamos').append(tabla);
            
    

        });
    }

    $(document).ready(function($){
    
        $('#idcliente').on('change',function(){
            getDataPrestamosByCliente($(this).val());
        });
    });
</script>
        <script type="text/javascript" src="scripts/pagos.js"></script>
<?php 
}
ob_end_flush();
?>