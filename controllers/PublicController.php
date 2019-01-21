<?php

namespace App\Controllers;

use PsrHttpMessageServerRequestInterface as Request;
use PsrHttpMessageResponseInterface as Response;
use App\Models\Agenda;

class PublicController{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;   
    }

    public function interactuar($request, $response, $args){
        $datos = $request->getParsedBody();
        $oficina = $datos['datos'][0]["office"][0];
        $almuerzo = $datos['datos'][1]["lunch"][0];
        $cantidad = count($datos['datos']);
        $arreglo = array();
        for($x = 0; $x < $cantidad-2; $x++){
            array_push($arreglo, new Agenda());
            $arreglo[$x]->addOffice($oficina);
            $arreglo[$x]->addLunch($almuerzo);
            $arreglo[$x]->setName($datos['datos'][$x+2]['usuario']);
            foreach($datos['datos'][$x+2]['citas'] as $cita){
                $arreglo[$x]->addEvent($cita);
            }
        }

        $libres = array();
        for($x = 0; $x < 48; $x++){
            array_push($libres, array());
            foreach($arreglo as $agenda){
                if(count($agenda->horario[$x])==0){
                    array_push($libres[$x], $agenda->nombre);
                } else if(count($agenda->horario[$x])==1 && $agenda->horario[$x][0]=='oficina'){
                    array_push($libres[$x], 'trabajo '.$agenda->nombre);
                }
            }
        }

        $horario = array();
        for($x = 0; $x < 48; $x++){
            $exacta = intval(($x) / 2);
            $media = (intval(($x) % 2)==0?"00":"30");
            if(count($libres[$x])>3){
                array_push($libres[$x], $exacta.':'.$media);
                array_push($horario, $libres[$x]);
            }
        }

        echo json_encode($horario);
    }
}