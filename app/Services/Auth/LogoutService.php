<?php

namespace App\Services\Auth;

use App\DTOs\Default\ResponseDTO;
use App\Services\DefaultService;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutService extends DefaultService
{
    protected string $mensagem_nao_encontrada = 'Erro ao desconectar usuário. Tente novamente.';

    public function __construct()
    {
        parent::__construct();
    }

    public function logout(): ResponseDTO
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            $resposta_db = true;
            $resposta = $this->montar_resposta($resposta_db, 'Usuário desconectado com sucesso.', $this->mensagem_nao_encontrada);
            $this->definir_dados_resposta($resposta);
        } catch (\Exception $e) {
            $this->lidar_com_excecao($e);
        } finally {
            return $this->dados_resposta([]);
        }
    }
}