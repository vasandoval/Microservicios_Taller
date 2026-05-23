<?php

namespace App\Gestor\Controllers;

use App\Gestor\Models\Historia;
use App\Gestor\Models\Sprint;
use App\Gestor\Models\Informe;
use Exception;

class Informe_Controller{

    function generarInforme(int $id_sprint): Informe{
        $sprint = Sprint::find($id_sprint );
        if(empty($sprint)){
            throw new Exception("El sprint con ID $id_sprint no existe", 1);
        }

        $historias = Historia::where('sprint_id', $id_sprint );

        $informe = new Informe();
        $informe->sprint_id = $sprint->id;
        $informe->sprint_nombre = $sprint->nombre;
        $historias = Historia::where('sprint_id', $id_sprint)->get();
        $informe->total_historias = $historias->count();


        $mapaResponsables = [];

        foreach($historias as $historia){
            $resp = $historia->responsable;

            if(!isset($mapaResponsables[$resp])){
                $mapaResponsables[$resp] = [
                    'responsable' => $resp,
                    'finalizadas' => 0,
                    'pendientes' => 0,
                    'con_impedimento' => 0,
                ];
            }

            switch ($historia->estado){
                case 'finalizadas':
                    $informe->finalizadas++;
                    $mapaResponsables[$resp]['finalizadas']++;
                    break;

                case 'impedimento':
                    $informe->con_impedimento++;
                    $mapaResponsables[$resp]['con_impedimento']++;
                    break;

                case 'nueva':
                case 'activa':
                    $informe->pendientes++;
                    $mapaResponsables[$resp]['pendientes']++;
                    break;
            }
        }
        
        $informe->por_responsable = array_values($mapaResponsables);
        return $informe;
    }

    function generarTodosInformes(): array{
        $sprints = Sprint::all();
        $informes = [];

        foreach ($sprints as $sprint) {
            $informes[] = $this->generarInforme($sprint->id);
        }

        return $informes;
    }

}