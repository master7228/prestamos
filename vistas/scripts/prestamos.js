var tabla;

//Funcion que se ejecuta al inicio
function init(){
    mostrarform(false);
    listar();
    
    $("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});
    
    //Cargamos los items al select Cliente
	$.post("../ajax/prestamos.php?op=selectCliente", function(r){
	            $("#idcliente").append(r);
	            $('#idcliente').selectpicker('refresh');
	});
    
    //Cargamos los items al select Usuarios
    /*$.post("../ajax/prestamos.php?op=selectUsuario", function(r){
	            $("#usuario").append(r);
	            $('#usuario').selectpicker('refresh');
	});*/
}

//Funcion Limpiar
function limpiar(){
    $("#idprestamo").val("");
    $("#idcliente").val("");
    $("#usuario").val("");
    $("#fprestamo").val("");
    $("#monto").val("");
    $("#interes").val("");
    $("#formapago").val("");
    $("#plazo").val("");
    $("#estado").val("");
	$("#interescuotavencidad").val("");
	$('#plan_pagos').empty();
	$('#datos_cliente').empty();
	$('#idcliente').selectpicker('refresh');

    
    //Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fprestamo').val(today);
    
}

//Mostrar Formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

function cancelarform()
{
    limpiar();
    mostrarform(false);
}


function listar()
{

	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            /*'copyHtml5',
		            'excelHtml5',
		            'pdf'*/
		        ],
		"ajax":
				{
					url: '../ajax/prestamos.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);
	var planPagos = [];
	var planPagosDetalle = [];
    
	for (let index = 1; index <= $('#tablePlanPagos tr').length; index++) {
		planPagosDetalle.push($("#tablePlanPagos tr:eq(" + index + ") td:eq(0)").text());
		planPagosDetalle.push($("#tablePlanPagos tr:eq(" + index + ") td:eq(1)").text());
		planPagosDetalle.push(($("#tablePlanPagos tr:eq(" + index + ") td:eq(2)").text()).replace('$','').replace('.',''));
		planPagos.push(planPagosDetalle);
		planPagosDetalle = [];
	}
	formData.append('planPagos', JSON.stringify(planPagos));
	$.ajax({
		url: "../ajax/prestamos.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          tabla.ajax.reload();
	    }

	});
	limpiar();
}

function mostrar(idprestamo)
{
	$.post("../ajax/prestamos.php?op=mostrar",{idprestamo : idprestamo}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);
		$("#idcliente").val(data.idcliente);
        $('#idcliente').selectpicker('refresh');
        $("#usuario").val(data.usuario);
		$("#fprestamo").val(data.fecha);
		$("#monto").val(parseInt(data.monto));
		$("#interes").val((data.interes).replace('.00',''));
		$("#saldo").val(data.saldo);
		$("#formapago").val(data.formapago);
        $("#fechapago").val(data.fechap);
        $("#plazo").val(data.plazo);
		$("#estado").val(data.estado);
		$("#idprestamo").val(data.idprestamo);
		$("#interescuotavencidad").val((data.interesCuotaVencida).replace('.00',''));
		$("#finicio").val((data.fechaInicioPago).replace(' 00:00:00',''));
		$.post("../ajax/clientes.php?op=mostrar", {
            idcliente: data.idcliente
        }, function (dataCliente, status) {
            dataCliente = JSON.parse(dataCliente);
            $('#datos_cliente').empty();
            ingresos = parseInt(dataCliente[0].ingresos).toLocaleString('es-CO');
            egresos = parseInt(dataCliente[0].egresos).toLocaleString('es-CO');
            cuotasugerida = parseInt((dataCliente[0].ingresos - dataCliente[0].egresos) * 0.3).toLocaleString('es-CO');
            //valorymeses = 

            var capacidadPagoMensual = (dataCliente[0].ingresos  - dataCliente[0].egresos) * 0.3; // capacidad de pago mensual en pesos
            var tasaInteres = 0.20; // tasa de interés (20%)
            var plazo = 4; // plazo del préstamo en meses
            var valorymeses = parseInt(((capacidadPagoMensual * ((1 - Math.pow(1 + tasaInteres, -plazo)) / tasaInteres)).toFixed(0))).toLocaleString('es-CO')+' a 4 Meses';
            $('#datos_cliente').append('<table class="table-striped table-bordered table-condensed table-hover" style="width:100%"><tbody><tr><td><b>Nombre:</b>'+dataCliente[0].nombre+'</td><td><b>Documento:</b> '+dataCliente[0].cedula+' </td></tr><tr><td><b>Direccion:</b> '+dataCliente[0].direccion+' </td><td><b>Telefono:</b> '+dataCliente[0].telefono+'</td></tr><tr><td><b>Ingresos:</b> $'+ingresos+' </td><td><b>Egresos:</b> $'+egresos+' </td></tr> <tr><td><b>Maximo Cuota Sugerido:</b> $'+cuotasugerida+' </td><td><b>Valor y Meses Sugeridos:</b> $'+valorymeses+' </td></tr> </tbody></table>');
    

        });
		$.post("../ajax/prestamos.php?op=mostrarPlanPagos",{idprestamo : idprestamo}, 
			function(dataPlan, status)
				{
					dataPlan = JSON.parse(dataPlan);
					var tablaPlan = '<table id="tablePlanPagos" class="table-striped table-bordered table-condensed table-hover" style="width:100%"><thead><tr><th>Cuota Nro.</th><th>Fecha de Pago</th><th>Valor</th></tr></thead><tbody>';
					for (let index = 0; index < dataPlan.length; index++) {
						tablaPlan += '<tr><td>'+dataPlan[index][0]+'</td><td>'+(dataPlan[index][1]).replace(' 00:00:00','')+'</td><td> $'+(Math.ceil(parseFloat(dataPlan[index][2]))).toLocaleString('es-CO');+' </td></tr>';
					}	
					tablaPlan += '</tbody></table>';
					$('#plan_pagos').append(tablaPlan);
				});

 		});
    }


	function solover(idprestamo){
		$.post("../ajax/prestamos.php?op=mostrar",{idprestamo : idprestamo}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);
		$("#idcliente").val(data.idcliente);
        $('#idcliente').selectpicker('refresh');
        $("#usuario").val(data.usuario);
		$("#fprestamo").val(data.fecha);
		$("#monto").val(parseInt(data.monto));
		$("#interes").val((data.interes).replace('.00',''));
		$("#saldo").val(data.saldo);
		$("#formapago").val(data.formapago);
        $("#fechapago").val(data.fechap);
        $("#plazo").val(data.plazo);
		$("#estado").val(data.estado);
		$("#idprestamo").val(data.idprestamo);
		$("#interescuotavencidad").val((data.interesCuotaVencida).replace('.00',''));
		$("#finicio").val((data.fechaInicioPago).replace(' 00:00:00',''));
		$.post("../ajax/clientes.php?op=mostrar", {
            idcliente: data.idcliente
        }, function (dataCliente, status) {
            dataCliente = JSON.parse(dataCliente);
            $('#datos_cliente').empty();
            ingresos = parseInt(dataCliente[0].ingresos).toLocaleString('es-CO');
            egresos = parseInt(dataCliente[0].egresos).toLocaleString('es-CO');
            cuotasugerida = parseInt((dataCliente[0].ingresos - dataCliente[0].egresos) * 0.3).toLocaleString('es-CO');
            //valorymeses = 

            var capacidadPagoMensual = (dataCliente[0].ingresos  - dataCliente[0].egresos) * 0.3; // capacidad de pago mensual en pesos
            var tasaInteres = 0.20; // tasa de interés (20%)
            var plazo = 4; // plazo del préstamo en meses
            var valorymeses = parseInt(((capacidadPagoMensual * ((1 - Math.pow(1 + tasaInteres, -plazo)) / tasaInteres)).toFixed(0))).toLocaleString('es-CO')+' a 4 Meses';
            $('#datos_cliente').append('<table class="table-striped table-bordered table-condensed table-hover" style="width:100%"><tbody><tr><td><b>Nombre:</b>'+dataCliente[0].nombre+'</td><td><b>Documento:</b> '+dataCliente[0].cedula+' </td></tr><tr><td><b>Direccion:</b> '+dataCliente[0].direccion+' </td><td><b>Telefono:</b> '+dataCliente[0].telefono+'</td></tr><tr><td><b>Ingresos:</b> $'+ingresos+' </td><td><b>Egresos:</b> $'+egresos+' </td></tr> <tr><td><b>Maximo Cuota Sugerido:</b> $'+cuotasugerida+' </td><td><b>Valor y Meses Sugeridos:</b> $'+valorymeses+' </td></tr> </tbody></table>');
    

        });
		$.post("../ajax/prestamos.php?op=mostrarPlanPagosParaPago",{idprestamo : idprestamo}, 
			function(dataPlan, status)
				{
					dataPlan = JSON.parse(dataPlan);
					
					var tablaPlan = '<table id="tablePlanPagos" class="table-striped table-bordered table-condensed table-hover" style="width:100%"><thead><tr><th>Cuota Nro.</th><th>Fecha Próximo Pago</th><th>Valor Cuota</th><th>Estado</th><th>Valor Pagado</th><th>Fecha de Pago</th></tr></thead><tbody>';
					for (let index = 0; index < dataPlan.length; index++) {
						estado = (dataPlan[index]["estado"] == 1) ? 'Cancelado' : 'Pendiente';
						fechaPago = (dataPlan[index]["fecha_pago"] != null) ? dataPlan[index]["fecha_pago"] : '';
						valorPagado = ((Math.ceil(parseFloat(dataPlan[index]["valor_pagado"]))).toLocaleString('es-CO') != 'NaN') ? '$'+(Math.ceil(parseFloat(dataPlan[index]["valor_pagado"]))).toLocaleString('es-CO') : '';
						tablaPlan += '<tr><td>'+dataPlan[index]["numero_cuota"]+'</td><td>'+(dataPlan[index]["fecha_proximo_pago"]).replace(' 00:00:00','')+'</td><td> $'+(Math.ceil(parseFloat(dataPlan[index]["valor_cuota"]))).toLocaleString('es-CO')+' </td><td>'+estado+'</td><td>'+valorPagado+' </td><td>'+fechaPago+'</td></tr>';
					}	
					tablaPlan += '</tbody></table>';
					$('#plan_pagos').append(tablaPlan);
				});

 		});

		 $('#monto').attr('disabled',true);
		 $('#interes').attr('disabled',true);
		 $('#finicio').attr('disabled',true);
		 $('#formapago').attr('disabled',true);
		 $('#plazo').attr('disabled',true);
		 $('#interescuotavencidad').attr('disabled',true);
		 $('#idcliente').attr('disabled',true);
		 $('#btn_calcular').hide();
	}
//Función para eliminar registros
function eliminar(idprestamo)
{
	bootbox.confirm("¿Está Seguro de eliminar el Prestamo?", function(result){
		if(result)
        {
        	$.post("../ajax/prestamos.php?op=eliminar", {idprestamo : idprestamo}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();
