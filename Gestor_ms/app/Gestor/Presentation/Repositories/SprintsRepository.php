<?php
namespace App\Gestor\Presentation\Repositories;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\SprintsController;
use Exception;

class SprintsRepository {

    function all(Request $request, Response $response) {
        $controller = new SprintsController();
        $sprints = $controller->getSprints();
        $response->getBody()->write($sprints);
        return $response->withHeader("Content-Type", "application/json");
    }

    function create(Request $request, Response $response) {
        $bodyRequest = $request->getBody()->getContents();
        $data = json_decode($bodyRequest, true);
        $controller = new SprintsController();
        $sprint = $controller->guardarSprint($data);
        $response->getBody()->write($sprint);
        return $response
            ->withStatus(201)
            ->withHeader("Content-Type", "application/json");
    }

    function detail(Request $req, Response $resp, $args) {
        try {
            $id = $args['id'];
            $controller = new SprintsController();
            $sprint = $controller->getSprint($id);
            $resp->getBody()->write($sprint->toJson());
            return $resp->withHeader("Content-Type", "application/json");
        } catch (Exception $ex) {
            $resp->getBody()->write("Error: " . $ex->getMessage());
            $code = $ex->getCode() == 1 ? 404 : 400;
            return $resp->withStatus($code);
        }
    }

    function update(Request $req, Response $resp, $args) {
        try {
            $id = $args['id'];
            $data = json_decode($req->getBody()->getContents(), true);
            $controller = new SprintsController();
            $sprint = $controller->modificarSprint($id, $data);
            $resp->getBody()->write($sprint->toJson());
            return $resp->withStatus(200)->withHeader("Content-Type", "application/json");
        } catch (Exception $ex) {
            $resp->getBody()->write("Error: " . $ex->getMessage());
            $code = $ex->getCode() == 1 ? 404 : 400;
            return $resp->withStatus($code);
        }
    }

    function delete(Request $req, Response $resp, $args) {
        try {
            $id = $args['id'];
            $controller = new SprintsController();
            $controller->borrarSprint($id);
            $resp->getBody()->write(json_encode(['msg' => 'Sprint borrado']));
            return $resp->withStatus(200)->withHeader("Content-Type", "application/json");
        } catch (Exception $ex) {
            $resp->getBody()->write("Error: " . $ex->getMessage());
            $code = $ex->getCode() == 1 ? 404 : 400;
            return $resp->withStatus($code);
        }
    }
}