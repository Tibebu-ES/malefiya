<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class UserInfo extends Entity
{
    private ?int $id = null;
    private ?string $firstName;
    private ?string $lastName;
    private ?string $timezone;
    private ?string $locale;

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
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName = $this->attributes['first_name'];
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $this->attributes['first_name'] = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName = $this->attributes['last_name'];
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $this->attributes['last_name'] = $lastName;
    }

    /**
     * @param string $timezone
     */
    public function setTimezone(string $timezone): void
    {
        $this->timezone = $this->attributes['timezone'] = $timezone;
    }

    /**
     * @return string
     */
    public function getTimezone(): ?string
    {
        return $this->timezone = $this->attributes['timezone'];
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $this->attributes['locale'] = $locale;
    }

    /**
     * @return string
     */
    public function getLocale(): ?string
    {
        return $this->locale = $this->attributes['locale'];
    }


}
