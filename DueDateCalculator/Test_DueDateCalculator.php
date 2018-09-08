<?php

namespace DueDateCalculator;


class Test_DueDateCalculator
{
    /** @var DueDateCalculator */
    private $calculator;

    public function __construct()
    {
        $this->calculator = new DueDateCalculator();
    }

    public function testDueDateCalculate()
    {
        $testData = [
            ['09-11-2018 9:00', 1],
            ['09-10-2018 12:00', "10:10"],
            ['2018-09-14 11:30', "16:00"]
        ];

        foreach($testData as $test)
        {
            try {
                $problem = $this->calculator->DueDateCalculate($test[0], $test[1]);
                echo $problem->GetInsertDate() . ' + ' . $test[1] . ' -> ' . $problem->GetDueDate();
            } catch(\Exception $ex) {
                echo $ex->getMessage();
            }

            echo '<hr />';
        }
    }
}