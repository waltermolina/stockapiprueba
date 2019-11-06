<?php
namespace App\Model;

use App\Lib\Database;
use App\Lib\Response;
use \Firebase\JWT\JWT;

class UserModel
{
    private $db;
    private $table = 'user';
    private $response;

    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function Login($data, $secret)
    {

        try
        {
            //$input = $request->getParsedBody(); // $data

            //$result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE username = ?");
            $stm->execute(array($data['username']));

            //$this->response->setResponse(true);
            $user = $stm->fetch();
            //$this->response->result = $user;
            //return $this->response;

            // verify username.
            if (!$user) {
                $this->response->setResponse(false, "wrong user");
                return $this->response;

                //return $this->response->withJson(['error' => true, 'message' => 'Wrong email...']);
            }

            // verify password.
            if (!password_verify($data['password'], $user->password)) {
                $this->response->setResponse(false, "wrong password");
                return $this->response;

                //return $this->response->withJson(['error' => true, 'message' => 'Wrong password...']);
            }

            $now = time();
            $token = JWT::encode(['id' => $user->iduser, 'username' => $user->username, 'iat' => $now],
                $secret, "HS256");

            //return $this->response->withJson(['token' => $token]);
            $this->response->setResponse(true);
            $this->response->token = $token;

            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function GetAll()
    {
        try
        {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table");
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();

            foreach ($this->response->result as $key => $value) {
                $stmmember = $this->db->prepare(
                    "SELECT
						idmember,
                        membernumber,
                        lastname,
						firstname
					FROM member
					WHERE idmember = ?;
                ");
                $stmmember->execute(
                    array(
                        $value->idmember,
                    )
                );
                $value->member = $stmmember->fetch();
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
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE iduser = ?");
            $stm->execute(array($id));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function InsertOrUpdate($data)
    {
        try
        {
            if (isset($data['iduser'])) {
                $sql = "UPDATE $this->table SET
                            modified           = (select now()),
                            idmember             = ?,
                            amount     = ?,
                            year           = ?,
                            month            = ?,
                            expirationdate            = ?,
                            paydate            = ?
                        WHERE iduser = ?";
                $iduser = intval($data['iduser']);
                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['idmember'],
                            $data['amount'],
                            $data['year'],
                            $data['month'],
                            $data['expirationdate'],
                            $data['paydate'],
                            $iduser,
                        )
                    );
            } else {
                $sql = "INSERT INTO $this->table
                            (datetime, modified, idmember, amount, year, month, expirationdate, paydate)
                            VALUES ((select now()), (select now()),?,?,?,?,?,?)";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['idmember'],
                            $data['amount'],
                            $data['year'],
                            $data['month'],
                            $data['expirationdate'],
                            $data['paydate'],
                        )
                    );
            }

            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Register($data)
    {
        try {

            $sql = "INSERT INTO $this->table
                (
                    username,
                    salt,
                    password,
                    token,
                    created,
                    status
                )
                VALUES (
                    ?,
                    null,
                    ?,
                    null,
                    (select now()),
                    1
                )";

            $this->db->prepare($sql)
                ->execute(
                    array(
                        $data['username'],
                        password_hash($data['password'], PASSWORD_DEFAULT),
                    )
                );

            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

}
