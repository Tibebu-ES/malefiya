<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SubCalendarPermission extends Entity
{
    private ?int $id = null;
    private string $accessType;
    const ACCESS_TYPE_MODIFY = 'modify';
    const ACCESS_TYPE_READ_ONLY = 'read_only';

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
     * @param string $accessType
     */
    public function setAccessType(string $accessType): void
    {
        $this->accessType = $this->attributes['access_type'] = $accessType;
    }

    /**
     * @return string
     */
    public function getAccessType(): string
    {
        return $this->accessType = $this->attributes['access_type'];
    }

    /**
     * @param int $subCalendarId
     */
    public function setSubCalendarId(int $subCalendarId): void
    {
        $this->attributes['sub_calendar_id'] = $subCalendarId;
    }

    /**
     * @return int
     */
    public function getSubCalendarId(): int
    {
        return $this->attributes['sub_calendar_id'];
    }

    /**
     * @return int
     */
    public function getAccessKeyId(): int
    {
        return $this->attributes['access_key_id'];
    }

    /**
     * @param int $accessKeyId
     */
    public function setAccessKeyId(int $accessKeyId): void
    {
        $this->attributes['access_key_id'] = $accessKeyId;
    }


}
