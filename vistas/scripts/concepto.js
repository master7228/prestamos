var tabla;

//Funcion que se ejecuta al inicio
function init(){
    listarPagosDelDia();
	listarPrestamosDelDia();
}




function listarPagosDelDia()
{

	tabla=$('#tbllistadopagosdeldia').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": false,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            /*'copyHtml5',
		            'excelHtml5',
		            'pdf'*/
		        ],
		"ajax":
				{
					url: '../ajax/planpagos.php?op=listarpagosdeldia',
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

function listarPrestamosDelDia()
{

	tabla=$('#tbllistadoprestamosdeldia').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": false,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            /*'copyHtml5',
		            'excelHtml5',
		            'pdf'*/
		        ],
		"ajax":
				{
					url: '../ajax/prestamos.php?op=listarprestamosdeldia',
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


init();
