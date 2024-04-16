<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Clientes
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//Implementamos un método para insertar registros
	public function insertar($cedula,$nombre,$direccion,$telefono, $ingresos, $egresos, $idzona)
	{
		$sql="INSERT INTO clientes (cedula,nombre,direccion,telefono,estado, ingresos, egresos, idzona )
		VALUES ('$cedula','$nombre','$direccion','$telefono','1', $ingresos, $egresos, $idzona)";
		return ejecutarConsulta($sql);
	}
    
	//Implementamos un método para editar registros
	public function editar($idcliente,$cedula,$nombre,$direccion,$telefono, $ingresos, $egresos, $idzona)
	{
		$sql="UPDATE clientes SET cedula='$cedula',nombre='$nombre',direccion='$direccion',telefono='$telefono', ingresos='$ingresos', egresos='$egresos', idzona='$idzona' WHERE idcliente='$idcliente'";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para desactivar Clientes
	public function desactivar($idcliente)
	{
		$sql="UPDATE clientes SET estado ='0' WHERE idcliente='$idcliente'";
		return ejecutarConsulta($sql);
	}
    //Implementamos un método para Activar Clientes
	public function activar($idcliente)
	{
		$sql="UPDATE clientes SET estado ='1' WHERE idcliente='$idcliente'";
		return ejecutarConsulta($sql);
	}
	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcliente)
	{
		$sql="SELECT * FROM clientes WHERE idcliente='$idcliente'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function zonas()
	{
		$sql="SELECT * FROM zonas";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT clientes.*, zonas.idzona as idzona, zonas.nombre as nombrezona FROM clientes INNER JOIN zonas ON zonas.idzona = clientes.idzona";
		return ejecutarConsulta($sql);		
	}
    
    //Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT idcliente,nombre FROM clientes WHERE estado=1 ORDER BY nombre ASC";
		return ejecutarConsulta($sql);		
	}
}
?>