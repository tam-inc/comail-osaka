<?php

namespace App;

use Carbon\Carbon;
use League\Csv\Reader;

class SpreadSheet
{
    public $keys = [
        'date',
        'name',
        'email',
        'mori',
        'volume',
        'comment',
    ];

    /**
     * 本日の希望者を google ドキュメントから取得
     */
    public function getSpreadSheet()
    {
        // CSV取得
        $csvText = file_get_contents(env('RICE_SHEET_URL_CSV'));
        $csv = Reader::createFromString($csvText);

        $data = $csv->setOffset(2)->fetchAll(function($row) {
            return array_combine($this->keys, array_slice($row, 0, 6));
        });

        return $data;
    }

    public function getSpreadSheetByDate($date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();

        $data = $this->getSpreadSheet();

        $out = [];
        foreach ($data as $a) {
            if ($a['date'] == $date) {
                $out[] = $a;
            }
        }

        return $out;
    }
}
