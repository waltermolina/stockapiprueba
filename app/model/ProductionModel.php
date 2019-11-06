<?php
namespace App\Model; //Donde estamos

use App\Lib\Database; //Importamos el archivo que conecta a la base de datos
use App\Lib\Response;
//Importamos el archivo que arma la respuesta
//jejje
class ProductionModel
{ //Nombre de la clase
    private $db;
    private $dbPr = 'produccion';
    private $response;

    //Construimos la clase ProduccionModelo
    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    //Función que recupera todos los item de Produccion
    public function GetAll()
    {
        try {
            //Consulta SQL que ejecutaremos
            //statement = consulta = consulta
            $stmp = $this->db->prepare("SELECT p.idproduccion,
            p.idunidadmedida unidad,
            p.lote,
            p.nombre,
            p.fechaactualizado,
            p.descripcion,
            p.cantidad,
            p.buenestado,
            p.preciounitario,
            p.fechayhoradeproduccion,
            um.nombre,
            um.descripcion umd,
            um.simbolo
            from produccion p join unidadmedida um
            on p.idunidadmedida = um.idunidadmedida" 
            );
            $stmp->execute();

            $this->response->setResponse(true);
            $this->response->result = $stmp->fetchAll();
            foreach($this->response->result as $key=>$value){
                $value->unidad = array(
                    "idunidadmedida" => $value->unidad,
                    "nombre" =>$value->nombre,
                    "descrpcion" =>$value->umd,
                    "simbolo" =>$value->simbolo

                );
                unset ($value->numd);
                unset ($value->descripcion);
                unset ($value->simbolo);
            }
            foreach($this->response->result as $key=>$value){
                $value->preciounitario = array(
                    "preciounitario" =>$value->preciounitario,
                    "fechaactualizado "=>$value->fechaactualizado,
                    "cantidad "=>$value->cantidad
                );
                unset ($value->preciounitario);
                unset ($value->fechaactualizado);
                unset ($value->cantidad);
            }

            return $this->response;} catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;}
    }

    public function Get($id)
    {
        try {
            $stm = $this->db->prepare(" SELECT pro.idproduccion,
            pro.idunidadmedida unidad, 
            pro.preciounitario, 
            pro.lote,
            pro.nombre pn,
            pro.fechaactualizado,
            pro.descripcion,
            pro.buenestado,
            pro.cantidad,
            pro.fechayhoradeproduccion,
            um.nombre nmd, 
            um.descripcion umd, 
            um.simbolo,
            pm.idalmacen almacen,                        
            pm.idmovimiento movimiento,
            pm.fecha,
            al.idtipoalmacen,
            al.ubicacion,
            al.capacidad,
            al.nombre alm ,
            mo.nombre mov,
            mo.descripcion od,
            p.idpersona persona,
            us.idusuario usuario,
            us.usuario sa,
            p.nombre np,
            p.apellido
            
            FROM produccion pro
            
            join unidadmedida um

            on pro.idunidadmedida = um.idunidadmedida
            
            join produccionalmacen pm
            
            on pro.idproduccion = pm.idproduccion
            
            join almacen al
            
            on al.idalmacen = pm.idalmacen            
            
            join movimiento mo
            
            on pm.idmovimiento = mo.idmovimiento  
            
            join usuario us
            
            on pm.idusuario = us.idusuario  
			
            join persona p
			
            on us.idusuario = p.idpersona
            
            where pro.idproduccion =?
                 ");

            $stm->execute(array($id));

            $this->response->setResponse(true);

            $this->response->result = $stm->fetch();

            if($this->response->result!=null){
                
                $this->response->result->unidad= array(
                        "idunidadmedida" => $this->response->result->unidad,
                        "nombre"=>$this->response->result->nmd,
                        "descripcion"=>$this->response->result->umd,
                        "simbolo"=>$this->response->result->simbolo
                    );
    
                    unset($this->response->result->nombre);
                    unset($this->response->result->umd);
                    unset($this->response->result->nmd);
                    unset($this->response->result->simbolo);
    
                    $this->response->result->almacen= array(
                        "idalmacen" => $this->response->result->almacen,
                        "nombre"=> $this->response->result->pn,
                        "idtipoalmacen"=>$this->response->result->idtipoalmacen,
                        "ubicacion"=>$this->response->result->ubicacion,
                        "capacidad"=>$this->response->result->capacidad,
                        "nombre"=>$this->response->result->alm);
    
                    unset($this->response->result->ubicacion);
                    unset($this->response->result->capacidad);
                    unset($this->response->result->alm);
                    unset($this->response->result->pn);
                    unset($this->response->result->idtipoalmacen);
    
                    
                  
    
                       /* $this->response->result->movimiento= array(
                        "idmovimiento"=>$this->response->result->movimiento,
                        "nombre"=>$this->response->result->mov,
                        "descripcion"=>$this->response->result->od,
                        "fecha"=>$this->response->result->fecha);*/
    
                        unset($this->response->result->mov);
                        unset($this->response->result->od);
                        unset($this->response->result->fecha);
    
    
    
                       /* $this->response->result->usuario= array(
                        "idusuario"=>$this->response->result->usuario,
                        "usuario"=>$this ->response->result->sa);*/
    
                        unset($this->response->result->sa);
    
    
                        /*$this->response->result->persona= array(
                        "idpersona"=>$this->response->result->persona,
                        "nombre"=>$this->response->result->np,
                        "apellido"=>$this->response->result->apellido);*/
                                         
                        
                        unset($this->response->result->apellido);
                        unset($this->response->result->np);   }
                                                                 
                
            return $this->response;} catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;}
    }

//funciona! :)
// unidad medida del 1 - 4

    public function Insert($data)
    {

        try {
            $sql = "INSERT INTO $this->dbPr
                    (
                        idunidadmedida,
                        lote,
                        nombre,
                        fechaactualizado,
                        descripcion,
                        cantidad,
                        buenestado,
                        monto,
                        fechayhoradeproduccion
                    )
                    VALUES (?,?,?,(select now()),?,?,?,?,(select now()));";

            $this->db->prepare($sql)
                ->execute(
                    array(
                        $data['idunidadmedida'],
                        $data['lote'],
                        $data['nombre'],
                        $data['descripcion'],
                        $data['cantidad'],
                        $data['buenestado'],
                        $data['monto'],
                    )
                );

            $this->response->setResponse(true);

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
//funciona
    public function Update($data)
    {
        try {

            if (isset($data['idproduccion'])) {
                $sql = "UPDATE $this->dbPr SET
                        nombre = ?,
                        descripcion = ?,
                        cantidad  = ?,
                        buenestado = ?,
                        monto = ?,
                        fechaactualizado = (select now())
                    WHERE idproduccion = ?";

                $id = intval($data['idproduccion']);
                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['nombre'],
                            $data['descripcion'],
                            $data['cantidad'],
                            $data['buenestado'],
                            $data['monto'],
                            $id,
                        )
                    );
            }
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Delete($id)
    {
        try
        {
            $stm = $this->db
                ->prepare(" DELETE FROM $this->dbPr
            WHERE idproduccion = ?");

            $stm->execute(array($id));

            $this->response->setResponse(true);
            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

}
