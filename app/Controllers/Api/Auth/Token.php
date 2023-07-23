<?php

namespace App\Controllers\Api\Auth;

use App\Entities\User;
use CodeIgniter\RESTful\ResourceController;

class Token extends ResourceController
{
    private ?User $user = null;
    public function __construct()
    {
        $username = $_SERVER['PHP_AUTH_USER'] ?? "";
        $password = $_SERVER['PHP_AUTH_PW'] ?? "";
        $credentials = [
            'username'    => $username,
            'password' => $password
        ];
        $loginAttempt = auth('session')->check($credentials);
        if(!$loginAttempt->isOK()){
            $response = \Config\Services::response();
            $responseData = [
                "error" => 401,
                "messages" => [
                    "error" => "Unauthorized"
                ]
            ];
            $response->setStatusCode(401);
            $response->setJSON($responseData);
            $response->send();
            exit();
        }else{
            $this->user = $loginAttempt->extraInfo();
        }
    }

    /**
     * generate token for the give user
     *
     * @return mixed
     */
    public function generateToken()
    {
        $response = [];
        if($this->user != null){
            $token = $this->user->generateAccessToken($this->user->getEmail());
            $response['token'] = $token->raw_token;
        }else{
           return $this->failNotFound("user not found");
        }
        return $this->respond($response);
    }

    /**
     * remove all access tokens - associated to a user
     * @return \CodeIgniter\HTTP\Response
     */
    public function revokeAllAccessTokens (){
        $response = [];
        if($this->user != null){
            $this->user->revokeAllAccessTokens();
            $response['message'] = "All access tokens are deleted";
        }else{
            return $this->failNotFound("user not found");
        }
        return $this->respond($response);
    }

    /**
     * remove a token  - associated to a user - given the raw_token
     * @return \CodeIgniter\HTTP\Response
     */
    public function revokeAccessToken (){
        $response = [];
        if($this->user != null){
            $token = $this->request->getJsonVar('token');
            if(!empty($token)){
                $this->user->revokeAccessToken($token);
            }else{
                return $this->failNotFound("token not found");
            }
            $response['message'] = "Access token is removed";
        }else{
            return $this->failNotFound("user not found");
        }
        return $this->respond($response);
    }
}
