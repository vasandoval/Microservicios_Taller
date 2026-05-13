<?php

use Slim\App;
use App\Contactos\Presentation\Repositories\Test;
use App\Contactos\Presentation\Repositories\ContactosRepository;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', [Test::class, 'default']);
    $app->post('/hola', [Test::class, 'hola']);

    $app->post('/contacto', [ContactosRepository::class, 'create']);
    $app->get('/contactos', [ContactosRepository::class, 'all']);
    $app->get('/contacto/{id}', [ContactosRepository::class, 'detail']);
    $app->put('/contacto/{id}', [ContactosRepository::class, 'update']);
    $app->delete('/contacto/{id}', [ContactosRepository::class, 'delete']);

    $app->group('/contactos-v2', function (RouteCollectorProxy $group) {
        $group->get('', [ContactosRepository::class, 'all']);
        $group->get('/{id}', [ContactosRepository::class, 'detail']);
        $group->post('', [ContactosRepository::class, 'create']);
        $group->put('/{id}', [ContactosRepository::class, 'update']);
        $group->delete('/{id}', [ContactosRepository::class, 'delete']);
    });
};