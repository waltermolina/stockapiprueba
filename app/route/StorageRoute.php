<?php
use App\Model\StorageModel;

$app->group('/storage', function () {

    $this->get('', function ($req, $res, $args) {
        $modelo = new StorageModel();

        $res
            ->getBody()
            ->write(
                json_encode(
                    $modelo->GetAll() // Qué función usaré de mi modelojjnkjnkjnknk
                )
            );

        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

    });

    $this->get('/{id}', function ($req, $res, $args) {
        $um = new StorageModel();

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

    $this->post('/', function ($req, $res) {
        $um = new StorageModel();
        
        $res
           ->getBody()
           ->write(
            json_encode(
                $um->Insert(
                    $req->getParsedBody()
                )
            )
        );
        
        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );
    });

    $this->put('/', function ($req, $res) {
        $um = new StorageModel();
        
        $res
           ->getBody()
           ->write(
            json_encode(
                $um->Update(
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
        $um = new StorageModel();

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