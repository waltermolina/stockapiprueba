<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

//this works, but i can't get the correct path string to protect all api
//ignore works well

$app->add(new Tuupola\Middleware\JwtAuthentication([
    "path" => "/api/",
    "ignore" => ["/public/users/login", "/public/users/register"],
    "attribute" => "decoded_token_data",
    //"secure" => false,
    "header" => "olivia",
    //"relaxed" => ["localhost", "dev.example.com"],
    "secret" => "unsecretoquenotevoyacontar",
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    },
]));
