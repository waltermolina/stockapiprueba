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
                    "SELECT * FROM unidadmedida"
                    
                    );

                $stm->execute();  

                $this->response->setResponse(true);

            $this->response->result = $stm->fetchAll();
            
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
                SELECT * FROM $this->unitm
                WHERE idunidadmedida = ?");
            $stm->execute(array($id));

            $this->response->setResponse(true);

            $this->response->result = $stm->fetch();

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
