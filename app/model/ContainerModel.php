<?php
namespace App\Model; //Donde estamos

use App\Lib\Database; //Importamos el archivo que conecta a la base de datos
use App\Lib\Response; //Importamos el archivo que arma la respuesta

class ContainerModel { //Nombre de la clase
    private $db;
    private $container = 'envase';
    private $response;

    //Construimos la clase ContainerModelo
    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function Get($id) {
        try 
        {
            $result = array();

            $stm = $this->db->prepare(
                "SELECT e.idenvase,
                e.idunidadmedida unidad, 
                e.marca me, 
                e.modelo,
                e.descripcion hd,
                e.cantidad,
                e.buenestado,
                e.monto,
                e.fechaactualizado,
                um.nombre num, 
                um.descripcion dum, 
                um.simbolo,
                ea.idalmacen almacen,                        
                ea.fecha,
                ea.idmovimiento,
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
                
                FROM envase e
                
                join unidadmedida um
    
                on e.idunidadmedida = um.idunidadmedida
                
                join envasealmacen ea
                
                on e.idenvase = ea.idenvase
                
                join almacen al
                
                on al.idalmacen = ea.idalmacen            
                
                join movimiento mo
                
                on ea.idmovimiento = mo.idmovimiento  
                
                join usuario us
                
                on ea.idusuario = us.idusuario  
                
                join persona p
                
                on us.idusuario = p.idpersona
                
                where e.idenvase = ?" );
            
            $stm->execute(array($id));

            $this->response->setResponse(true);

            $this->response->result = $stm->fetch();
            
            if($this->response->result!=null){
            $this->response->result->unidad= array(
                    "idunidadmedida" => $this->response->result->unidad,
                    "nombre"=>$this->response->result->num,
                    "descripcion"=>$this->response->result->dum,
                    "simbolo"=>$this->response->result->simbolo
                );
                
                unset($this->response->result->num);
                unset($this->response->result->dum);
                unset($this->response->result->simbolo);
                
            $this->response->result->almacen= array(
                    "idalmacen" => $this->response->result->almacen,
                  /*  "marca"=> $this->response->result->me,*/
                    "idtipoalmacen"=>$this->response->result->idtipoalmacen,
                    "ubicacion"=>$this->response->result->ubicacion,
                    "capacidad"=>$this->response->result->capacidad,
                    "nombre"=>$this->response->result->alm
                );
                unset($this->response->result->ubicacion);
                unset($this->response->result->capacidad);
                unset($this->response->result->alm);
                unset($this->response->result->me);
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
                    unset($this->response->result->np);  }
        
            
           
            return $this->response;
             
            
        }
        catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }


    }
    public function GetAll() {
        try 
        {
            $result = array();

            $stm = $this->db->prepare(
                "SELECT e.idenvase,
                e.idunidadmedida unidad,
                e.marca,
                e.modelo,
                e.descripcion,
                e.cantidad,
                e.buenestado,
                e.monto,
                e.fechaactualizado,
                um.nombre,
                um.descripcion umd,
                um.simbolo
                from envase e join unidadmedida um
                on e.idunidadmedida = um.idunidadmedida" );
            
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();
            foreach($this->response->result as $key=>$value){
                $value->unidad = array(
                    "idunidadmedida" => $value->unidad,
                    "nombre" =>$value->nombre,
                    "descrpcion" =>$value->umd,
                    "simbolo" =>$value->simbolo

                );
                unset ($value->nombre);
                unset ($value->descripcion);
                unset ($value->simbolo);
            }
        
            
           
            return $this->response;
             
            
        }
        catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }


    }
//funciona.
    public function Insert($data)
    {
        try {
            $sql = "INSERT INTO envase(idunidadmedida, marca, modelo, descripcion, cantidad, buenestado,monto,fechaactualizado)
            VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                (select now())
            );";

            $this->db->prepare($sql)
                ->execute(
                    array(
                        $data['idunidadmedida'],
                        $data['marca'],
                        $data['modelo'],
                        $data['descripcion'],
                        $data['cantidad'],
                        $data['buenestado'],
                        $data['monto']
                    )
                );

            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }



    public function Update($data)
    {
        try {
            if (isset($data['idenvase'])) {
                $sql = "UPDATE $this->container set
                            
                            idunidadmedida  = ?,
                            marca = ?,
                            modelo = ?,
                            descripcion = ?,
                            cantidad    = ?,
                            buenestado  = ?,
                            monto = ?,
                            fechaactualizado = (select now())
                        WHERE idenvase = ?";

                $idenvase = intval($data['idenvase']);
                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['idunidadmedida'],
                            $data['marca'],
                            $data['modelo'],
                            $data['descripcion'],
                            $data['cantidad'],
                            $data['buenestado'],
                            $data['monto'],
                            $idenvase
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
                ->prepare("delete from $this->container where idenvase = ?");

            $stm->execute(array($id));

            $this->response->setResponse(true);
            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }


}



//aa