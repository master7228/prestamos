<?php 
if (session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión
    session_start();
}
date_default_timezone_set('America/Bogota');
//Incluimos inicialmente la conexion a la base de datos
require "../config/Conexion.php";
    
Class PlanPagos
{
    //implementamos nuestro constructor
    public function __construct()
    {   

    }
    
    //implementamos un metodo para insertar registros
    public function insertar($idprestamo,$numero_cuota,$fecha_proximo_pago,$valor_cuota)   {
        $sql="INSERT INTO planPagos (idprestamo,numero_cuota,fecha_proximo_pago,valor_cuota,estado) 
        VALUES ('$idprestamo','$numero_cuota','$fecha_proximo_pago','$valor_cuota',0)";
        return ejecutarConsulta($sql);
    }

    public function delete($idprestamo)   {
        $sql= "DELETE FROM planPagos WHERE idprestamo = $idprestamo ";
        return ejecutarConsulta($sql);
    }

    public function acentarPago($id, $valor_pagado, $fecha_pago, $observacion, $id_user){
        $sql= "UPDATE planPagos SET valor_pagado = $valor_pagado, fecha_pago = '$fecha_pago', descripcion = '$observacion', estado = 1, id_user = $id_user   WHERE id = $id ";
        return ejecutarConsulta($sql);
    }

    public function actualizarCuotasSaldos($id_planPagos, $valor){
        $sql= "UPDATE planPagos SET valor_cuota = $valor  WHERE id = $id_planPagos ";
        return ejecutarConsulta($sql);
    }

    public function listarpagosdeldia()
    {
        $fecha_actual = date('Y-m-d');
        if($_SESSION['admin'] == 1){
            
            $sql="SELECT pp.id, pp.idprestamo, c.nombre AS cliente,  pp.numero_cuota, pp.fecha_proximo_pago AS fecha, pp.valor_cuota AS cuota, pp.estado, u.nombre AS usuario FROM planPagos pp 
            INNER JOIN prestamos p ON pp.idprestamo = p.idprestamo  
            INNER  JOIN usuarios u ON u.idusuario = p.usuario 
            INNER JOIN clientes c ON p.idcliente = c.idcliente
            WHERE pp.fecha_proximo_pago = '$fecha_actual' ";
            
        }else{
            $usuario = $_SESSION['idusuario'];
            $sql="SELECT pp.id, pp.idprestamo, c.nombre AS cliente,  pp.numero_cuota, pp.fecha_proximo_pago AS fecha, pp.valor_cuota AS cuota, pp.estado, u.nombre AS usuario FROM planPagos pp 
            INNER JOIN prestamos p ON pp.idprestamo = p.idprestamo  
            INNER  JOIN usuarios u ON u.idusuario = p.usuario 
            INNER JOIN clientes c ON p.idcliente = c.idcliente
            WHERE pp.fecha_proximo_pago = '$fecha_actual' AND p.usuario = $usuario ";
        }
        return ejecutarConsulta($sql);
    }

    public function consultarPagoPlanPagos($id)
    {
        $sql="SELECT pp.id, pp.idprestamo, c.nombre AS cliente, c.cedula,  pp.numero_cuota, pp.fecha_pago AS fecha, pp.valor_cuota AS cuota, pp.estado, u.nombre AS usuario 
        FROM planPagos pp 
        INNER JOIN prestamos p ON pp.idprestamo = p.idprestamo  
        INNER  JOIN usuarios u ON u.idusuario = p.usuario 
        INNER JOIN clientes c ON p.idcliente = c.idcliente
        WHERE pp.id = '$id' ";
        return ejecutarConsulta($sql);
    }
    
}

?>