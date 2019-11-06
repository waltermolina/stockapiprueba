<?php
use App\Model\MemberModel;

$app->group('/members', function () {

    //$this->get('test', function ($req, $res, $args) {
    //    return $res->getBody()
    //               ->write('Hello Users');
    //});

    $this->get('', function ($req, $res, $args) {
        $um = new MemberModel();

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
        $um = new MemberModel();

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

    $this->get('/{id}/owed', function ($req, $res, $args) {
        $um = new MemberModel();

        $res
            ->getBody()
            ->write(
                json_encode(
                    $um->GetFeesOwed($args['id'])
                )
            );
        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

    });
    $this->get('/dni/{dni}', function ($req, $res, $args) {
        $um = new MemberModel();

        $res
            ->getBody()
            ->write(
                json_encode(
                    $um->GetByDNI($args['dni'])
                )
            );
        return $res->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

    });

    $this->post('', function ($req, $res) {
        $um = new MemberModel();

        return $res
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $um->InsertOrUpdate(
                        $req->getParsedBody()
                    )
                )
            );
    });

    $this->delete('/{id}', function ($req, $res, $args) {
        $um = new MemberModel();

        return $res
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
                json_encode(
                    $um->Delete($args['id'])
                )
            );

        //$res
        //   ->getBody()
        //   ->write(
        //    json_encode(
        //        $um->Delete($args['id'])
        //    )
        //);

        //return $res->withHeader(
        //    'Content-type',
        //    'application/json; charset=utf-8'
        //);
    });
});
