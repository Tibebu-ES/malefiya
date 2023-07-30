<?php

namespace App\Controllers\Api;

use App\Entities\User;
use App\Models\UserInfoModel;
use CodeIgniter\RESTful\ResourceController;

class Registration extends ResourceController
{
    protected $helpers = ['setting'];

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function registerUser()
    {
        //
        $users = auth()->getProvider();
        $inputs = $this->request->getJSON(true);
        //validate @todo
        $validation = \Config\Services::validation();
        //validate inputs related to consumer user data
        $validation->reset();
        $validation->setRuleGroup('registerUser');
        if(!$validation->run($inputs)){
            return $this->failValidationErrors($validation->getErrors());
        }
        $user = new User([
            'username' =>  $inputs['username'],
            'email' => $inputs['email'],
            'password' => $inputs['password']
        ]);

        $users->save($user);

        // To get the complete user object with ID, we need to get from the database
        $user = $users->findById($users->getInsertID());

        // Add to default group
        $users->addToDefaultGroup($user);
        //@todo send email activation
        $users->activate($user);
        //add default userInfo
        $userInfo = new \App\Entities\UserInfo();
        if(!empty($inputs['first_name']))
            $userInfo->setFirstName($inputs['first_name']);
        if(!empty($inputs['last_name']))
            $userInfo->setLastName($inputs['last_name']);
        $locale = $inputs['locale'] ?? locale_get_default();
        $timezone = $inputs['timezone'] ?? date_default_timezone_get();
        $userInfo->setLocale($locale);
        $userInfo->setTimezone($timezone);
        (new UserInfoModel())->saveUserInfo($user->id,$userInfo);

        return $this->respond(['message' => 'Registered Successfully']);
    }




}
