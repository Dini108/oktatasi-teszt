<?php

namespace Model;

class ExtraPoints
{
    /**
     * @var string
     */
    private string $category;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $language;

    private const POINT_B2 = 28;
    private const POINT_C1 = 40;

    /**
     * @param $category
     * @param $type
     * @param $language
     */
    public function __construct($category,$type,$language)
    {
        $this->category = $category;
        $this->type = $type;
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
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
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return int
     */
    public function getResult(): int
    {
        if ($this->type === 'B2') {
            return self::POINT_B2;
        }

        if ($this->type === 'C1') {
            return self::POINT_C1;
        }

        return 0;
    }
}
