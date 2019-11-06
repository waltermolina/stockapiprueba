 <?php
use App\Model\ContainerModel;

//
$app->group('/containers', function () {
//funciona.
    $this->get('/{id}', function ($req, $res, $args) {
        $um = new ContainerModel();

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
    //funciona.
    $this->get('', function ($req, $res, $args) {
        $um = new ContainerModel();

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
    //funciona
    $this->post('/', function ($req, $res) {
        $um = new ContainerModel();

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
    //funciona
    $this->put('/', function ($req, $res) {
        $um = new ContainerModel();

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
//funciona
    $this->delete('/{id}', function ($req, $res, $args) {
        $um = new ContainerModel();

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

    });

});
//aa