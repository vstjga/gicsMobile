<?php
//He cambiado la ruta de entrada a /clientes/pedido y el render a cliente_views/pedido.html
$app->get('/clientes',function() use($app)
{	
	if(!controlAccesoCliente())  // control autorizacion pagina  
	{ 
		$app->redirect('/gics/logincliente');
		exit();	
	}else{
		$mesa=$_COOKIE['Mesa'];
		$idCliente=$_COOKIE['IdCliente'];
    $pedido=new Pedido();
    $pedidosMesa=$pedido->listarPorMesa($mesa);
    
    $DetallePedido=new DetallePedido();

    $pedidos="";
	
    for($i=0;$i<long($pedidosMesa);$i++){
        $pedidos[$i]=$pedidosMesa[$i];
        $detalles=$DetallePedido->listarPorPedido($pedidosMesa[$i]["IdPedido"]);
        $pedidos[$i]["DetallePedido"]=$detalles;
        $producto=new Producto();
        if($detalles!=""){
            for($j=0;$j<long($detalles);$j++){
                $producto->get($detalles[$j]['CodProducto']);
                $pedidos[$i]["DetallePedido"][$j]["Nombre"]=$producto->Nombre;
                $pedidos[$i]["DetallePedido"][$j]["Descripcion"]=$producto->Descripcion;
                $pedidos[$i]["DetallePedido"][$j]["Precio"]=$producto->Precio;
                $pedidos[$i]["DetallePedido"][$j]["Stock"]=$producto->Stock;
                $pedidos[$i]["DetallePedido"][$j]["Tipo"]=$producto->Tipo;
                $pedidos[$i]["DetallePedido"][$j]["SubTipo"]=$producto->SubTipo;
                $pedidos[$i]["DetallePedido"][$j]["Imagen"]=$producto->Imagen;
            }
        }
    }
		$app->view()->setData(array('mesa'=>$mesa, 'idCliente'=>$idCliente,'pedidos'=>$pedidos));
		$app->render('cliente_views/pedido.html');

	}	
});

$app->get('/clientes/pedido',function() use($app)
{	
	if(!controlAccesoCliente())  // control autorizacion pagina  
	{ 
		$app->redirect('/gics/logincliente');
		exit();	
	}else{
		$mesa=$_COOKIE['Mesa'];
		$idCliente=$_COOKIE['IdCliente'];
    $pedido=new Pedido();
    $pedidosMesa=$pedido->listarPorMesa($mesa);
    
    $DetallePedido=new DetallePedido();

    $pedidos="";
	
    for($i=0;$i<long($pedidosMesa);$i++){
        $pedidos[$i]=$pedidosMesa[$i];
        $detalles=$DetallePedido->listarPorPedido($pedidosMesa[$i]["IdPedido"]);
        $pedidos[$i]["DetallePedido"]=$detalles;
        $producto=new Producto();
        if($detalles!=""){
            for($j=0;$j<long($detalles);$j++){
                $producto->get($detalles[$j]['CodProducto']);
                $pedidos[$i]["DetallePedido"][$j]["Nombre"]=$producto->Nombre;
                $pedidos[$i]["DetallePedido"][$j]["Descripcion"]=$producto->Descripcion;
                $pedidos[$i]["DetallePedido"][$j]["Precio"]=$producto->Precio;
                $pedidos[$i]["DetallePedido"][$j]["Stock"]=$producto->Stock;
                $pedidos[$i]["DetallePedido"][$j]["Tipo"]=$producto->Tipo;
                $pedidos[$i]["DetallePedido"][$j]["SubTipo"]=$producto->SubTipo;
                $pedidos[$i]["DetallePedido"][$j]["Imagen"]=$producto->Imagen;
            }
        }
    }
		$app->view()->setData(array('mesa'=>$mesa, 'idCliente'=>$idCliente,'pedidos'=>$pedidos));
		$app->render('cliente_views/pedidoV2.html');

	}	
});

$app->get('/clientes/mis_pedidos',function() use($app)
{	
	if(!controlAccesoCliente())  // control autorizacion pagina  
	{ 
		$app->redirect('/gics/logincliente');
		exit();	
	}else{
		$mesa=$_COOKIE['Mesa'];
		$idCliente=$_COOKIE['IdCliente'];
		
    $pedido=new Pedido();
    $pedidosMesa=$pedido->listarPorMesa($mesa);
    
    $DetallePedido=new DetallePedido();

    $pedidos="";
	
    for($i=0;$i<count($pedidosMesa);$i++){
        $pedidos[$i]=$pedidosMesa[$i];
        $detalles=$DetallePedido->listarPorPedido($pedidosMesa[$i]["IdPedido"]);
        $pedidos[$i]["DetallePedido"]=$detalles;
        
        if($detalles!=""){
            
            for($j=0;$j<count($detalles);$j++){
                $producto=new Producto();
                $producto->get($detalles[$j]['CodProducto']);
                $pedidos[$i]["DetallePedido"][$j]["Nombre"]=$producto->Nombre;
                $pedidos[$i]["DetallePedido"][$j]["Descripcion"]=$producto->Descripcion;
                $pedidos[$i]["DetallePedido"][$j]["Precio"]=$producto->Precio;
                $pedidos[$i]["DetallePedido"][$j]["Stock"]=$producto->Stock;
                $pedidos[$i]["DetallePedido"][$j]["Tipo"]=$producto->Tipo;
                $pedidos[$i]["DetallePedido"][$j]["SubTipo"]=$producto->SubTipo;
                $pedidos[$i]["DetallePedido"][$j]["Imagen"]=$producto->Imagen;
            }
        }
    }
    
    //var_dump($pedidos);
		
	$app->view()->setData(array('mesa'=>$mesa, 'idCliente'=>$idCliente,'pedidos'=>$pedidos));
	$app->render('cliente_views/mis_pedidos.html');

	}	
});

$app->post('/clientes/productos/',function() use($app)
{
	$producto=new Producto();
	$productos=$producto->listar();
	
	$arrayProductos="";
	
	for($i=0;$i<count($productos);$i++){
		$arrayProductos[$productos[$i]["Tipo"]][$productos[$i]["SubTipo"]][]=$productos[$i];
	}
     echo json_encode(array('productos'=>$arrayProductos));
});

$app->post('/clientes/mis_pedidos/cobro',function() use($app)
{
    $pedidos=json_decode($_POST["pedidos"]);
    
    $pedido=new Pedido();
    $detallespedido=new DetallePedido();

    for($i=0;$i<count($pedidos);$i++){
        $pedido->editEstado($pedidos[$i],'SCO');
        $detallespedido->editEstados($pedidos[$i],'SCO');
    }
    setcookie (session_name(),"", time() - 3600);
     session_unset();
     session_destroy();
     $time = time();
     setcookie("IdCliente","",$time-3600);   //Validez anterior a la creacion
     echo json_encode(array('mensaje'=>'OK'));
});

$app->post('/clientes/pedido/confirma',function() use($app)
{
	$ped=$_POST['pedido'];
	
    $pedidos=json_decode($ped,true);
    
    $arrayPedido="";
    $pedido=new Pedido();
    $arrayPedido["IdPedido"]=$pedido->asignaNumPedido();
    $arrayPedido["IdMesa"]=$pedidos["mesa"];
    $arrayPedido["IdCliente"]=$pedidos["cliente"];
    $arrayPedido["IdCamarero"]= intval($pedido->asignaCamareroMesa($pedidos["mesa"]));
	
    
    $arrayPedido["Estado"]='PND';
    $pedido->set($arrayPedido);

    $detallePedido=new DetallePedido();
    $producto=new Producto();
    for($i=0;$i<count($pedidos["lineasPedido"]);$i++){
        $arrayDetalle["IdPedido"]=$arrayPedido["IdPedido"];
        $arrayDetalle["LinPedido"]=$i+1;
        $arrayDetalle["CodProducto"]=$pedidos["lineasPedido"][$i]["producto"]["Codigo"];
        $arrayDetalle["Cantidad"]=$pedidos["lineasPedido"][$i]["cantidad"];
        $arrayDetalle["Estado"]=$producto->asignaEstado($arrayDetalle["CodProducto"]);
        

        $detallePedido->set($arrayDetalle);
    }
    //echo json_encode(array('mensaje'=>$arrayPedido["IdPedido"]));
    echo json_encode(array('num_lineas'=>count($arrayDetalle),'mensaje'=>$pedido->mensaje));
});



$app->get('/logincliente/',function() use($app)
{
    $app->render('logincliente.html');
});


$app->post('/validarcliente/',function() use($app)
{
    $datosform=$app->request;

    $nombre=sanear_entrada($datosform->post('nombre'));
    $password=sanear_entrada($datosform->post('password'));
    $mesa=sanear_entrada($datosform->post('mesa'));
    $codmesa=sanear_entrada($datosform->post('codmesa'));
    $userOK = true;
    $mesaOK = true;
    $mensajeAcceso='';
    
    $usuario=new Usuario();
    
    $usuario->validarUser($nombre,$password);
    if($usuario->error_query){
        $userOK = false;
        $mensajeAcceso = 'Usuario no válido.';
    }else{
        if (!$usuario->validarMesa($mesa,$codmesa)){
            $mesaOK = false;
            $mensajeAcceso = 'Error en identificacion de mesa';
        } 
    }
    
    if ($userOK and $mesaOK){
        if (!$usuario->mesaLibre($mesa)){
            if (!$usuario->mesaOcupadaElmismo($mesa,$nombre)){ 
                $mesaOK = false;
                $mensajeAcceso = 'Mesa Ocupada';
            }   
        }else{
            if ($usuario->OcupaOtraMesa($mesa,$nombre)){
                $mesaOK = false;
                $mensajeAcceso = 'Usuario logeado en otra mesa';
            }
        }   
    }
    
    $mensaje="";
    $ruta="";
    $estado=false;
    
    if(!$userOK or !$mesaOK){
        $mensaje=$mensajeAcceso;
    }else{
        switch($usuario->Rol){
            case 'CL':
                        $estado=true;
                        $ruta="clientes";
                        $mensaje='Usuario válido.';
                        break;

        default:
                        $mensaje='Usuario no válido. tres';
        }
        $time = time(); 
        $Rol       = $usuario->Rol;
        $Id        = $usuario->Id;
        $Username  = $usuario->Username;
        
        $_SESSION['Rol'] = $Rol;               
        $_SESSION['idsession'] = session_id();        
        $_SESSION['Username'] = $Username;        
        setcookie('IdCliente', $Id, $time + 7200); // 2 horas de conexion clientes  
        setcookie('Mesa', $mesa, $time + 7200); // 2 horas de conexion clientes vesa 
    }
    echo json_encode(array('estado'=>$estado,'mensaje'=>$mensaje,'ruta'=>$ruta,'id'=>$usuario->Id));
});

/********************************************************************************************************
 *            APIS PARA GICSMOBILE
 * 
 ********************************************************************************************************/


$app->get("/productos/GetProductosPorPrecio/",function() use($app)
    {
        try 
        {
			if(array_key_exists('apikey', $_GET)) {
				$apikey = $_GET['apikey'];
				if ($apikey == '6969'){
 
					$Producto=new Producto();
					$resultado = $Producto->listarTodoPorPrecio();
					$app->response->headers->set("Content-type", "application/json", "charset=utf-8");
					$app->response->headers->set("Access-Control-Allow-Origin:", "*");

					$app->response->status(200);
					$app->response->body(json_encode($resultado));
				}else{
					echo 'Invalid Apikey';
				}	
            }
        }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    });
 
$app->get("/productos/GetProductosPorNombre/",function() use($app)
    {
        try 
        {
			if(array_key_exists('apikey', $_GET)) {
				$apikey = $_GET['apikey'];
				if ($apikey == '6969'){
 
					$Producto=new Producto();
					$resultado = $Producto->listarTodoPorNombre();
			
					$app->response->headers->set("Content-type", "application/json");
					$app->response->headers->set("Access-Control-Allow-Origin:", "*");

					$app->response->status(200);
					$app->response->body(json_encode($resultado));
				}else{
					echo 'Invalid Apikey';
				}	
            }
        }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
    });

/**
*	controla la cokies de sesion y el roll de usuario CO
*
*	@author		Juan Gimenez Aguilar
*	@version 	1.1
*
*	@return string $db con la cadena de conexion 
*	@param  Sin parametros de entrada 
*/
function controlAccesoCliente(){
	if(!isset($_SESSION['Rol']) or !isset($_COOKIE['IdCliente']) or !isset($_SESSION['Username']) or !isset($_COOKIE['Mesa']))
	{ 
		return false;
	}else{
		/*if ($_SESSION['Rol'] <> 'CL'){
			return false;
		}else{*/
			return true;
		//}	
	}
}

?>
