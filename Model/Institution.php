<?php

namespace Model;

class Institution
{
    /**
     * @var string
     */
    public string $university;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $class;

    /**
     * @param $university
     * @param $type
     * @param $class
     */
    public function __construct($university, $type, $class)
    {
        $this->university = $university;
        $this->type = $type;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getUniversity(): string
    {
        return $this->university;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getInstitutionCode(): string
    {
        return $this->university . '-' . $this->type;
    }
}
