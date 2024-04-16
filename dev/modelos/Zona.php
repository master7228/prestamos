<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Zona
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	//Implementamos un método para insertar registros
	public function insertar($nombre, $descripcion)
	{
		$sql="INSERT INTO zonas (nombre, descripcion,estado)
		VALUES ('$nombre','$descripcion','1')";
		return ejecutarConsulta($sql);
	}
    
	//Implementamos un método para editar registros
	public function editar($idzona,$nombre,$descripcion)
	{
		$sql="UPDATE zonas SET nombre='$nombre', descripcion='$descripcion' WHERE idzona='$idzona'";
		return ejecutarConsulta($sql);
	}
	//Implementamos un método para desactivar zonas
	public function desactivar($idzona)
	{
		$sql="UPDATE zonas SET estado ='0' WHERE idzona='$idzona'";
		return ejecutarConsulta($sql);
	}
    //Implementamos un método para Activar zonas
	public function activar($idzona)
	{
		$sql="UPDATE zonas SET estado ='1' WHERE idzona='$idzona'";
		return ejecutarConsulta($sql);
	}
	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idzona)
	{
		$sql="SELECT * FROM zonas WHERE idzona='$idzona'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM zonas";
		return ejecutarConsulta($sql);		
	}
    
    //Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT idzona,nombre, descripcion FROM zonas WHERE estado=1 ORDER BY nombre ASC";
		return ejecutarConsulta($sql);		
	}
}
?>