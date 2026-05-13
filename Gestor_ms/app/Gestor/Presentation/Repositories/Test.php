<?php
namespace App\Contactos\Presentation\Repositories;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TestRepository{

    function default(Response $response){
        $response->getBody()->write("Hello world!");
        return $response;
    }

    function hola(Request $request, Response $response){
        $response->getBody()->write("Hola Pepe!");
        return $response;
    }
}