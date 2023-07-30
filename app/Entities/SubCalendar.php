<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SubCalendar extends Entity
{
    private ?int $id = null;
    private string $name;
    private bool $active;
    private bool $overlap;
    private string $color;

    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'active' => 'boolean',
        'overlap' => 'boolean',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => '?timestamp'
    ];

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
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $this->attributes['active'] =  $active;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active = $this->attributes['active'];
    }

    /**
     * @param bool $overlap
     */
    public function setOverlap(bool $overlap): void
    {
        $this->overlap = $this->attributes['overlap'] = $overlap;
    }

    /**
     * @return bool
     */
    public function isOverlap(): bool
    {
        return $this->overlap = $this->attributes['overlap'];
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {
        $this->color = $this->attributes['color'] = $color;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color = $this->attributes['color'];
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
