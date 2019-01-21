<?php

namespace App\Models;

class Agenda{
    public $horario;
    public $nombre;

    public function __construct(){
        $this->nombre = "";
        $this->horario = array();
        for($x = 0; $x < 48; $x++){
            $this->horario[$x] = array();
        }
    }

    public function setName($nombre){
        $this->nombre = $nombre;
    }

    public function addEvent($evento){
        if(count($evento)>0){
            $hora = $evento['hora'];
            $minutos = $evento['min'];
            $meridiano = $evento['meridiano'];
            if($meridiano == 'AM'){
                $hora = $hora * 2;
                if($minutos != "0") $hora++;
            }
            if($meridiano == 'PM'){
                if($hora == 12)
                    $hora = $hora * 2;
                else
                    $hora = 24 + $hora * 2;
                if($minutos != "0") $hora++;
            }
            array_push($this->horario[$hora-1], "ocupado");
        } else 
            return;
    }

    public function addOffice($office){
        $inicio = $office['inicio'];
        $hora_inicio = ($inicio['hora']*2 + intval($inicio['min']/30)) - 1;
        $fin = $office['fin'];
        $hora_fin = (24 + $fin['hora']*2 + intval($fin['min']/30)) - 1;
        for($x = $hora_inicio; $x < $hora_fin; $x++){
            array_push($this->horario[$x], "oficina");
        }
    }

    public function addLunch($lunch){
        $inicio = $lunch['inicio'];
        $hora_inicio = ($inicio['hora']*2 + intval($inicio['min']/30)) - 1;
        $fin = $lunch['fin'];
        $hora_fin = (24 + $fin['hora']*2 + intval($fin['min']/30)) - 1;
        for($x = $hora_inicio; $x < $hora_fin; $x++){
            array_push($this->horario[$x], "almuerzo");
        }
    }

    public function getAll(){
        return array($this->nombre, $this->horario);
    }
}