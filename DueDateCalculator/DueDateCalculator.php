<?php

namespace DueDateCalculator;


use DueDateCalculator\Model\ExceptionCode;
use DueDateCalculator\Model\Problem;

class DueDateCalculator
{
    private $startWorking;
    private $workingHourPerDay;
    private $workingDayNumbers;

    public function __construct()
    {
        $this->startWorking = Settings::WORKING_START_HOUR;
        $this->workingHourPerDay = Settings::WORKING_HOUR_PER_DAY;
        $this->workingDayNumbers = Settings::WORKING_DAY_NUMBERS;
    }

    public function DueDateCalculate($insertDate, $leadTime)
    {
        $tmpLeadTime = explode(':', $leadTime);
        $leadTimeInHour = $tmpLeadTime[0];
        $leadTimeSecond = isset($tmpLeadTime[1]) ? $tmpLeadTime[1] : 0;

        $insertDate = strtotime($insertDate);
        $this->CheckDateIsValid($insertDate);

        $problem = new Problem();
        $problem->LeadTimeInSecond = ($leadTimeInHour * 60 * 60) + ($leadTimeSecond * 60);
        $problem->InsertDate = $insertDate;

        $this->Calculate($problem);

        return $problem;
    }

    /**
     * @param Problem $problem
     */
    private function Calculate(&$problem)
    {
        $tmpHourFromInsertDate = date("H", $problem->InsertDate) - $this->startWorking;
        $tmpMinuteFromInsertDate = date("i", $problem->InsertDate);
        $tmpSecondsFromInsertDate = ($tmpHourFromInsertDate * 60 * 60) + ($tmpMinuteFromInsertDate * 60);
        $tmpDueDate = $problem->InsertDate - $tmpSecondsFromInsertDate;

        $tmpLeadTimeInSeconds = $problem->LeadTimeInSecond + $tmpSecondsFromInsertDate;

        $lastWorkingDayNumber = max($this->workingDayNumbers);
        $firstWorkingDayName = $this->GetFirstWorkingDayName();

        $plusWorkingDays = floor($tmpLeadTimeInSeconds / ($this->workingHourPerDay * 60 * 60));
        $plusWorkingSeconds = ($tmpLeadTimeInSeconds % ($this->workingHourPerDay * 60 * 60));

        for($i=1;$i<=$plusWorkingDays;$i++)
        {
            $plus1Day = strtotime("+1 day", $tmpDueDate);
            if($lastWorkingDayNumber - date("N", $plus1Day) < 0)
            {
                $tmpDueDate = strtotime("next {$firstWorkingDayName}", $tmpDueDate);
                $tmpDueDate = strtotime("+{$this->startWorking} hours", $tmpDueDate);
            } else {
                $tmpDueDate = $plus1Day;
            }
        }

        $tmpDueDate += $plusWorkingSeconds;
        $problem->DueDate = $tmpDueDate;
    }

    private function CheckDateIsValid($date)
    {
        $this->CheckDateIsWorkingDay($date);
        $this->CheckDateIsBeforeWorkingHour($date);
        $this->CheckDateIsAfterWorkingHour($date);
    }

    private function CheckDateIsWorkingDay($date)
    {
        if(!in_array(date("N", $date), $this->workingDayNumbers))
        {
            throw new \Exception(date("D", $date) . " is not working day", ExceptionCode::NOT_IN_WORKING_DAY);
        }
    }

    private function CheckDateIsBeforeWorkingHour($date)
    {
        if(date("H", $date) < $this->startWorking)
        {
            throw new \Exception(date("H:i", $date) . " is before the working hour", ExceptionCode::BEFORE_THE_WORKING_HOURS);
        }
    }

    private function CheckDateIsAfterWorkingHour($date)
    {
        if((int)date("Hi", $date) > (int)(($this->startWorking + $this->workingHourPerDay)."00"))
        {
            throw new \Exception(date("H:i", $date) . " is after the working hour", ExceptionCode::AFTER_THE_WORKING_HOURS);
        }
    }

    private function GetFirstWorkingDayName()
    {
        $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $firstWorkingDayNumber = min($this->workingDayNumbers);

        return $dayNames[$firstWorkingDayNumber -1];
    }
}