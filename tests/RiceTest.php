<?php

use App\Rice;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RiceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * 本日抽選済みかどうか
     * @return void
     */
    public function testIsSelected()
    {
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        // today: 未抽選
        factory(Rice::class, 5)->create([
            'date' => $today,
            'ricer' => false,
        ]);

        // yesterday: 抽選済み
        factory(Rice::class, 1)->create([
            'date' => $yesterday,
            'ricer' => true,
        ]);
        factory(Rice::class, 4)->create([
            'date' => $yesterday,
            'ricer' => false,
        ]);

        // assert
        $this->assertEquals(Rice::isSelected($today), false);
        $this->assertEquals(Rice::isSelected($yesterday), true);
    }
}
