<?php

namespace App\Policies;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode visualizar o transfer
     */
    public function view(User $user, Transfer $transfer)
    {
        return $user->id === $transfer->user_id || $user->role === 'admin';
    }

    /**
     * Determina se o usuário pode criar transfers
     */
    public function create(User $user)
    {
        return true; // Todos os usuários autenticados podem criar
    }

    /**
     * Determina se o usuário pode atualizar o transfer
     */
    public function update(User $user, Transfer $transfer)
    {
        // Apenas admin pode atualizar (confirmar) transfers
        return $user->role === 'admin';
    }

    /**
     * Determina se o usuário pode deletar o transfer
     */
    public function delete(User $user, Transfer $transfer)
    {
        // Usuário pode cancelar apenas seus próprios transfers pendentes
        return $user->id === $transfer->user_id && $transfer->status === 'pendente';
    }

    /**
     * Determina se o usuário pode confirmar um transfer
     */
    public function confirm(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determina se o usuário pode acessar funções administrativas de transfer
     */
    public function admin(User $user)
    {
        return $user->role === 'admin';
    }
}