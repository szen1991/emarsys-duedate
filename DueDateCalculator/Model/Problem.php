<?php

namespace DueDateCalculator\Model;


class Problem
{
    public $InsertDate;

    public $DueDate;

    public $LeadTimeInSecond;

    public function GetDueDate()
    {
        return date("Y.m.d H:i", $this->DueDate);
    }

    public function GetInsertDate()
    {
        return date("Y.m.d H:i", $this->InsertDate);
    }
}