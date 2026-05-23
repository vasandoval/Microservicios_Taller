<?php

use Slim\App;
use App\Gestor\Presentation\Repositories\TestRepository;
use App\Gestor\Presentation\Repositories\Sprints_Repository;
use App\Gestor\Presentation\Repositories\Gestor_Repository;
use App\Gestor\Presentation\Repositories\Informes_Repository;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', [TestRepository::class, 'default']);
    $app->post('/hola', [TestRepository::class, 'hola']);

    $app->post('/sprint', [Sprints_Repository::class, 'create']);
    $app->get('/sprints', [Sprints_Repository::class, 'all']);
    $app->get('/sprint/{id}', [Sprints_Repository::class, 'detail']);
    $app->put('/sprint/{id}', [Sprints_Repository::class, 'update']);
    $app->delete('/sprint/{id}', [Sprints_Repository::class, 'delete']);

    $app->post('/historia', [Gestor_Repository::class, 'create']);
    $app->get('/historias', [Gestor_Repository::class, 'all']);
    $app->get('/historia/{id}', [Gestor_Repository::class, 'detail']);
    $app->put('/historia/{id}', [Gestor_Repository::class, 'update']);
    $app->delete('/historia/{id}', [Gestor_Repository::class, 'delete']);
    $app->get('/historias/sprint/{sprint_id}', [Gestor_Repository::class, 'bySprint']);

    $app->get('/informes', [Informes_Repository::class, 'all']);
    $app->get('/informe/sprint/{sprint_id}', [Informes_Repository::class, 'bySprint']);

        // AGRUPACIONES v2

    $app->group('/sprints-v2', function (RouteCollectorProxy $group) {
        $group->get('',        [Sprints_Repository::class, 'all']);
        $group->get('/{id}',   [Sprints_Repository::class, 'detail']);
        $group->post('',       [Sprints_Repository::class, 'create']);
        $group->put('/{id}',   [Sprints_Repository::class, 'update']);
        $group->delete('/{id}',[Sprints_Repository::class, 'delete']);
    });

    /**
     * Versión v2 de historias agrupada bajo /historias-v2
     */
    $app->group('/historias-v2', function (RouteCollectorProxy $group) {
        $group->get('',                    [Gestor_Repository::class, 'all']);
        $group->get('/{id}',               [Gestor_Repository::class, 'detail']);
        $group->get('/sprint/{sprint_id}', [Gestor_Repository::class, 'bySprint']);
        $group->post('',                   [Gestor_Repository::class, 'create']);
        $group->put('/{id}',               [Gestor_Repository::class, 'update']);
        $group->delete('/{id}',            [Gestor_Repository::class, 'delete']);
    });
};