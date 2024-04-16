<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'header.php';

if ($_SESSION['Prestamos']==1)
{
?>
    <!-- Inicio Contenido PHP-->
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <header class="main-box-header clearfix">
                     <h2 class="box-title">Prestamos <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Nuevo</button></h2>
                </header>
                <div class="main-box-body clearfix" id="listadoregistros">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover" id="tbllistado">
                            <thead>
                                <tr>
                                   <th>Opciones</th>
                                    <th>Clientes</th>
                                    <th>Usuarios</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Interes</th>
                                    <th>Pagos</th>
                                    <th>Plazo</th>
                                    <th>Estado</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                </div>
                </div>
                
                   <div class="main-box-body clearfix" id="formularioregistros">
                    <form name="formulario" id="formulario" method="POST">
                        <div class="row">
                           <div class="form-group col-md-6 col-sm-9 col-xs-12">
                            <label>Cliente</label>
                            <input type="hidden" name="idprestamo" id="idprestamo">
                            <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required></select>                           
                        </div>
                        <div class="form-group col-sm-6 col-xs-12">
                           <label>Usuario</label>
                            
                            <input type="text" name="nombreusuario" id="nombreusuario" class="form-control" value="<?php echo $_SESSION['nombre']; ?>" disabled>
                            <input type="hidden" class="form-control" name="fprestamo" id="fprestamo" required>
                            <input type="hidden" class="form-control" name="idusuario" id="idusuario" value="<?php echo $_SESSION['idusuario']; ?>">
                        </div>
                                                  
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-xs-12" id="datos_cliente">
                            </div> 
                        </div> 
                        <div class="row">
                        <div class="form-group col-sm-3 col-xs-12">
                            <label>Monto</label>
                            <input type="number" name="monto" id="monto" class="form-control" placeholder="Monto" required>
                            <input type="hidden"  id="valor" >
                        </div>
                        <div class="form-group col-sm-3 col-xs-12">
                            <label>Interes</label>
                            <select class="form-control select-picker" name="interes" id="interes" required>
                              <option value="20" selected>20 %</option>
                              <option value="15">15 %</option>
                              <option value="13">13 %</option>
                              <option value="10">10 %</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3 col-xs-12">
                            <label>Fecha Primera Cuota</label>
                            <input type="date" name="finicio" id="finicio" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        </div>
                        <div class="row">
                             <div class="form-group col-sm-3 col-xs-12">
                            <label>Forma Pago</label>
                            <select class="form-control select-picker" name="formapago" id="formapago" required>
                              <option value="Diario">Diario</option>
                              <option value="Semanal">Semanal</option>
                              <option value="Quincenal">Quincenal</option>
                              <option value="Mensual">Mensual</option>
                            </select>
                        </div>
                        <div class="row">
                             <div class="form-group col-sm-3 col-xs-12">
                                <label>Plazo en Meses</label>
                                <select class="form-control select-picker" name="plazo" id="plazo" required>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-3 col-xs-12">
                                <label>Interés Cuota Vencida</label>
                                <select class="form-control select-picker" name="interescuotavencidad" id="interescuotavencidad" required>
                                    <option value="20" selected>20 %</option>
                                    <option value="15">15 %</option>
                                    <option value="13">13 %</option>
                                    <option value="10">10 %</option>
                                </select>
                            </div>
                        </div>
                         
                         </div>

                         


                        <div class="form-group col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"style="display:none" ><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            <button class="btn btn-primary" id="btn_calcular" type="button"><i class="fa fa-arrow-circle-left"></i> Calcular</button>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-xs-12" id="plan_pagos">
                            </div> 
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
    var fechaHoraLocal = new Date();
    var diferenciaMinutos = fechaHoraLocal.getTimezoneOffset();
    var fechaHoraBogota = new Date(fechaHoraLocal.getTime() - (diferenciaMinutos * 60000) - (5 * 3600000)); // Bogotá está 5 horas detrás de UTC
    var fechaPrestamo = fechaHoraBogota.getFullYear()+'-'+(fechaHoraBogota.getMonth()+1)+'-'+fechaHoraBogota.getDate()+' '+fechaHoraBogota.getHours()+':'+fechaHoraBogota.getMinutes();
    var fechaInicioPago = fechaHoraBogota.getFullYear()+'-'+(fechaHoraBogota.getMonth()+1)+'-'+fechaHoraBogota.getDate()+' '+fechaHoraBogota.getHours()+':'+fechaHoraBogota.getMinutes();
   
    $('#fprestamo').val(fechaPrestamo);


    function fechaSumaDiaria(cantidadDias) {
        var arrayFechas = [];
        if(fechaPrestamo != fechaInicioPago ){
            var fechaCalculo = new Date(fechaInicioPago);
            var fechaPrestaPrimeraCuota = new Date(fechaPrestamo);
            arrayFechas.push(fechaPrestaPrimeraCuota.toISOString().slice(0, 10));
        }else{
            var fechaCalculo = new Date(fechaPrestamo);
        }
        
        
        arrayFechas.push(fechaCalculo.toISOString().slice(0, 10));
        for (var i = 0; i <= cantidadDias; i++) {
            fechaCalculo.setDate(fechaCalculo.getDate() + 1); 
            arrayFechas.push(fechaCalculo.toISOString().slice(0, 10)); 
        }
        return arrayFechas;
    }

    function fechaSumaSemanal(cantidadDias) {
        var arrayFechas = [];
        if(fechaPrestamo != fechaInicioPago ){
            var fechaCalculo = new Date(fechaInicioPago);
            var fechaPrestaPrimeraCuota = new Date(fechaPrestamo);
            arrayFechas.push(fechaPrestaPrimeraCuota.toISOString().slice(0, 10));
        }else{
            var fechaCalculo = new Date(fechaPrestamo);
        }
        
        arrayFechas.push(fechaCalculo.toISOString().slice(0, 10));
        for (var i = 0; i <= cantidadDias; i++) {
            fechaCalculo.setDate(fechaCalculo.getDate() + 1 * 7); 
            arrayFechas.push(fechaCalculo.toISOString().slice(0, 10)); 
        }
        return arrayFechas;
    }

    function fechaSumaQuincenal(cantidadDias) {
        var arrayFechas = [];
        if(fechaPrestamo != fechaInicioPago ){
            var fechaCalculo = new Date(fechaInicioPago);
            var fechaPrestaPrimeraCuota = new Date(fechaPrestamo);
            arrayFechas.push(fechaPrestaPrimeraCuota.toISOString().slice(0, 10));
        }else{
            var fechaCalculo = new Date(fechaPrestamo);
        }
        
        arrayFechas.push(fechaCalculo.toISOString().slice(0, 10));
        for (var i = 0; i <= cantidadDias; i++) {
            fechaCalculo.setDate(fechaCalculo.getDate() + 1 * 15); 
            arrayFechas.push(fechaCalculo.toISOString().slice(0, 10)); 
        }
        return arrayFechas;
    }

    function fechaSumaMensual(cantidadDias) {
        var arrayFechas = [];
        if(fechaPrestamo != fechaInicioPago ){
            var fechaCalculo = new Date(fechaInicioPago);
            var fechaPrestaPrimeraCuota = new Date(fechaPrestamo);
            arrayFechas.push(fechaPrestaPrimeraCuota.toISOString().slice(0, 10));
        }else{
            var fechaCalculo = new Date(fechaPrestamo);
        }
        
        arrayFechas.push(fechaCalculo.toISOString().slice(0, 10));
        for (var i = 0; i <= cantidadDias; i++) {
            fechaCalculo.setDate(fechaCalculo.getDate() + 1 * 30); 
            arrayFechas.push(fechaCalculo.toISOString().slice(0, 10)); 
        }
        return arrayFechas;
    }

    function getDataCliente(idcliente)
    {
        $.post("../ajax/clientes.php?op=mostrar", {
            idcliente: idcliente
        }, function (data, status) {
            data = JSON.parse(data);
            $('#datos_cliente').empty();
            ingresos = parseInt(data[0].ingresos).toLocaleString('es-CO');
            egresos = parseInt(data[0].egresos).toLocaleString('es-CO');
            cuotasugerida = parseInt((data[0].ingresos - data[0].egresos) * 0.3).toLocaleString('es-CO');
            //valorymeses = 

            var capacidadPagoMensual = (data[0].ingresos  - data[0].egresos) * 0.3; // capacidad de pago mensual en pesos
            var tasaInteres = 0.20; // tasa de interés (20%)
            var plazo = 4; // plazo del préstamo en meses
            var valorymeses = parseInt(((capacidadPagoMensual * ((1 - Math.pow(1 + tasaInteres, -plazo)) / tasaInteres)).toFixed(0))).toLocaleString('es-CO')+' a 4 Meses';
            $('#datos_cliente').append('<table class="table-striped table-bordered table-condensed table-hover" style="width:100%"><tbody><tr><td><b>Nombre:</b>'+data[0].nombre+'</td><td><b>Documento:</b> '+data[0].cedula+' </td></tr><tr><td><b>Direccion:</b> '+data[0].direccion+' </td><td><b>Telefono:</b> '+data[0].telefono+'</td></tr><tr><td><b>Ingresos:</b> $'+ingresos+' </td><td><b>Egresos:</b> $'+egresos+' </td></tr> <tr><td><b>Maximo Cuota Sugerido:</b> $'+cuotasugerida+' </td><td><b>Valor y Meses Sugeridos:</b> $'+valorymeses+' </td></tr> </tbody></table>');
    

        })
    }
$(document).ready(function($){
    document.getElementById('finicio').value = fechaHoraBogota.getFullYear()+'-'+(fechaHoraBogota.getMonth()+1).toString().padStart(2, '0')+'-'+fechaHoraBogota.getDate();
  
    $('#finicio').on('change', function(){
        fechaInicioPago = $('#finicio').val()+' '+fechaHoraBogota.getHours()+':'+fechaHoraBogota.getMinutes();
    });
    
    $('#idcliente').on('change',function(){
        getDataCliente($(this).val());
    });

$('#btn_calcular').on('click',function(){
    $('#plan_pagos').empty();
    if($('#idcliente').val() == ''){
        alert('Debe seleccionar un cliente');
        return;
    }
    if($('#monto').val() == ''){
        alert('Ingresa el monto a prestar');
        return;
    }
    if($('#interes').val() == null){
        alert('Selecciona el interés');
        return;
    }
    if($('#formapago').val() == null){
        alert('Selecciona la forma de pago');
        return;
    }
    if($('#plazo').val() == ''){
        alert('Selecciona el plazo');
        return;
    }
    $('#btnGuardar').show();
    tablePlan = '<table id="tablePlanPagos" class="table-striped table-bordered table-condensed table-hover" style="width:100%"><thead><tr><th>Cuota Nro.</th><th>Fecha de Pago</th><th>Valor</th></tr></thead><tbody>';

            switch ($('#formapago').val()) {
                case 'Diario':
                    interesMensual = ($('#monto').val()*$('#interes').val())/100;
                    mesactual = fechaHoraBogota.getMonth();
                    if(mesactual == 1 || mesactual == 3 || mesactual == 5 || mesactual == 7 || mesactual == 8 || mesactual == 10 || mesactual == 12){
                        capitaldiferido = $('#monto').val()/($('#plazo').val()*31);
                        cantidadCuotas = $('#plazo').val()*31;
                        interesDiario = interesMensual/31;
                    }else{
                        capitaldiferido = $('#monto').val()/($('#plazo').val()*30);
                        cantidadCuotas = $('#plazo').val()*30;
                        interesDiario = interesMensual/30;
                    }
                    var valorPrimerCuota = (Math.ceil(interesDiario)).toFixed(0); 
                    var valorcuota = (Math.ceil(capitaldiferido) + Math.ceil(interesDiario)).toFixed(0);        
                    var fechasPlan = fechaSumaDiaria(cantidadCuotas);                
                    for (let index = 0; index <= cantidadCuotas; index++) {
                        if(index == 0){
                            tablePlan += '<tr id="row'+index+'"><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(valorPrimerCuota))).toLocaleString('es-CO');+' </td></tr>';
                        }else if(index == cantidadCuotas){
                            tablePlan += '<tr id="row'+index+'"><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(capitaldiferido))).toLocaleString('es-CO');+' </td></tr>';
                        }else{
                            tablePlan += '<tr id="row'+index+'"><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(valorcuota))).toLocaleString('es-CO');+' </td></tr>';
                        }
                        
                    }
                    tablePlan += '</tbody></table>';
                    break;
                case 'Semanal':
                    var interesMensual = ($('#monto').val()*$('#interes').val())/100;
                    var mesactual = fechaHoraBogota.getMonth();
                    var capitaldiferido = $('#monto').val()/($('#plazo').val()*4);
                    var cantidadCuotas = $('#plazo').val()*4;
                    var interesSemanal = interesMensual/4;
                    var valorPrimerCuota = (Math.ceil(interesSemanal)).toFixed(0); 
                    var valorcuota = (Math.ceil(capitaldiferido) + Math.ceil(interesSemanal)).toFixed(0);        
                    var fechasPlan = fechaSumaSemanal(cantidadCuotas);                
                    for (let index = 0; index <= cantidadCuotas; index++) {
                        if(index == 0){
                            tablePlan += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(valorPrimerCuota))).toLocaleString('es-CO');+' </td></tr>';
                        }else if(index == cantidadCuotas){
                            tablePlan += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(capitaldiferido))).toLocaleString('es-CO');+' </td></tr>';
                        }else{
                            tablePlan += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(valorcuota))).toLocaleString('es-CO');+' </td></tr>';
                        }
                    }
                    tablePlan += '</tbody></table>';
                    break;
                case 'Quincenal':
                    var interesMensual = ($('#monto').val()*$('#interes').val())/100;
                    var mesactual = fechaHoraBogota.getMonth();
                    var capitaldiferido = $('#monto').val()/($('#plazo').val()*2);
                    var cantidadCuotas = $('#plazo').val()*2;
                    var interesQuincenal = interesMensual/2;
                    var valorPrimerCuota = (Math.ceil(interesQuincenal)).toFixed(0); 
                    var valorcuota = (Math.ceil(capitaldiferido) + Math.ceil(interesQuincenal)).toFixed(0);        
                    var fechasPlan = fechaSumaQuincenal(cantidadCuotas);                
                    for (let index = 0; index <= cantidadCuotas; index++) {
                        if(index == 0){
                            tablePlan += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(valorPrimerCuota))).toLocaleString('es-CO');+' </td></tr>';
                        }else if(index == cantidadCuotas){
                            tablePlan += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(capitaldiferido))).toLocaleString('es-CO');+' </td></tr>';
                        }else{
                            tablePlan += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(valorcuota))).toLocaleString('es-CO');+' </td></tr>';
                        }
                    }
                    tablePlan += '</tbody></table>';
                    break;
                default:
                    var interesMensual = ($('#monto').val()*$('#interes').val())/100;
                    var mesactual = fechaHoraBogota.getMonth();
                    var capitaldiferido = $('#monto').val()/($('#plazo').val());
                    var cantidadCuotas = $('#plazo').val();
                    if(fechaPrestamo != fechaInicioPago){
                        var fecha1 = new Date(fechaInicioPago); // Fecha más reciente
                        var fecha2 = new Date(fechaPrestamo); // Fecha más antigua

                        // Calcular la diferencia en milisegundos entre las dos fechas
                        var diferencia = fecha1 - fecha2;

                        // Convertir la diferencia de milisegundos a días
                        var dias = diferencia / (1000 * 60 * 60 * 24);

                        // Redondear el resultado a un número entero
                        dias = Math.round(dias);
                        var valorPrimerCuota = Math.ceil(interesMensual+((interesMensual/30)*dias)).toFixed(0);
                    }else{
                        var valorPrimerCuota = Math.ceil(interesMensual).toFixed(0); 
                    }
                     
                    var valorcuota = (Math.ceil(capitaldiferido) + Math.ceil(interesMensual)).toFixed(0);        
                    var fechasPlan = fechaSumaMensual(cantidadCuotas);                
                    for (let index = 0; index <= cantidadCuotas; index++) {
                        if(index == 0){
                            tablePlan += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(valorPrimerCuota))).toLocaleString('es-CO');+' </td></tr>';
                        }else if(index == cantidadCuotas){
                            tablePlan += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(capitaldiferido))).toLocaleString('es-CO');+' </td></tr>';
                        }else{
                            tablePlan += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+fechasPlan[index]+'</td><td> $'+(Math.ceil(parseFloat(valorcuota))).toLocaleString('es-CO');+' </td></tr>';
                        }
                    }
                    tablePlan += '</tbody></table>';
                    break;
            }

    $('#plan_pagos').append(tablePlan);
  
});

 $('select#formapago').on('change',function(){
		    /* var valor = $(this).val(); 
			 if(valor == 'Diario'){
			 	$('#fechapago').val(sumaDias(1));
			 }
				 if(valor == 'Semanal'){
				 	$('#fechapago').val(sumaDias(7));
				 }
					 if(valor == 'Quincenal'){
					 	$('#fechapago').val(sumaDias(15));
					 }
						 if(valor == 'Mensual'){
						 	$('#fechapago').val(sumaDias(30));
						 }*/
		})

  $('select#plazo').on('change',function(){
		    /* var valor = $(this).val(); 
			 if(valor == 'Dia'){
			 	$('#fplazo').val(sumaDias(1));
			 }
				 if(valor == 'Semana'){
				 	$('#fplazo').val(sumaDias(7));
				 }
					 if(valor == 'Quincena'){
					 	$('#fplazo').val(sumaDias(15));
					 }
						 if(valor == 'Mes'){
						 	$('#fplazo').val(sumaDias(30));
						 }*/
		})
})
</script>
<script type="text/javascript" src="scripts/prestamos.js"></script>
<!--<script type="text/javascript" src="scripts/prestamos.js?v=<?php //echo str_replace('.', '', microtime(true)); ?>"></script>-->

<?php 
}
ob_end_flush();
?>

