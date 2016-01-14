<?php
require_once('core/db_abstract_model.php');

/**
 *	Productos (modelo)
 *
 *	@author		Juan Giménez Aguilar
 *	@version 	1.1
 *  @copyright   JGA
 *	@package    productos
 *	@subpackage 
 */ 
class Producto extends DBAbstractModel {

	public    $Codigo;
	public    $Nombre;
	public    $Descripcion;
	public    $Precio;        
	public    $Stock;         
	public    $Tipo;          
	public    $SubTipo;       
	public    $Imagen;        
    
	/**
	 *	get: Obtiene Productos de la base de datos
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $Codigo string
	 *  @return void
	 */
    public function get($Codigo='') {
        if($Codigo != '') {
            $this->query = "
				SELECT * FROM Productos
                WHERE       Codigo = '$Codigo'";
            $this->get_results_from_query();
        }

		if (!$this->error_query and !$this->error_conn) {
			
			if(count($this->rows) == 1) {
				foreach ($this->rows[0] as $propiedad=>$valor) {
					$this->$propiedad = $valor;
				}
				$this->mensaje = 'Productos encontrado';
			} else {
				$this->mensaje = 'Productos no encontrado';
				$this->error_query = true;
			}
		}
			
    }

	/**
	 *	set: Crea Productos 
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $registro_data array
	 *  @return void
	 */

    public function set($registro_data=array()) {


        if(array_key_exists('Codigo', $registro_data)) {
			  
            $this->get($registro_data['Codigo']);
			if (!$this->error_conn) {
				if($registro_data['Codigo'] != $this->Codigo) {
					foreach ($registro_data as $campo=>$valor) {
						$$campo = $valor;
					}

					$this->query = "
							INSERT INTO Productos
							(Codigo,Nombre,Descripcion,Precio,Stock,Tipo,SubTipo,Imagen)
							VALUES
							('$Codigo', '$Nombre', '$Descripcion', '$Precio','$Stock','$Tipo','$SubTipo','$Imagen')
					";
					$this->execute_single_query();
					if (!$this->error_conn and !$this->error_query) { 
						$this->mensaje = 'Producto agregado exitosamente';
					}
				} else {
					$this->mensaje = 'El Producto ya existe';
				}
			}
        } else {
            $this->mensaje = 'No se ha agregado el Productos';
        }
    }

	/**
	 *	edit: Modifica Productos 
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $registro_data array
	 *  @return void
	 */

    public function edit($registro_data=array()) {
	
		if(array_key_exists('Codigo', $registro_data)) {
			$this->get($registro_data['Codigo']);
			
			if (!$this->error_query and !$this->error_conn) {
		
				if($registro_data['Codigo'] = $this->Codigo) {
					foreach ($registro_data as $campo=>$valor) {
						$$campo = $valor;
					}
			
					$this->query = "
							UPDATE      Productos
							SET         Nombre='$Nombre',
										Descripcion='$Descripcion',
										Precio='$Precio',
										Stock='$Stock',
										Tipo='$Tipo',
										SubTipo='$SubTipo',
										Imagen='$Imagen'
							WHERE       Codigo = '$Codigo'
					";
					$this->execute_single_query();
					$this->mensaje = 'Producto modificado';
			
				}
			}	
		} else {
			$this->mensaje = 'No se ha modificado el Producto';
		}
    }

	/**
	 *	delete: Elimina Productos 
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $Codigo string
	 *  @return void
	 */

    public function delete($Codigo='') {

		$this->get($Codigo);
		if (!$this->error_query and !$this->error_conn) {
			$this->query = "
					DELETE FROM     Productos
					WHERE           Codigo = '$Codigo'";
			$this->execute_single_query();
			$this->mensaje = 'Producto eliminado';
		}
		
	}
	/**
	 *	listar : Lista productos
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $Codigo string
	 *  @return array 
	 */	
	public function listar() {
        $this->query =" 
			   SELECT * FROM Productos";
		 
		return $this->get_results_from_query_list();
	}
	/**
	 *	listar : Lista productos
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $Codigo string
	 *  @return array 
	 */	
	public function listarTodoPorTipo() {
        $this->query =" 
			   SELECT * FROM Productos order by Tipo, Subtipo, Nombre";
		 
		return $this->get_results_from_query_list();
	}		
   	/**	listar : Lista productos
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @return array 
	 */	
	public function listarTodoPorNombre() {
        $this->query =" 
			   SELECT * FROM Productos order by Subtipo,Nombre";
		 
		return $this->get_results_from_query_list();
	}		
	 /**	listar : Lista productos
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @return array 
	 */	
	public function listarTodoPorPrecio() {
        $this->query =" 
			   SELECT * FROM Productos order by Subtipo,Precio";
		 
		return $this->get_results_from_query_list();
	}
	/**	listar : Lista productos
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @return array 
	 */	
	public function listarTiposProducto() {
        $this->query =" 
			   SELECT * FROM TipoProductos order by Tipo";
		 
		return $this->get_results_from_query_list();
	}

	/**
	 *	funcion existe
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $IdPedido string
	 *  @return true/false
	 */	
	 
	public function existe($Codigo) {
        $this->query =" 
			    SELECT * FROM Productos
 			    WHERE Codigo = '$Codigo'";
				if (count($this->get_results_from_query_list()) > 0) {
				   return true;	
				}else{   
				   return false;
				}		
	}
	/**
	 *	listarPorTipo : Lista productos por tipo
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $Codigo string
	 *  @return array 
	 */	
	public function listarPorTipo($Tipo) {
        $this->query =" 
			   SELECT * FROM Productos
			   WHERE Tipo = '$Tipo'";
		 
		return $this->get_results_from_query_list();
	}
					
	/**
	 *	listarPorTipo : Lista productos por tipo
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $Codigo string
	 *  @return array 
	 */	
	public function listarPorSubTipo($SubTipo) {
        $this->query =" 
			   SELECT * FROM Productos
			   WHERE SubTipo = '$SubTipo'";
		 
		return $this->get_results_from_query_list();
	}
	/**
	 *	asigna estado
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param $IdPedido string
	 *  @return array 
	 */	
	public function asignaEstado($Codigo) {
		$columnas = array();
		$this->query = "

			   SELECT EstadoIni FROM Productos AS P INNER JOIN SubtipoProductos AS S ON (P.Tipo = S.Tipo and P.Subtipo = S.Subtipo)
			   WHERE P.Codigo = '$Codigo'"; 
			   foreach($this->get_results_from_query_list() as $columnas) {
			 	$Estado= $columnas ['EstadoIni'];
			   }
			   return ($Estado);
	}		
			
					/**
	 *	Metodo constructor de la clase
	 *	
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param  void
	 *  @return void
	 */

    function __construct() {
	//	$this->db_name = 'DBGICS';
    }

	/**
	 *	Metodo destructor de la clase
	 *	
	 *	@author		Juan Gimenez Aguilar
	 *	@version 	1.1
	 *  @param  void
	 *  @return void
	 */

    function __destruct() {
        unset($this);
    }
}
?>
