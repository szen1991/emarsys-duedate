<?php

namespace DueDateCalculator\Model;


abstract class ExceptionCode
{
    const NOT_IN_WORKING_DAY = 1;

    const BEFORE_THE_WORKING_HOURS = 2;

    const AFTER_THE_WORKING_HOURS = 3;
}