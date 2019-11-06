<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class RawMaterialModel
{

    private $db;
    private $table = 'materiaprima';
    private $response;

    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    /*Get all Raw Materials */
    public function GetAll()
    {
        try {

            $stm = $this->db->prepare("SELECT m.idmateriaprima,
            m.idunidadmedida unidad, 
            m.nombre, 
            m.descripcion,
            m.cantidad,
            m.buenestado,
            m.monto,
            m.fechaactualizado,
            um.nombre numd, 
            um.descripcion umd, 
            um.simbolo FROM materiaprima m
            
            join unidadmedida um

            on m.idunidadmedida = um.idunidadmedida");

            $stm->execute();

            $this->response->setResponse(true);

            $this->response->result = $stm->fetchAll();

            foreach($this->response->result as $key=>$value){
                $value->unidad=array(
                    "idunidadmedida" => $value->unidad,
                    "nombre"=>$value->numd,
                    "descripcion"=>$value->umd,
                    "simbolo"=>$value->simbolo
                );

                unset($value->numd);
                unset($value->umd);
                unset($value->simbolo);}

            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    /*Get Raw Material by id*/
    public function Get($id)
    {
        try {
            $stm = $this->db->prepare("
            SELECT m.idmateriaprima,
            m.idunidadmedida unidad,
            m.nombre hn,
            m.descripcion,
            m.cantidad,
            m.buenestado,
            m.monto,
            m.fechaactualizado,
            um.nombre numd, 
            um.descripcion umd, 
            um.simbolo,
            am.idalmacen almacen,                        
            am.idmovimiento movimiento,
            am.fecha,
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
            
            FROM materiaprima m
            
            join unidadmedida um

            on m.idunidadmedida = um.idunidadmedida
            
            join materiaprimaalmacen am
            
            on m.idmateriaprima = am.idmateriaprima
            
            join almacen al
            
            on al.idalmacen = am.idalmacen            
            
            join movimiento mo
            
            on am.idmovimiento = mo.idmovimiento  
            
            join usuario us
            
            on am.idusuario = us.idusuario  
			
            join persona p
			
            on us.idusuario = p.idpersona
            
            where m.idmateriaprima =?");
            $stm->execute(array($id));

            $this->response->setResponse(true);

            $this->response->result = $stm->fetch();

            $this->response->result->unidad= array(
                "idunidadmedida" => $this->response->result->unidad,
                "nombre"=>$this->response->result->numd,
                "descripcion"=>$this->response->result->umd,
                "simbolo"=>$this->response->result->simbolo
            );

            unset($this->response->result->numd);
            unset($this->response->result->umd);
            unset($this->response->result->simbolo);

            $this->response->result->almacen= array(
                "idalmacen" => $this->response->result->almacen,
                "nombre"=> $this->response->result->hn,
                "idtipoalmacen"=>$this->response->result->idtipoalmacen,
                "ubicacion"=>$this->response->result->ubicacion,
                "capacidad"=>$this->response->result->capacidad,
                "nombre"=>$this->response->result->alm);

            unset($this->response->result->ubicacion);
            unset($this->response->result->capacidad);
            unset($this->response->result->alm);
            unset($this->response->result->hn);
            unset($this->response->result->idtipoalmacen);

            unset($this->response->result->mov);
                    unset($this->response->result->od);
                    unset($this->response->result->fecha);

                    unset($this->response->result->sa);

                    unset($this->response->result->apellido);
                    unset($this->response->result->np);
            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    /*Create new Raw Material*/
    public function Insert($data)
    {
        try {
            $sql = "INSERT INTO materiaprima
            (idunidadmedida,
            nombre, 
            descripcion, 
            cantidad, 
            buenestado,
            monto,
            fechaactualizado)
            VALUES (?,?,?,?,?,?,(select now()));";

            $this->db->prepare($sql)
                ->execute(
                    array(
                        $data['idunidadmedida'],
                        $data['nombre'],
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

    /*Update a Raw Material by its id*/
    public function Update($data)
    {
        try {
            if (isset($data['idmateriaprima'])) {
                $sql = "UPDATE $this->table SET
                            idunidadmedida = ?,
                            nombre      = ?,
                            descripcion = ?,
                            cantidad    = ?,
                            buenestado  = ?,
                            monto       = ?,
                            fechaactualizado = (select now()) 

                        WHERE idmateriaprima = ?";

                $idmateriaprima = intval($data['idmateriaprima']);
                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['idunidadmedida'],
                            $data['nombre'],
                            $data['descripcion'],
                            $data['cantidad'],                        
                            $data['buenestado'],
                            $data['monto'],
                            $idmateriaprima,
                        )
                    );
            }

            $this->response->setResponse(true);

            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    /*Delete a Raw Material by its idcambio*/ 
    public function Delete($id)
    {
        try
        {
            $stm = $this->db
                ->prepare("DELETE FROM $this->table WHERE idmateriaprima = ?");

            $stm->execute(array($id));

            $this->response->setResponse(true);
            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }
}
