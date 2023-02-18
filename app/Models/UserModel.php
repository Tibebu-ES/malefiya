<?php

namespace App\Models;


use App\Entities\User;

class UserModel extends \CodeIgniter\Shield\Models\UserModel
{
    protected $returnType       = User::class;

    /**
     * Called during initialization.
     *
     */
    protected function initialize()
    {
        $this->afterInsert[] = 'saveUserInfo';
        $this->afterUpdate[] = 'saveUserInfo';
    }

    /**
     * Save User Info
     *
     * Model event callback called by `afterInsert` and `afterUpdate`.
     */
    protected function saveUserInfo(array $data): array
    {

        log_message('debug','[UserModel][saveUserInfo] -- saveUserInfo = '.json_encode($data));
        // Insert


        return $data;
    }

}
