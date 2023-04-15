<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CustomEventFieldOption extends Entity
{

    private ?int $id = null;
    private string $name;

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
}
