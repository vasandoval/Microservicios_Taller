<?php
namespace App\Gestor\Controllers;

use App\Gestor\Models\Historia;
use Exception;

class Historia_Controller {

    function getHistorias(){
        $rows = Historia::all();
        return $rows->toJson();
    }
    
    function guardarHistoria($data){
        $historia = new Historia();
        $historia->titulo              = $data['titulo'];
        $historia->descripcion         = $data['descripcion'];
        $historia->responsable         = $data['responsable'];
        $historia->estado              = $data['estado'];
        $historia->puntos              = $data['puntos'];
        $historia->fecha_creacion      = $data['fecha_creacion'];
        $historia->fecha_finalizacion  = $data['fecha_finalizacion'];
        $historia->sprint_id           = $data['sprint_id'];

        $historia->save();
        return $historia->toJson();
    }

    function getHistoria($id){
        $historia = Historia::find($id);
        if(empty($historia)){
            throw new Exception("La historia $id no existe", 1);
        }
        return $historia;
    }

    function getHistoriaPorSprint($id_sprint){
        $historias = Historia::where('sprint_id', $id_sprint)->get();
        return $historias->toJson();
    }

    function modificarHistoria($id, $data){
        $historia = $this->getHistoria($id);
        $historia->titulo              = $data['titulo'];
        $historia->descripcion         = $data['descripcion'];
        $historia->responsable         = $data['responsable'];
        $historia->estado              = $data['estado'];
        $historia->puntos              = $data['puntos'];
        $historia->fecha_creacion      = $data['fecha_creacion'];
        $historia->fecha_finalizacion  = $data['fecha_finalizacion'];
        $historia->sprint_id           = $data['sprint_id'];
        $historia->save();
        return $historia;
    }

    function borrarHistoria($id){
        $historia = $this->getHistoria($id);
        $historia->delete();
    }
}