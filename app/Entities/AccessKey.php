<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class AccessKey extends Entity
{
    private ?int $id = null;
    private string $name;
    private string $key;
    private bool $active;
    private bool $hasPassword;
    private string $password;


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
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $this->attributes['key'] = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key = $this->attributes['key'];
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
     * @param bool $hasPassword
     */
    public function setHasPassword(bool $hasPassword): void
    {
        $this->hasPassword = $this->attributes['has_password'] = $hasPassword;
    }

    /**
     * @return bool
     */
    public function isHasPassword(): bool
    {
        return $this->hasPassword = $this->attributes['has_password'];
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password = $this->attributes['password'];
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
