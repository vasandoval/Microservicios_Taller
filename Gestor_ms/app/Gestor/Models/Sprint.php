<?php
namespace App\Gestor\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model {
    protected $table = 'sprints';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin'
    ];

    public function historias() {
        return $this->hasMany(Historia::class, 'sprint_id');
    }
}