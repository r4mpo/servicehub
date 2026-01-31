<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

abstract class Controller
{
    public function __construct()
    {
        //
    }

    /**
     * Padroniza o retorno dos componentes e propriedades com Inertia
     * @param mixed $responseData
     * @return \Inertia\Response
     */
    protected function responseInertia($responseData): Response
    {
        return Inertia::render($responseData['component'], $responseData['props']);
    }
}