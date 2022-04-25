<?php

namespace Classes;

use Model\ExamResults;
use Model\ExtraPoints;
use Model\Institution;

class ExamPointCalculator
{
    /**
     * @var ExamResults[]
     */
    public array $examResults;

    /**
     * @var \Classes\Requirements
     */
    private Requirements $requirements;

    /**
     * @var array
     */
    private array $extraPointsResults;

    /**
     * @var \Model\Institution
     */
    private Institution $institution;

    /**
     * @param $examResults
     * @param \Classes\Requirements $requirements
     * @throws \Exception
     */
    public function __construct($examResults,Requirements $requirements)
    {
        $this->requirements = $requirements;
        $this->examResults = $this->setExamResults($examResults);
        $this->extraPointsResults = $this->setExtraPointsResults($examResults);
        $this->institution = $this->setInstitution($examResults);

        $this->checkExamResults();
    }

    /**
     * @param $examResults
     * @return array
     */
    public function setExtraPointsResults($examResults): array
    {
        $results = [];
        foreach ($examResults['tobbletpontok'] as $examResult) {
            $results[]= new ExtraPoints($examResult['kategoria'], $examResult['tipus'],$examResult['nyelv']);
        }
        return $results;
    }

    /**
     * @param $examResults
     * @return array
     */
    public function setExamResults($examResults): array
    {
        $results = [];
        foreach ($examResults['erettsegi-eredmenyek'] as $examResult) {
            $results[]= new ExamResults($examResult['nev'], $examResult['tipus'],$examResult['eredmeny']);
        }
        return $results;
    }

    /**
     * @param $examResults
     * @return \Model\Institution
     */
    public function setInstitution($examResults): Institution
    {
        $institution = $examResults['valasztott-szak'];
        return new Institution($institution['egyetem'], $institution['kar'],$institution['szak']);
    }

    /**
     * @return int|void
     */
    public function getRelevantMandatoryResults(){
        $requirements = $this->requirements->getMandatoryRequirements($this->institution);

        foreach ($this->examResults as $result){
            if($result->getExamName() === $requirements){
                return $result->getResult();
            }
        }
    }

    /**
     * @return int
     */
    public function getRelevantSelectedResults(): int
    {
        $relevantResults = [];

        $selectedClasses = $this->requirements->getMandatoryRequirements($this->institution,true);

        foreach ($this->examResults as $result){
            foreach ($selectedClasses as $selectedClass){
                if($result->getExamName() === $selectedClass){
                    $relevantResults[] = $result;
                }
            }
        }
        //If there is multiple relevant results, return the better one
        usort($relevantResults, function ($a, $b) {
            return $b->getResult() <=> $a->getResult();
        });

        return $relevantResults[0]->getResult();
    }

    /**
     * @return int
     */
    private function getExtraPointResults(): int
    {
        $relevantResults = 0;

        //Get extra points
        foreach ($this->extraPointsResults as $result){
            $relevantResults += $result->getResult();
        }

        //If there is high level in exam results, add 50 point
        foreach ($this->examResults as $result){
            if($result->getLevel() === 'emelt'){
                $relevantResults += 50;
            }
        }

        //If results are better than 100 return max 100
        if ($relevantResults > 100){
            $relevantResults = 100;
        }

        //If there is no relevant result, return false
        return $relevantResults;
    }

    /**
     * @return float|int
     */
    public function getResult()
    {
        $result = ($this->getRelevantMandatoryResults() + $this->getRelevantSelectedResults()) * 2;
        $result += $this->getExtraPointResults();

        return $result;
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function checkExamResults(): void
    {
        $mandatoryClasses = ['magyar nyelv és irodalom','történelem','matematika'];

        foreach ($this->examResults as $examResult){
            if ($examResult->getResult() < 20){
                throw new \Exception('Invalid exam result');
            }
        }

        //Exam results object to array
        $examResultsArray = array_map(function($val){return $val->examName;}, $this->examResults);

        foreach ($mandatoryClasses as $mandatoryClass){
            if (! in_array($mandatoryClass, $examResultsArray, true)){
                throw new \Exception('No result for mandatory class');
            }
        }

    }
}
