<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CustomEventFieldValue extends Entity
{
    private ?int $id = null;
    private string $value;


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
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $this->attributes['value'] = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value = $this->attributes['value'];
    }

    /**
     * @param int $customEventFieldId
     */
    public function setCustomEventFieldId(int $customEventFieldId): void
    {
        $this->attributes['custom_event_field_id'] = $customEventFieldId;
    }

    /**
     * @return int
     */
    public function getCustomEventFieldId(): int
    {
        return $this->attributes['custom_event_field_id'];
    }

    /**
     * @param int $eventId
     */
    public function setEventId(int $eventId): void
    {
        $this->attributes['event_id'] = $eventId;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->attributes['event_id'];
    }

}
