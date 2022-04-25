<?php

namespace Classes;

use Model\Institution;

class Requirements
{
    /**
     * @var array
     */
    private array $requirements;

    /**
     * @param $file
     */
    public function __construct($file){
        //read requirements from file
        $this->requirements = $this->readRequirements($file);
    }

    /**
     * @param \Model\Institution $institution
     * @param boolean $selected
     * @return string|array
     */
    public function getMandatoryRequirements(Institution $institution, bool $selected = false)
    {
        foreach ($this->requirements as $key => $requirement) {
            if ($requirement['institution_code'] === $institution->getInstitutionCode() &&
                $requirement['class'] === $institution->getClass()) {
                if ($selected) {
                    return $requirement['mandatory_selected_subject'];
                }
                return $requirement['mandatory_subject'];
            }
        }

        return [];
    }

    /**
     * @param $file
     * @return array
     */
    private function readRequirements($file): array
    {
        $requirements = [];
        //read csv file to an array of requirements separated by comma
        if (($handle = fopen($file, "r")) !== false) {
            if (($data = fgetcsv($handle, 1000, ";")) !== false) {
                $keys = $data;
            }
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                foreach ($data as $key => $value) {
                    if (strpos($value, ',') !== false) {
                        $data[$key] = explode(',', $value);
                    } else {
                        $data[$key] = $value;
                    }
                }
                //If line contains comma create array
                $requirements[] = array_combine($keys, $data);
            }
            fclose($handle);
        }

        return $requirements;
    }
}
