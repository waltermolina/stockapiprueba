<?php
use App\Model\UserModel;

$app->group('/users', function () {

    //$this->get('test', function ($req, $res, $args) {
    //    return $res->getBody()
    //               ->write('Hello Users');
    //});

    $this->post('/login', function ($req, $res, $args) {

        $um = new UserModel();
        $secret = $this->get('settings')['jwt']['secret'];

        $res
            ->getBody()
            ->write(
                json_encode(
                    $um->Login(
                        $req->getParsedBody(),
                        $secret
                    )
                )
            );

        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

    });

    $this->get('', function ($req, $res, $args) {
        $um = new UserModel();

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
        $um = new UserModel();

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

    $this->post('', function ($req, $res) {
        $um = new UserModel();

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

    $this->post('/register', function ($req, $res) {
        $um = new UserModel();

        $res
            ->getBody()
            ->write(
                json_encode(
                    $um->Register(
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
        $um = new UserModel();

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
