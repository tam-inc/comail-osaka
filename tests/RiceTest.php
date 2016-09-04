<?php

use App\Rice;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RiceTest extends TestCase
{
    use DatabaseMigrations;

    public $Rice;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Rice = new Rice();
    }

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
            'name' => 'riceman',
            'date' => $yesterday,
            'ricer' => true,
        ]);
        factory(Rice::class, 4)->create([
            'date' => $yesterday,
            'ricer' => false,
        ]);

        // assert
        $this->assertEquals($this->Rice->isSelected($today), false);
        $this->assertEquals($this->Rice->isSelected($yesterday), true);
    }

    /**
     * 最近20日の結果を返す
     */
    public function testGetResult()
    {
        $data = [
            [
                'date'  => Carbon::today()->toDateString(),
                'ricer' => true,
            ],
            [
                'date'  => Carbon::yesterday()->toDateString(),
                'ricer' => false,
            ],
            [
                'date'  => Carbon::parse('-20days')->toDateString(),
                'ricer' => true,
            ],
        ];

        foreach ($data as $a) {
            factory(Rice::class)->create($a);
        }

        $this->assertEquals(count($this->Rice->getResult()), 2);
    }

    public function testVolume()
    {
        $data = [
            [
                'date'  => Carbon::today()->toDateString(),
                'volume' => 1,
            ],
            [
                'date'  => Carbon::today()->toDateString(),
                'volume' => 2,
            ],
            [
                'date'  => Carbon::today()->toDateString(),
                'volume' => 3,
            ],
            [
                'date'  => Carbon::yesterday()->toDateString(),
                'volume' => 4,
            ],
        ];

        foreach ($data as $a) {
            factory(Rice::class)->create($a);
        }

        $this->assertEquals($this->Rice->getVolume(), 1+2+3);
    }

    /**
     * 最近20日の結果を返す
     */
    public function testPickup1()
    {
        // Bが担当多いのでAが本日の担当に
        $data = [
            [
                'date'  => Carbon::today()->toDateString(),
                'email' => 'A',
            ],
            [
                'date'  => Carbon::today()->toDateString(),
                'email' => 'B',
            ],
            [
                'date'  => Carbon::yesterday()->toDateString(),
                'email' => 'A',
                'ricer' => false,
            ],
            [
                'date'  => Carbon::yesterday()->toDateString(),
                'email' => 'B',
                'ricer' => true,
            ],
        ];

        foreach ($data as $a) {
            factory(Rice::class)->create($a);
        }

        $this->assertEquals($this->Rice->pickup(), 'A');


        // Aが担当多いのでBが本日の担当に
        $data = [
            [
                'date'  => Carbon::parse('-3days')->toDateString(),
                'email' => 'A',
                'ricer' => true,
            ],
            [
                'date'  => Carbon::parse('-4days')->toDateString(),
                'email' => 'A',
                'ricer' => true,
            ],
        ];

        foreach ($data as $a) {
            factory(Rice::class)->create($a);
        }

        $this->assertEquals($this->Rice->pickup(), 'B');


        // Bが担当多いけど7日以上前なので、Bが本日の担当に
        $data = [
            [
                'date'  => Carbon::parse('-8days')->toDateString(),
                'email' => 'B',
                'ricer' => true,
            ],
            [
                'date'  => Carbon::parse('-9days')->toDateString(),
                'email' => 'B',
                'ricer' => true,
            ],
        ];

        foreach ($data as $a) {
            factory(Rice::class)->create($a);
        }

        $this->assertEquals($this->Rice->pickup(), 'B');
    }
}
