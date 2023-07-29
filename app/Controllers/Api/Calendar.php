<?php

namespace App\Controllers\Api;

use App\Models\CalendarModel;
use CodeIgniter\RESTful\ResourceController;

class Calendar extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
        $response = [
            'calendars' => (new CalendarModel())->getCalendars(auth()->id())
        ];
        return $this->respond($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
        $calendar = null;
        $calendars = (new CalendarModel())->getCalendars(auth()->id());
        foreach ($calendars as $cal){
            if($cal->getId() == $id){
                $calendar = $cal;
                break;
            }
        }
        if($calendar == null){
            return $this->failNotFound();
        }
        $response = [
            'calendar' => $calendar
        ];
        return $this->respond($response);

    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
        try{
            $inputs = $this->request->getJSON(true);
            $inputs['user_id'] = auth()->id();
            //validate inputs
            $validation = \Config\Services::validation();
            $validation->reset();
            $validation->setRuleGroup('calendar');
            if(!$validation->run($inputs)){
                return $this->failValidationErrors($validation->getErrors());
            }
            $calendar = new \App\Entities\Calendar($inputs);
            $calendar = (new CalendarModel())->addCalendar(auth()->id(),$calendar);
            $response = [
                "calendar" => $calendar,
            ];

            return $this->respondCreated($response);
        }catch (\Exception $e){
            log_message('error', __METHOD__.' {exception}', ['exception' => $e]);
            return $this->failServerError();
        }


    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
        try{
            $inputs = $this->request->getJSON(true);
            $calendarModel = new CalendarModel();
            $calendar = $calendarModel->getCalendar($id);
            if(empty($calendar) || $calendar->getUserId() != auth()->id()){
                return $this->failNotFound();
            }
            $inputs['user_id'] = auth()->id();
            $inputs['id'] = $calendar->getId();
            //validate inputs
            $validation = \Config\Services::validation();
            $validation->reset();
            $validation->setRuleGroup('calendar');
            if(!$validation->run($inputs)){
                return $this->failValidationErrors($validation->getErrors());
            }
            $hasChangedData = false;
            $allowedFields = $calendarModel->getAllowedFields();
            $updated = [];
            foreach ($inputs as $key => $value){
                if(in_array($key,$allowedFields) && $calendar->__get($key) !== $value){
                    $calendar->__set($key,$value);
                    $hasChangedData = true;
                }
            }
            if($hasChangedData){
                if($calendarModel->updateCalendar($id,$calendar)){
                    $updated = $calendar->toRawArray(true);
                    $calendar = $calendarModel->getCalendar($id);
                }else{
                    return $this->failServerError();
                }
            }
            $response = [
                "calendar" => $calendar,
                "updated" => $updated
            ];

            return $this->respondUpdated($response);
        }catch (\Exception $e){
            log_message('error', __METHOD__.' {exception}', ['exception' => $e]);
            return $this->failServerError();
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        try{
            $calendarModel = new CalendarModel();
            $calendar = $calendarModel->getCalendar($id);
            if(empty($calendar) || $calendar->getUserId() != auth()->id()){
                return $this->failNotFound();
            }
            if(!$calendarModel->deleteCalendar($id)){
                return $this->failServerError();
            }
            $response = [
                "message" => "Deleted Successfully",
            ];
            return $this->respondDeleted($response);
        }catch (\Exception $e){
            log_message('error', __METHOD__.' {exception}', ['exception' => $e]);
            return $this->failServerError();
        }
    }
}
