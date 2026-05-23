<?php

namespace App\Gestor\Presentation\Repositories;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Gestor\Controllers\Informe_Controller;
use Exception;

class Informes_Repository {

    function bySprint(Request $req, Response $resp, array $args): Response {
        try {
            $sprint_id = (int) $args['sprint_id'];

            $controller = new Informe_Controller();
            $informe    = $controller->generarInforme($sprint_id);

            $resp->getBody()->write($informe->toJson());

            return $resp->withHeader("Content-Type", "application/json");

        } catch (Exception $ex) {
            $resp->getBody()->write(json_encode(['error' => $ex->getMessage()]));
            $code = $ex->getCode() == 1 ? 404 : 400;
            return $resp->withStatus($code)->withHeader("Content-Type", "application/json");
        }
    }

    function all(Request $req, Response $resp): Response {
        try {
            $controller = new Informe_Controller();

            $informes = $controller->generarTodosInformes();

            $data = array_map(function($informe) {
                return json_decode($informe->toJson(), true);
            }, $informes);

            $resp->getBody()->write(json_encode($data));

            return $resp->withHeader("Content-Type", "application/json");

        } catch (Exception $ex) {
            $resp->getBody()->write(json_encode(['error' => $ex->getMessage()]));
            return $resp->withStatus(400)->withHeader("Content-Type", "application/json");
        }
    }
}
