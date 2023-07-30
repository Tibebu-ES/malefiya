<?php

namespace App\Controllers\Api;

use App\Models\CalendarModel;
use App\Models\SubCalendarModel;
use CodeIgniter\RESTful\ResourceController;

class SubCalendar extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function getSubCalendars($calendarId = null)
    {
        //
        $calendar = (new CalendarModel())->getCalendar($calendarId);
        if(empty($calendar) || $calendar->getUserId() != auth()->id()){
            return $this->failNotFound('calendar not found');
        }
        $response = [
            'sub_calendars' => (new SubCalendarModel())->getSubCalendars($calendar->getId()),
        ];
        return $this->respond($response);
    }
    /**
     *
     *
     * @return mixed
     */
    public function getSubCalendar($calendarId = null,$subCalendarId = null)
    {
        //
        $calendar = (new CalendarModel())->getCalendar($calendarId);
        if(empty($calendar) || $calendar->getUserId() != auth()->id()){
            return $this->failNotFound('calendar not found');
        }
        $subCalendar = (new SubCalendarModel())->getSubCalendar($subCalendarId);
        if(empty($subCalendar) || $subCalendar->getCalendarId() != $calendarId){
            return $this->failNotFound('subcalendar not found');
        }
        $response = [
            'sub_calendar' => $subCalendar,

        ];
        return $this->respond($response);
    }

    /**
     *
     * @return mixed
     */
    public function createSubCalendar($calendarId = null)
    {
        //
        try{
            $calendar = (new CalendarModel())->getCalendar($calendarId);
            if(empty($calendar) || $calendar->getUserId() != auth()->id()){
                return $this->failNotFound('calendar not found');
            }
            $subCalendarModel = new SubCalendarModel();
            $inputs = $this->request->getJSON(true);
            $inputs['calendar_id'] = $calendarId;
            //validate inputs
            $validation = \Config\Services::validation();
            $validation->reset();
            $validation->setRuleGroup('subCalendar');
            if(!$validation->run($inputs)){
                return $this->failValidationErrors($validation->getErrors());
            }
            $subCalendar = new \App\Entities\SubCalendar();
            $subCalendar->fill($inputs);
            $subCalendar = $subCalendarModel->addSubCalendar($calendarId,$subCalendar);
            $response = [
                'sub_calendar' => $subCalendar,
            ];
            return $this->respondCreated($response);
        }catch (\Exception $e){
            log_message('error', __METHOD__.' {exception}', ['exception' => $e]);
            return $this->failServerError();
        }

    }

    /**
     *
     * @return mixed
     */
    public function updateSubCalendar($calendarId = null,$subCalendarId = null)
    {
        //
        try{
            $calendar = (new CalendarModel())->getCalendar($calendarId);
            if(empty($calendar) || $calendar->getUserId() != auth()->id()){
                return $this->failNotFound('calendar not found');
            }
            $subCalendarModel = new SubCalendarModel();
            $subCalendar = $subCalendarModel->getSubCalendar($subCalendarId);
            if(empty($subCalendar) || $subCalendar->getCalendarId() != $calendarId){
                return $this->failNotFound('subcalendar not found');
            }
            $inputs = $this->request->getJSON(true);
            $inputs['calendar_id'] = $calendar->getId();
            $inputs['id'] = $subCalendar->getId();
            //validate inputs
            $validation = \Config\Services::validation();
            $validation->reset();
            $validation->setRuleGroup('subCalendar');
            if(!$validation->run($inputs)){
                return $this->failValidationErrors($validation->getErrors());
            }
            $hasChangedData = false;
            $allowedFields = $subCalendarModel->getAllowedFields();
            $updated = [];
            foreach ($inputs as $key => $value){
                if(in_array($key,$allowedFields) && $subCalendar->__get($key) !== $value){
                    $subCalendar->__set($key,$value);
                    $hasChangedData = true;
                }
            }
            if($hasChangedData){
                if($subCalendarModel->updateSubCalendar($subCalendarId,$subCalendar)){
                    $updated = $subCalendar->toRawArray(true);
                    $subCalendar = $subCalendarModel->getSubCalendar($subCalendarId);
                }else{
                    return $this->failServerError();
                }
            }
            $response = [
                'sub_calendar' => $subCalendar,
                "updated" => $updated
            ];
            return $this->respondUpdated($response);
        }catch (\Exception $e){
            log_message('error', __METHOD__.' {exception}', ['exception' => $e]);
            return $this->failServerError();
        }

    }

    /**
     *
     * @return mixed
     */
    public function deleteSubCalendar($calendarId = null,$subCalendarId = null)
    {
        //
        try{
            $calendar = (new CalendarModel())->getCalendar($calendarId);
            if(empty($calendar) || $calendar->getUserId() != auth()->id()){
                return $this->failNotFound('calendar not found');
            }
            $subCalendarModel = new SubCalendarModel();
            $subCalendar = $subCalendarModel->getSubCalendar($subCalendarId);
            if(empty($subCalendar) || $subCalendar->getCalendarId() != $calendarId){
                return $this->failNotFound('subcalendar not found');
            }
            if(!$subCalendarModel->deleteSubCalendar($subCalendarId)){
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
