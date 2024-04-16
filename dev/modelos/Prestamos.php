<?php 
//Incluimos inicialmente la conexion a la base de datos
require "../config/Conexion.php";
    
Class Prestamo
{
    //implementamos nuestro constructor
    public function __construct()
    {   

    }
    
    //implementamos un metodo para insertar registros
    public function insertar($idcliente,$usuario,$fprestamo,$monto,$interes,$formapago,$plazo,$fechaInicioPago,$interesCuotaVencida)   {
        $sql="INSERT INTO prestamos (idcliente,usuario,fprestamo,monto,interes,formapago,plazo,estado,fechaInicioPago,interesCuotaVencida) 
        VALUES ('$idcliente','$usuario','$fprestamo','$monto','$interes','$formapago','$plazo','1','$fechaInicioPago','$interesCuotaVencida')";
        return ejecutarConsulta_retornarID($sql);
    }
    
    //Implementamos el metodo para Editar Registros
    public function editar($idprestamo,$idcliente,$idusuario,$fprestamo,$monto,$interes,$formapago,$plazo,$fechaInicioPago,$interesCuotaVencida)
	{
		$sql="UPDATE prestamos SET 
                     idcliente='$idcliente',
                     usuario='$idusuario',
                     fprestamo='$fprestamo',
                     monto='$monto',
                     interes='$interes',
                     formapago='$formapago',
                     plazo='$plazo',
                     fechaInicioPago='$fechaInicioPago',
                     interesCuotaVencida='$interesCuotaVencida'
                    WHERE idprestamo='$idprestamo'";
		return ejecutarConsulta($sql);
	}
    
    //Implementamos un método para eliminar categorías
	public function eliminar($idprestamo)
	{
		$sql="DELETE FROM prestamos WHERE idprestamo='$idprestamo'";
		return ejecutarConsulta($sql);
	}
    
    //Implementamos un método para desactivar Clientes
	public function cancelado($idprestamo)
	{
		$sql="UPDATE prestamos SET estado ='0' WHERE SaldoActual=0";
		return ejecutarConsulta($sql);
	} 
    
    //Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idprestamo)
	{
		$sql="SELECT 
            p.idprestamo, 
            c.nombre as cliente, 
            c.idcliente as idcliente, 
            u.nombre as usuario, 
            DATE(p.fprestamo) as fecha,
            p.monto, 
            p.interes,
            p.formapago,
            p.plazo,
            p.estado,
            p.fechaInicioPago,
            p.interesCuotaVencida
        FROM prestamos p 
        INNER JOIN clientes c ON p.idcliente=c.idcliente 
        INNER JOIN usuarios u ON p.usuario=u.idusuario
        WHERE p.idprestamo = $idprestamo";
		return ejecutarConsultaSimpleFila($sql);
	}

    public function mostrarbycliente($idcliente)
	{
		$sql="SELECT 
            p.idprestamo, 
            c.nombre as cliente, 
            c.idcliente as idcliente, 
            u.nombre as usuario, 
            DATE(p.fprestamo) as fecha,
            p.monto, 
            p.interes,
            p.formapago,
            p.plazo,
            p.estado,
            p.fechaInicioPago,
            p.interesCuotaVencida
        FROM prestamos p 
        INNER JOIN clientes c ON p.idcliente=c.idcliente 
        INNER JOIN usuarios u ON p.usuario=u.idusuario
        WHERE p.idcliente = $idcliente";
		return ejecutarConsulta($sql);
	}

    public function mostrarPlanPagos($idprestamo)
	{
		$sql="SELECT * FROM planPagos 
        WHERE idprestamo = $idprestamo";
		return ejecutarConsulta($sql);
	}

    public function proximaCuotaPlanPagos($idprestamo)
	{
		$sql="SELECT * FROM planPagos pp  WHERE pp.idprestamo = $idprestamo AND estado = 1 ORDER BY idprestamo ASC LIMIT 1";
		return ejecutarConsultaSimpleFila($sql);
	}
    
//mostrar lista de la tabla gastos    
    public function listar()
	{
		$sql="SELECT p.idprestamo, c.nombre as cliente, u.nombre as usuario, DATE(p.fprestamo) as fecha, p.monto, p.interes, p.formapago, p.plazo, p.estado, u.idusuario
        FROM prestamos p INNER JOIN clientes c ON 
        p.idcliente=c.idcliente INNER JOIN usuarios u ON 
        p.usuario=u.idusuario";
		return ejecutarConsulta($sql);		
	}
    
    public function select()
	{
		$sql="SELECT p.idprestamo,c.nombre FROM prestamos p INNER JOIN clientes c ON p.idcliente=c.idcliente WHERE p.estado=1 ORDER BY c.nombre ASC";
		return ejecutarConsulta($sql);		
	}

}

?>