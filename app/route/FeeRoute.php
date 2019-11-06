<?php
use App\Model\AlmacenModel;


$app->group('/almacen', function () {

    $this->get('', function ($req, $res, $args) {
        $um = new almacenModel();
        
        $res
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );

        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

    });
    
    $this->get('/{id}', function ($req, $res, $args) {
        $um = new almacenModel();
        
        $res
           ->getBody()
           ->write(
            json_encode(
                $um->Get($args['id'])
            )
        );

        
        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );
    });

     $this->get('/members/{id}', function ($req, $res, $args) {
        $um = new almacenModel();
        
        $res
           ->getBody()
           ->write(
            json_encode(
                $um->GetByMemberId($args['id'])
            )
        );

        
        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );
    });

    $this->post('', function ($req, $res) {
        $um = new almacenModel();
        
        $res
           ->getBody()
           ->write(
            json_encode(
                $um->InsertOrUpdate(
                    $req->getParsedBody()
                )
            )
        );
        
        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );
    });

    $this->post('/bulk', function ($req, $res) {
        $um = new almacenModel();
        
        $res
           ->getBody()
           ->write(
            json_encode(
                $um->CreateByMembers(
                    $req->getParsedBody()
                )
            )
        );
        
        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );
    });

    $this->put('/put', function ($req, $res) {
        $um = new almacenModel();
        
        $res
           ->getBody()
           ->write(
            json_encode(
                $um->RegisterPay(
                    $req->getParsedBody()
                )
            )
        );
        
        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );
    });
    
    $this->delete('/{id}', function ($req, $res, $args) {
        $um = new almacenModel();
        
        $res
           ->getBody()
           ->write(
            json_encode(
                $um->Delete($args['id'])
            )
        );
        
        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );
    });

});