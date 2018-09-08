<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('DueDateCalculator/Settings.php');
require_once('DueDateCalculator/Model/ExceptionCode.php');
require_once('DueDateCalculator/Model/Problem.php');
require_once('DueDateCalculator/DueDateCalculator.php');
require_once('DueDateCalculator/Test_DueDateCalculator.php');


$tester = new \DueDateCalculator\Test_DueDateCalculator();
$tester->testDueDateCalculate();