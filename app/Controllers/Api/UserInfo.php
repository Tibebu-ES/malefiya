<?php

namespace App\Controllers\Api;

use App\Models\CalendarModel;
use App\Models\UserInfoModel;
use CodeIgniter\RESTful\ResourceController;

class UserInfo extends ResourceController
{


    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function showUserInfo()
    {
        //
        $userInfoData = (new UserInfoModel())->getUserInfo(auth()->id())->toArray();
        //include email
        $userInfoData['email'] = auth()->getUser()->getEmail();
        $response = [
            'user_info' => $userInfoData
        ];
        return $this->respond($response);

    }



    /**
     *
     *
     * @return mixed
     */
    public function saveUserInfo()
    {
        //
        try{
            $inputs = $this->request->getJSON(true);

            $inputs['user_id'] = auth()->id();
            //validate inputs
            $validation = \Config\Services::validation();
            $validation->reset();
            $validation->setRuleGroup('userInfo');
            if(!$validation->run($inputs)){
                return $this->failValidationErrors($validation->getErrors());
            }
            $userInfoModel = new UserInfoModel();
            $hasChanged = false;
            $firstName = $inputs['first_name'] ?? null;
            $lastName = $inputs['last_name'] ?? null;
            $locale = $inputs['locale'] ?? null;
            $timezone = $inputs['timezone'] ?? null;
            $userInfo = $userInfoModel->getUserInfo(auth()->id());
            if(empty($userInfo)){
                //on creating the first time
                $userInfo = new \App\Entities\UserInfo();
                $userInfo->setFirstName($firstName);
                $userInfo->setLastName($lastName);
                $userInfo->setLocale($locale);
                $userInfo->setTimezone($timezone);
                $hasChanged = true;
            }else{
                //on updating
                if($userInfo->getFirstName() != $firstName){
                    $userInfo->setFirstName($firstName);
                    $hasChanged = true;
                }
                if($userInfo->getLastName() != $lastName){
                    $userInfo->setLastName($lastName);
                    $hasChanged = true;
                }
                if($userInfo->getLocale() != $locale){
                    $userInfo->setLocale($locale);
                    $hasChanged = true;
                }
                if($userInfo->getTimezone() != $timezone){
                    $userInfo->setTimezone($timezone);
                    $hasChanged = true;
                }
            }

            if($hasChanged){
                if($userInfoModel->saveUserInfo(auth()->id(),$userInfo)){
                    $userInfo = $userInfoModel->getUserInfo(auth()->id());
                }else{
                    return $this->failServerError('error saving userinfo');
                }
            }
            $userInfoData = $userInfo->toArray();
            //include email
            $userInfoData['email'] = auth()->getUser()->getEmail();

            $response = [
                "user_info" => $userInfoData
            ];

            return $this->respondUpdated($response);
        }catch (\Exception $e){
            log_message('error', __METHOD__.' {exception}', ['exception' => $e]);
            return $this->failServerError($e->getMessage());
        }
    }


}
