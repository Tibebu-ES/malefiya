<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\HTTP\Request;

class InitSystem extends Seeder
{
    public function run()
    {
        log_message('info','[InitSystem] Initializing System');
        //init shield library
        log_message('info','[InitSystem] Initializing Codeigniter Shield');
        command("shield:setup -f");


    }
}
