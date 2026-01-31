<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    /**
     * Padroniza o retorno de redirecionamentos com mensagens de sessão
     * @param mixed $responseData
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function responseRedirect($responseData): RedirectResponse
    {
        return redirect()->route($responseData['route'])->with('message', $responseData['message']);
    }
}