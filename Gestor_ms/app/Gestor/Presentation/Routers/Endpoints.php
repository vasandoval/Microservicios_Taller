<?php

use Slim\App;
use App\Gestor\Presentation\Repositories\TestRepository;
use App\Gestor\Presentation\Repositories\SprintsRepository;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', [TestRepository::class, 'default']);
    $app->post('/hola', [TestRepository::class, 'hola']);

    $app->post('/sprint', [SprintsRepository::class, 'create']);
    $app->get('/sprints', [SprintsRepository::class, 'all']);
    $app->get('/sprint/{id}', [SprintsRepository::class, 'detail']);
    $app->put('/sprint/{id}', [SprintsRepository::class, 'update']);
    $app->delete('/sprint/{id}', [SprintsRepository::class, 'delete']);

    $app->group('/sprints-v2', function (RouteCollectorProxy $group) {
        $group->get('', [SprintsRepository::class, 'all']);
        $group->get('/{id}', [SprintsRepository::class, 'detail']);
        $group->post('', [SprintsRepository::class, 'create']);
        $group->put('/{id}', [SprintsRepository::class, 'update']);
        $group->delete('/{id}', [SprintsRepository::class, 'delete']);
    });
};