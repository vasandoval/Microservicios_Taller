<?php
namespace App\Gestor\Models;

class Informe{
    public int $sprint_id;
    public string $sprint_nombre;
    public int $total_historias;
    public int $finalizadas;
    public int $pendientes;
    public int $con_impedimento;
    public array $por_responsable;

    public function __construct() {
        $this->sprint_id       = 0;
        $this->sprint_nombre   = '';
        $this->total_historias = 0;
        $this->finalizadas     = 0;
        $this->pendientes      = 0;
        $this->con_impedimento = 0;
        $this->por_responsable = [];
    }

    public function toJson(): string {
        return json_encode([
            'sprint_id'        => $this->sprint_id,
            'sprint_nombre'    => $this->sprint_nombre,
            'total_historias'  => $this->total_historias,
            'finalizadas'      => $this->finalizadas,
            'pendientes'       => $this->pendientes,
            'con_impedimento'  => $this->con_impedimento,
            'por_responsable'  => $this->por_responsable,
        ]);
    }
}