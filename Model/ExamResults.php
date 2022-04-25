<?php

namespace Model;

class ExamResults
{
    public string $examName;

    private string $level;

    private string $result;

    public function __construct(string $examName,string $level,string $result)
    {
        $this->examName = $examName;
        $this->level = $level;
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getExamName(): string
    {
        return $this->examName;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getResult(): int
    {
        return (int)substr($this->result, 0, -1);
    }
}
