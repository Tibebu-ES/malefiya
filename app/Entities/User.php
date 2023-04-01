<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends \CodeIgniter\Shield\Entities\User
{
    private ?UserInfo $userInfo = null;
}
