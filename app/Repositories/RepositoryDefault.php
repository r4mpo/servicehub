<?php

namespace App\Repositories;

class RepositoryDefault
{
    /**
     * Atualizar registro com base no modelo fornecido e dados
     * @param mixed $model
     * @param mixed $data
     * @return void
     */
    public function update($model, $data): void
    {
        $model->update($data);
    }

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