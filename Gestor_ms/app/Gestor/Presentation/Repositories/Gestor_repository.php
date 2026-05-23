<?php

namespace App\Gestor\Presentation\Repositories;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Gestor\Controllers\Historia_Controller;
use Exception;

class Gestor_Repository {


    function all(Request $request, Response $response): Response {
        $controller = new Historia_Controller();
        $historias  = $controller->getHistorias();

        $response->getBody()->write($historias);

        return $response->withHeader("Content-Type", "application/json");
    }

    function create(Request $request, Response $response): Response {
        $bodyRequest = $request->getBody()->getContents();

        $data = json_decode($bodyRequest, true);

        $controller = new Historia_Controller();
        $historia   = $controller->guardarHistoria($data);

        $response->getBody()->write($historia);

        return $response
            ->withStatus(201)
            ->withHeader("Content-Type", "application/json");
    }

    function detail(Request $req, Response $resp, array $args): Response {
        try {
            $id = (int) $args['id'];

            $controller = new Historia_Controller();
            $historia   = $controller->getHistoria($id);

            $resp->getBody()->write($historia->toJson());
            return $resp->withHeader("Content-Type", "application/json");

        } catch (Exception $ex) {
            $resp->getBody()->write(json_encode(['error' => $ex->getMessage()]));
            $code = $ex->getCode() == 1 ? 404 : 400;
            return $resp->withStatus($code)->withHeader("Content-Type", "application/json");
        }
    }

    function update(Request $req, Response $resp, array $args): Response {
        try {
            $id   = (int) $args['id'];
            $data = json_decode($req->getBody()->getContents(), true);

            $controller = new Historia_Controller();
            $historia   = $controller->modificarHistoria($id, $data);

            $resp->getBody()->write($historia->toJson());
            return $resp
                ->withStatus(200)
                ->withHeader("Content-Type", "application/json");

        } catch (Exception $ex) {
            $resp->getBody()->write(json_encode(['error' => $ex->getMessage()]));
            $code = $ex->getCode() == 1 ? 404 : 400;
            return $resp->withStatus($code)->withHeader("Content-Type", "application/json");
        }
    }

    function delete(Request $req, Response $resp, array $args): Response {
        try {
            $id = (int) $args['id'];

            $controller = new Historia_Controller();
            $controller->borrarHistoria($id);

            $resp->getBody()->write(json_encode(['msg' => 'Historia eliminada correctamente']));
            return $resp
                ->withStatus(200)
                ->withHeader("Content-Type", "application/json");

        } catch (Exception $ex) {
            $resp->getBody()->write(json_encode(['error' => $ex->getMessage()]));
            $code = $ex->getCode() == 1 ? 404 : 400;
            return $resp->withStatus($code)->withHeader("Content-Type", "application/json");
        }
    }

    function bySprint(Request $req, Response $resp, array $args): Response {
        try {
            $sprint_id = (int) $args['sprint_id'];

            $controller = new Historia_Controller();
            $historias  = $controller->getHistoriaPorSprint($sprint_id);

            $resp->getBody()->write($historias);
            return $resp->withHeader("Content-Type", "application/json");

        } catch (Exception $ex) {
            $resp->getBody()->write(json_encode(['error' => $ex->getMessage()]));
            $code = $ex->getCode() == 1 ? 404 : 400;
            return $resp->withStatus($code)->withHeader("Content-Type", "application/json");
        }
    }
}