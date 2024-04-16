var tabla;

//Funcion que se ejecuta al inicio

function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })
}

//Funcion Limpiar
function limpiar() {
    $("#idcliente").val("");
    $("#cedula").val("");
    $("#nombre").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#ingresos").val("");
    $("#egresos").val("");
    $("#zona").empty();
    $("#id_ciudad").empty();
    $("#archivos").val("");
}

//Mostrar Formulario

function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        /*$.ajax({
            url: "../ajax/clientes.php?op=zonas",
            type: "GET",
    
            success: function (datos) {
                datos = JSON.parse(datos);
                $("#zona").append($('<option>', {
                    value: '',
                    text: ''
                }));
                datos.forEach(element => {
                    $("#zona").append($('<option>', {
                        value: element.idzona,
                        text: element.nombre
                    }));
                });

            }
        });*/
        $.ajax({
            url: "../ajax/clientes.php?op=ciudades",
            type: "GET",
    
            success: function (datos) {
                datos = JSON.parse(datos);
                $("#id_ciudad").append($('<option>', {
                    value: '',
                    text: ''
                }));
                datos.forEach(element => {
                    $("#id_ciudad").append($('<option>', {
                        value: element.id,
                        text: element.nombre
                    }));
                });

            }
        });
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

function cancelarform() {
    limpiar();
    mostrarform(false);
}


function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true, //Activamos el procesamiento del datatables
        "aServerSide": true, //Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //Definimos los elementos del control de tabla
        buttons: [
            
        ],
        "ajax": {
            url: '../ajax/clientes.php?op=listar',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10, //Paginación
        "order": [
            [2, "asc"]
        ] //Ordenar (columna,orden)
    }).DataTable();
}

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/clientes.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        }
    });
    limpiar();
}

function mostrar(idcliente) {
    $.post("../ajax/clientes.php?op=mostrar", {
        idcliente: idcliente
    }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        console.log(data[0]);
        $("#cedula").val(data[0].cedula);
        $("#nombre").val(data[0].nombre);
        $("#direccion").val(data[0].direccion);
        $("#telefono").val(data[0].telefono);
        $("#idcliente").val(data[0].idcliente);
        $("#ingresos").val(data[0].ingresos);
        $("#egresos").val(data[0].egresos);
        setTimeout(function() { 
            $("#zona").val(data[0].idzona);
            $("#id_ciudad").val(data[0].id_ciudad);
        }, 100);
        var tablaHTML = "<table border='1' style='margin-bottom: 10px;'><tr><th style='text-align:center'>Archivos</th></tr>";
        data[1].forEach(element => {
            tablaHTML += "<tr><td>" + element + "</td></tr>";
        });
        tablaHTML += "</table>";
        $("#filesClient").html(tablaHTML);
        $('#filesClient').show();
  

    })
}

//Función para desactivar registros
function desactivar(idcliente) {
    bootbox.confirm("¿Está Seguro de desactivar el Cliente?", function (result) {
        if (result) {
            $.post("../ajax/clientes.php?op=desactivar", {
                idcliente: idcliente
            }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idcliente) {
    bootbox.confirm("¿Está Seguro de activar Cliente?", function (result) {
        if (result) {
            $.post("../ajax/clientes.php?op=activar", {
                idcliente: idcliente
            }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

init();