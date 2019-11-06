<?php
use App\Model\RawMaterialModel;

$app->group('/raws', function () {

    $this->get('', function ($req, $res, $args) {
        $um = new RawMaterialModel();

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
        $um = new RawMaterialModel();

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
        $um = new RawMaterialModel();
  
        return $res
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $um->Insert(
                        $req->getParsedBody()
                    )
                )
            );
    });
    
        $this->put('/', function ($req, $res) {
            $um = new RawMaterialModel();
    
            return $res
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                ->withHeader('Content-type', 'application/json')
                ->getBody()
                ->write(
                    json_encode(
                        $um->Update(
                            $req->getParsedBody()
                        )
                    )
                );
        });
    
        $this->delete('/{id}', function ($req, $res, $args) {
            $um = new RawMaterialModel();
    
            return $res
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                ->withHeader('Content-type', 'application/json')
                ->getBody()
                ->write(
                    json_encode(
                        $um->Delete(
                            $args['id']
                        )
                    )
                );
    
        });
    });
    