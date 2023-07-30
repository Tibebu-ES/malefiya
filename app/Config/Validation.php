<?php

namespace Config;

use App\Validation\MalefiyaRules;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        MalefiyaRules::class
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $registerUser = [
        'username' => [
            'label' => 'Auth.username',
            'rules' => 'min_length[3]|is_unique[users.username]',
        ],
        'email' => [
            'label' => 'Auth.email',
            'rules' => 'valid_email|is_unique[auth_identities.secret]',
        ],
        'password' => [
            'label' => 'Auth.password',
            'rules' => 'required|valid_password',
        ],
        'password_confirm' => [
            'label' => 'Auth.passwordConfirm',
            'rules' => 'required|matches[password]',
        ],
    ];
    /**
     * rules for creating/updating calendar
     * @var array
     */
    public $calendar = [
        'name' => [
            'label' => 'Validation.Calendar.name',
            'rules' => 'permit_empty|min_length[3]|max_length[255]|required_without[id]|is_unique_with_filter[malefiya_calendars.name,id,{id},user_id,{user_id}]'
        ],
        'active' => [
            'label' => 'Validation.Calendar.active',
            'rules' => 'permit_empty|is_bool'
        ],
        'timezone' => [
            'label' => 'Validation.Calendar.timezone',
            'rules'  => 'permit_empty|timezone'
        ],
        'locale' => [
            'label' => 'Validation.Calendar.locale',
            'rules'  => 'permit_empty|locale',
        ],
        'about' => [
            'label' => 'Validation.Calendar.about',
            'rules'  => 'permit_empty|max_length[255]',
        ]
    ];
    /**
     * rules for updating UserInfo
     * @var array
     */
    public $userInfo = [
        'first_name' => [
            'label' => 'Validation.UserInfo.first_name',
            'rules' => 'permit_empty|min_length[3]|max_length[50]'
        ],
        'last_name' => [
            'label' => 'Validation.UserInfo.last_name',
            'rules' => 'permit_empty|min_length[3]|max_length[50]'
        ],
        'timezone' => [
            'label' => 'Validation.UserInfo.timezone',
            'rules'  => 'permit_empty|timezone'
        ],
        'locale' => [
            'label' => 'Validation.UserInfo.locale',
            'rules'  => 'permit_empty|locale',
        ]
    ];
}
