<?php

// override core en language system validation or define your own en language validation message
return [
    'valid_password' => "The password does not meet the requirements. Please set a password of a minimum 8 characters, at least one letter, and one number.",
    'required_boolean'  => 'The {field} field is required.',
    'required_boolean_without'  => 'The {field} field is required when {param} is not present.',
    'is_bool'  => 'The {field} field must be boolean.',
    'required' => "The '{field}' field is required.",
    'unique' => 'The {field} field must be unique.',
    'valid_date' => "Supplied value ({value}) for {field} must be in {param} format.",
    'valid_email' => "The {field} field must contain a valid email address.",
    'is_unique_with_filter' => 'The {field} field must be unique.',
    'locale' => 'Supplied value ({value}) for {field} is not valid.',
    'Calendar' => [
        'name' => 'Calendar Name',
        'active' => 'Active',
        'about' => 'About',
        'locale' => 'Locale',
        'timezone' => 'Timezone'
    ],
    'UserInfo' => [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'locale' => 'Locale',
        'timezone' => 'Timezone'
    ]
];
