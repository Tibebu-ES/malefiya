<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Calendar extends Entity
{
    private ?int $id = null;
    private string $name;
    private bool $active;
    private ?string $timezone;
    private ?string $locale;
    private ?string $about;

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
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $this->attributes['active'] =  $active;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active = $this->attributes['active'];
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

    /**
     * @param string $about
     */
    public function setAbout(string $about): void
    {
        $this->about = $this->attributes['about']  =$about;
    }

    /**
     * @return string
     */
    public function getAbout(): ?string
    {
        return $this->about = $this->attributes['about'];
    }

    public function getUserId (): int
    {
        return $this->attributes['user_id'];
    }

}
