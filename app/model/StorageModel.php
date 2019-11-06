<?php
namespace App\Model; 
use App\Lib\Database; 
use App\Lib\Response;
// unidad medida del 1-4
class StorageModel{
    //Rafael Perez! pero perez de pereza!
    private $db;
    private $stro = 'almacen';
    private $ta = "tipoalmacen";
    private $response;  

    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    public function GetAll() {
        try {
            $stm = $this->db->prepare(
                "SELECT 
                a.idalmacen,
                a.idtipoalmacen tipo,
                a.ubicacion,
                a.capacidad,
                a.nombre,
                t.descripcion
                
                FROM almacen a

                join tipoalmacen t

                on a.idtipoalmacen = t.idtipoalmacen" );

            $stm->execute();

            $this->response->setResponse(true);

            $this->response->result = $stm->fetchAll();

            foreach($this->response->result as $key=>$value){
                $value->tipo=array(
                    "idtipoalmacen" => $value->tipo,
                    "descripcion"=>$value->descripcion
                    
                );

                unset($value->descripcion);
            }

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function Get($id)
    {
        try
        {
           
            $stm = $this->db->prepare(
                "SELECT 
                a.idalmacen,
                a.idtipoalmacen tipo,
                a.ubicacion,
                a.capacidad,
                a.nombre,
                t.descripcion
                
                FROM almacen a

                join tipoalmacen t

                on a.idtipoalmacen = t.idtipoalmacen

                WHERE idalmacen = ?");
            $stm->execute(array($id));

            $this->response->setResponse(true);

            $this->response->result = $stm->fetch();            
    
            $this->response->result->tipo= array(
                "idtipoalmacen" => $this->response->result->tipo,
                "descripcion"=>$this->response->result->descripcion
                
            );

            unset($this->response->result->descripcion);

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }


    public function Insert($data)
    {
        try {
            $stm = "INSERT INTO $this->stro
                    (ubicacion,capacidad,tipo,nombre)
                    VALUES (?,?,?,?);";

            $this->db->prepare($stm)
                ->execute(
                    array(
                        $data['ubicacion'],
                        $data['capacidad'],
                        $data['tipo'],
                        $data['nombre']
                    )
                );

            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Update($data){
        try{
        if (isset($data['idalmacen'])) {
            $sql = "UPDATE $this->stro SET
                        ubicacion     = ?,
                        capacidad     = ?,
                        tipo          = ?,
                        nombre        = ?
                    WHERE idAlmacen = ?";

            $id = intval($data['idalmacen']);
            $this->db->prepare($sql)
                ->execute(
                    array(
                        $data['ubicacion'],
                        $data['capacidad'],
                        $data['tipo'],
                        $data['nombre'],
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



    public function delete($id) {
        try
        {
            $stm = $this->db
                ->prepare("DELETE FROM $this->stro WHERE idalmacen = ?");

            $stm->execute(array($id));

            $this->response->setResponse(true);
            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
    
}