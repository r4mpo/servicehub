<?php

namespace App\Repositories;

class RepositoryDefault
{
    /**
     * Excluir registro com base no modelo fornecido
     * @param mixed $model
     * @return void
     */
    public function delete($model): void
    {
        $model->delete();
    }
}