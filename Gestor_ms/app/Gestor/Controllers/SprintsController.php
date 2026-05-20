<?php
namespace App\Controllers;

use App\Models\Sprint;
use Exception;

class SprintsController {

    function getSprints() {
        $rows = Sprint::all();
        return $rows->toJson();
    }

    function guardarSprint($data) {
        $sprint = new Sprint();
        $sprint->nombre = $data['nombre'];
        $sprint->fecha_inicio = $data['fecha_inicio'];
        $sprint->fecha_fin = $data['fecha_fin'];
        $sprint->save();
        return $sprint->toJson();
    }

    function getSprint($id) {
        $sprint = Sprint::find($id);
        if (empty($sprint)) {
            throw new Exception("El sprint $id no existe", 1);
        }
        return $sprint;
    }

    function modificarSprint($id, $data) {
        $sprint = $this->getSprint($id);
        $sprint->nombre = $data['nombre'];
        $sprint->fecha_inicio = $data['fecha_inicio'];
        $sprint->fecha_fin = $data['fecha_fin'];
        $sprint->save();
        return $sprint;
    }

    function borrarSprint($id) {
        $sprint = $this->getSprint($id);
        $sprint->delete();
    }
}