<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CustomEventField extends Entity
{
    private ?int $id = null;
    private string $name;
    private string $type;


    //custom field types
    public const TYPE_TEXT = 'text';
    public const TYPE_SINGLE_SELECT = 's_select';
    public const TYPE_MULTIPLE_SELECT = 'm_select';


    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $this->attributes['id'] = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        if(isset($this->attributes['id'])){
            $this->id = $this->attributes['id'];
        }
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $this->attributes['name'] =  $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name = $this->attributes['name'];
    }

    /**
     * @param string $type - CustomEventField::TYPE_TEXT|CustomEventField::TYPE_SINGLE_SELECT|CustomEventField::TYPE_MULTIPLE_SELECT
     */
    public function setType(string $type): void
    {
        if(in_array($type,[CustomEventField::TYPE_TEXT,CustomEventField::TYPE_SINGLE_SELECT,CustomEventField::TYPE_MULTIPLE_SELECT])){
            $this->type = $this->attributes['type'] = $type;
        }else{
            throw new \Exception("Invalid custom field type");
        }

    }

    /**
     * @return string - CustomEventField::TYPE_TEXT|CustomEventField::TYPE_SINGLE_SELECT|CustomEventField::TYPE_MULTIPLE_SELECT
     */
    public function getType(): string
    {
        return $this->type = $this->attributes['type'];
    }

    /**
     * @return int
     */
    public function getCalendarId(): int
    {
        return $this->attributes['calendar_id'];
    }

    /**
     * @param int $calendarId
     */
    public function setCalendarId(int $calendarId): void
    {
        $this->attributes['calendar_id'] = $calendarId;
    }
}
