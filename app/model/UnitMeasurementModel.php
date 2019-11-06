<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class UnitMeasurementModel {
    private $db;
    private $unitm = 'unidadmedida';
    private $response;
    
    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    /*Get all units of measurement*/
    public function GetAll()
    {
        try {
           
                $stm = $this->db->prepare(
                    "SELECT mt.idmateriaprima,
                    mt.idunidadmedida unidad, 
                    mt.nombre pn,
                    mt.fechaactualizado,
                    mt.descripcion,
                    mt.buenestado,
                    mt.cantidad,
                    mt.monto,
                    um.nombre nmd, 
                    um.descripcion umd, 
                    um.simbolo
    
                    from materiaprima mt
    
                    join unidadmedida um
    
                    on mt.idunidadmedida = um.idunidadmedida"
                    
                    );

                $stm->execute();  

                $this->response->setResponse(true);

            $this->response->result = $stm->fetchAll();
            foreach($this->response->result as $key=>$value){
                $value->unidad=array(
                    "idunidadmedida" => $value->unidad,
                    "nombre"=>$value->nmd,
                    "descripcion"=>$value->umd,
                    "simbolo"=>$value->simbolo
                );

                unset($value->nombre);
                unset($value->umd);
                unset($value->simbolo);
            }

            
            return $this->response;  
            
            

            
            
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
            }
    }

    /*Get an UM by its id*/
    public function Get($id)
    {
        try {
            $stm = $this->db->prepare("
            SELECT mt.idmateriaprima,
            mt.idunidadmedida unidad, 
            mt.nombre pn,
            mt.fechaactualizado,
                    mt.descripcion,
                    mt.buenestado,
                    mt.cantidad,
                    mt.monto,
                    um.nombre nmd, 
                    um.descripcion umd, 
                    um.simbolo,
                    ma.idalmacen almacen,                        
                ma.idmovimiento movimiento,
                ma.fecha,
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
                
                from materiaprima mt
                
                join unidadmedida um
    
                on mt.idunidadmedida = um.idunidadmedida
                
                join materiaprimaalmacen ma
                
                on mt.idmateriaprima = ma.idmateriaprima
                
                join almacen al
                
                on al.idalmacen = ma.idalmacen            
                
                join movimiento mo
                
                on ma.idmovimiento = mo.idmovimiento  
                
                join usuario us
                
                on ma.idusuario = us.idusuario  
                
                join persona p
                
                on us.idusuario = p.idpersona
                
                where ma.idmateriaprima = ?");
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
                        unset($this->response->result->np);                                                        
                
                    
    
                
                }

            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    /*Create new UnitMeasurement*/
    public function Insert($data)
    {
        try {
            $sql = "INSERT INTO $this->unitm
                    (nombre, descripcion)
                    VALUES (?,?);";

            $this->db->prepare($sql)
                ->execute(
                    array(
                        $data['nombre'],
                        $data['descripcion'],
                        
                    )
                );

            $this->response->setResponse(true);

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    /*Update a UnitMeasurement by its id*/
    public function Update($data)
    {
        try {
            if (isset($data['idunidadmedida'])) {
                $sql = "UPDATE $this->unitm SET
                            nombre      = ?,
                            descripcion = ?                           

                        WHERE idunidadmedida = ?";

                $idunidadmedida = intval($data['idunidadmedida']);
                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['nombre'],
                            $data['descripcion'],
                            $idunidadmedida,
                        )
                    );
            }

            $this->response->setResponse(true);

            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    /*Delete a UM by id*/
    public function Delete($id)
    {
        try
        {
            $stm = $this->db
                ->prepare("DELETE FROM $this->unitm WHERE idunidadmedida = ?");

            $stm->execute(array($id));

            $this->response->setResponse(true);
            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
}
