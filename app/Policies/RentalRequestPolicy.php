<?php

namespace App\Policies;

use App\Models\RentalRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RentalRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode visualizar a solicitação
     */
    public function view(User $user, RentalRequest $rentalRequest)
    {
        return $user->id === $rentalRequest->user_id || $user->role === 'admin';
    }

    /**
     * Determina se o usuário pode criar solicitações
     */
    public function create(User $user)
    {
        return true; // Todos os usuários autenticados podem criar
    }

    /**
     * Determina se o usuário pode atualizar a solicitação
     */
    public function update(User $user, RentalRequest $rentalRequest)
    {
        // Apenas admin pode atualizar (confirmar) solicitações
        return $user->role === 'admin';
    }

    /**
     * Determina se o usuário pode deletar a solicitação
     */
    public function delete(User $user, RentalRequest $rentalRequest)
    {
        // Usuário pode cancelar apenas suas próprias solicitações pendentes
        return $user->id === $rentalRequest->user_id && $rentalRequest->status === 'pendente';
    }

    /**
     * Determina se o usuário pode confirmar uma solicitação
     */
    public function confirm(User $user)
    {
        return $user->role === 'admin';
    }
}