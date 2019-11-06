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
                "SELECT 
                    *
                FROM
                    envase where idenvase = ?");
            
            $stm->execute(array($id));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();
        
            
           
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
                "SELECT 
                    *
                FROM
                    envase" );
            
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();
        
            
           
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