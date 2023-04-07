<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

class Event extends Entity
{

    private ?int $id = null;
    private string $title;
    private bool $allDay;
    private Time $startDate;
    private Time $endDate;
    private string $rrule;
    private string $about;
    private string $where;
    private string $who;

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
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $this->attributes['title'] = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title = $this->attributes['title'];
    }

    /**
     * @param bool $allDay
     */
    public function setAllDay(bool $allDay): void
    {
        $this->allDay = $this->attributes['all_day'] = $allDay;
    }

    /**
     * @return bool
     */
    public function isAllDay(): bool
    {
        return $this->allDay = $this->attributes['all_day'];
    }

    /**
     * Get the value of startDate
     * @return string - 'Y-m-d H:i:s'
     */
    public function getStartDate(string $format = 'Y-m-d H:i:s'): string
    {
        // Convert to CodeIgniter\I18n\Time object
        $this->startDate = $this->attributes['start_date'] = $this->mutateDate($this->attributes['start_date']);
        return $this->startDate->format($format);
    }

    /**
     * Set the value of startDate
     * @param string $startDate - 'Y-m-d H:i:s'
     */
    public function setStartDate(string $startDate)
    {
        $this->startDate = $this->attributes['start_date'] = new Time($startDate);
    }

    /**
     * Get the value of endDate
     * @return string - 'Y-m-d H:i:s'
     */
    public function getEndDate(string $format = 'Y-m-d H:i:s'): string
    {
        // Convert to CodeIgniter\I18n\Time object
        $this->endDate = $this->attributes['end_date'] = $this->mutateDate($this->attributes['end_date']);
        return $this->endDate->format($format);
    }

    /**
     * Set the value of endDate
     * @param string $endDate - 'Y-m-d H:i:s'
     */
    public function setEndDate(string $endDate)
    {
        $this->endDate = $this->attributes['end_date'] = new Time($endDate);
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        $duration = $this->startDate->difference($this->endDate)->getSeconds();
        return $this->attributes['duration'] = $duration;
    }

    /**
     * @param string $rrule
     */
    public function setRrule(string $rrule): void
    {
        $this->rrule = $this->attributes['rrule'] = $rrule;
    }

    /**
     * @return string
     */
    public function getRrule(): string
    {
        return $this->rrule = $this->attributes['rrule'];
    }

    /**
     * @param string $about
     */
    public function setAbout(string $about): void
    {
        $this->about = $this->attributes['about'] = $about;
    }

    /**
     * @return string
     */
    public function getAbout(): string
    {
        return $this->about = $this->attributes['about'];
    }

    /**
     * @param string $where
     */
    public function setWhere(string $where): void
    {
        $this->where = $this->attributes['where'] = $where;
    }

    /**
     * @return string
     */
    public function getWhere(): string
    {
        return $this->where = $this->attributes['where'];
    }

    /**
     * @param string $who
     */
    public function setWho(string $who): void
    {
        $this->who = $this->attributes['who'] = $who;
    }

    /**
     * @return string
     */
    public function getWho(): string
    {
        return $this->who = $this->attributes['who'];
    }

}
