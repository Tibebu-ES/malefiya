<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Config;
use CodeIgniter\Database\Seeder;
use Config\Database;

class Malefiya extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Malefiya';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'malefiya:init';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Initialize system';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'malefiya:init [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        //
        $seeder = Database::seeder();
        $seeder->call('InitSystem');
    }
}
