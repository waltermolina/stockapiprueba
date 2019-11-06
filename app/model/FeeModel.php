<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;

class almacenModel {
    private $db;
    private $almacentbl = 'fee';
    private $membertbl = 'member';
    private $almacengrouptbl = 'almacengroup';
    private $response;
    
    public function __CONSTRUCT() {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }
    
    public function GetAll() {
        try {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->feetbl");
            $stm->execute();
            
            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();
        
            foreach ($this->response->result as $key => $value) {
                $stmmember = $this->db->prepare(
                    "SELECT 
                        idmember,
                        membernumber,
                        lastname,
                        firstname,
                        isparent
                    FROM member
                    WHERE idmember = ?;
                ");
                $stmmember->execute(
                        array(
                            $value->idmember
                        )
                    );
                $value->member = $stmmember->fetch();

                $stmtype = $this->db->prepare(
                    "SELECT 
                        *
                    FROM feetype
                    WHERE idfeetype = ?;
                ");
                $stmtype->execute(
                        array(
                            $value->type
                        )
                    );
                $value->type = $stmtype->fetch();
            }


            return $this->response;
        } catch(Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
    
    public function Get($id) {
        try {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->feetbl WHERE idfee = ?");
            $stm->execute(array($id));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            $stmmember = $this->db->prepare(
                "SELECT
                    idmember,
                    membernumber,
                    lastname,
                    firstname,
                    isparent
                FROM member
                WHERE idmember = ?;
            ");
            $stmmember->execute(
                array(
                    $this->response->result->idmember
                )
            );
            $this->response->result->member = $stmmember->fetch();

            $stmtype = $this->db->prepare(
                "SELECT
                    idfeetype,
                    name,
                    description,
                    value
                FROM feetype
                WHERE idfeetype = ?;
            ");
            $stmtype->execute(
                array(
                    $this->response->result->type
                )
            );
            $this->response->result->type = $stmtype->fetch();


            return $this->response;
        } catch(Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function GetByMemberId($idmember) {
        try {
            $result = array();

            $stmmember = $this->db->prepare(
                "SELECT * FROM $this->membertbl WHERE idmember = ?"
            );
            $stmmember->execute(array($idmember));

            $member = $stmmember->fetch();

            if($member->isparent == 1 ){
                
                $stmFees = $this->db->prepare(
                    "SELECT 
                        f.* 
                    FROM
                        $this->almacengrouptbl fg
                        JOIN $this->feetbl f ON fg.childmember = f.idmember
                    WHERE
                        parentmember = ? "
                );

            } else {

                $stmFees = $this->db->prepare(
                    "SELECT almacen
                        *
                    FROM
                        $this->stockbl
                    WHERE
                        idmember = ? "
                );

            }

            $stmFees->execute(array($idmember));
            $this->response->setResponse(true);
            $this->response->result = $stmFees->fetchAll();

            foreach ($this->response->result as $key => $value) {
                $stmfeetype = $this->db->prepare(
                    "SELECT 
                        idfeetype,
                        name,
                        description,
                        value
                    FROM feetype WHERE idfeetype = ?;"
                );

                $stmfeetype->execute(
                    array(
                        $value->type,
                    )
                );
                $value->type = $stmfeetype->fetch();

                $stmfeemember = $this->db->prepare(
                    "SELECT 
                        idmember,
                        membernumber,
                        lastname,
                        firstname,
                        isparent
                    FROM member WHERE idmember = ?;"
                );

                $stmfeemember->execute(
                    array(
                        $value->idmember,
                    )
                );
                $value->member = $stmfeemember->fetch();
            }

            return $this->response;

        } catch(Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
    
    public function InsertOrUpdate($data) {
        try {
            if(isset($data['idfee'])) {
                $sql = "UPDATE $this->feetbl SET 
                            value           = ?, 
                            discountpercent = ?, 
                            discountvalue   = ?, 
                            total           = ?, 
                            description     = ?, 
                            type            = ?, 
                            expire          = ?, 
                            year            = ?, 
                            month           = ?, 
                            pay             = ?, 
                            lastmodified    = (select now())
                        WHERE idfee = ?";
                $idfee = intval($data['idfee']);
                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['value'], 
                            $data['discountpercent'],
                            $data['discountvalue'],
                            $data['total'],
                            $data['description'],
                            $data['type'],
                            $data['expire'],
                            $data['year'],
                            $data['month'],
                            $data['pay'],
                            $idfee
                        )
                    );
            } else {
                $sql = "INSERT INTO $this->feetbl 
                            (
                                idmember, 
                                value, 
                                discountpercent, 
                                discountvalue, 
                                total, 
                                description, 
                                type, 
                                generated, 
                                expire, 
                                year, 
                                month, 
                                pay, 
                                lastmodified
                            ) 
                            VALUES (
                                ?, 
                                ?, 
                                ?, 
                                ?, 
                                ?, 
                                (select now()), 
                                ?, 
                                ?, 
                                ?, 
                                ?, 
                                (select now())
                            );";
                
                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['idmember'], 
                            $data['value'],
                            $data['discount'],
                            $data['description'],
                            $data['type'],
                            $data['expire']
                        )
                    ); 
            }
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function BulkInsert($data) {
        try {

            $expirationdate = $data['date']."-10";
            $date = explode("-", $data['date']);

            foreach($data['selectedMembers'] as $member) {

                $sql = "INSERT INTO $this->feetbl
                    (datetime,amount,expirationdate,idmember,year,month)
                    VALUES ((select now()),?,?,?,?,?)";
                
                $this->db->prepare($sql)
                    ->execute(
                    array(
                        $data['amount'],
                        $expirationdate,
                        $member, 
                        $date[0],
                        $date[1]
                    )
                );
            }
            
            $this->response->setResponse(true);
            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function RegisterPay($data) {
        try {
            $sql = "UPDATE $this->feetbl SET 
                pay = ?
                WHERE idfee = ?";
            
            $idfee = intval($data['idfee']);
            $this->db->prepare($sql)
                ->execute(
                    array(
                        $data['pay'], 
                        $idfee
                    )
                );
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Delete($id) {
        try {
            $stm = $this->db
                        ->prepare("DELETE FROM $this->feetbl WHERE idfee = ?");                      

            $stm->execute(array($id));
            
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

}