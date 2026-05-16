<?php
namespace App\Gestor\Controllers;

use App\Gestor\Models\Historia;
use Exception;

class HistoriaController {

    function getHistoria(){
        $rows = Historia::all();
        return $rows->toJson();
    }
    
    function guardarHistoria($data){
        $historia = new Historia();
        $historia->titulo = $data['titulo'];
        $historia->descripcion = $data['descripcion'];
        $historia->responsable = $data['responsable '];
        $historia->estado=$data ['estado'];
        $historia->puntos=$data ['puntos'];
        $historia->fecha_creacion=$data ['fecha_creacion'];
        $historia->fecha_finalizacion=$data ['fecha de finalizacion'];

        $historia->save();
        return $historia->toJson();
    }

    function getHistorias($id){
        $historia = Historia::find($id);
        if(empty($historia)){
            throw new Exception("La historia $id no existe", 1);
        }
        return $historia;
    }

    function modificarHistoria($id, $data){
        $historia = $this->getHistoria($id);
        $historia->titulo = $data['titulo'];
        $historia->descripcion = $data['descripcion'];
        $historia->responsable = $data['responsable '];
        $historia->estado=$data ['estado'];
        $historia->puntos=$data ['puntos'];
        $historia->fecha_creacion=$data ['fecha_creacion'];
        $historia->fecha_finalizacion=$data ['fecha de finalizacion'];
        $historia->save();
        return $historia;
    }

    function borrarHistoria($id){
        $historia = $this->getHistoria($id);
        $historia->delete();
    }
}
