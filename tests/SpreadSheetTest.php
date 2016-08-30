<?php

use App\SpreadSheet;
use Carbon\Carbon;

class SpreadSheetTest extends TestCase
{
    public $S;

    /**
     * @return array
     */
    public function setUp()
    {
        parent::setUp();

        $this->S = new SpreadSheet();
    }

    public function testReadCsv()
    {
        var_dump($this->S->getSpreadSheetByDate());
    }
}
