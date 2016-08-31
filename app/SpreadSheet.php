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
            return [
                'date' => $row[0],
                'name' => $row[1],
                'email' => $row[2],
                'volume' => (empty($row[4])) ? 0 : $row[4],
                'comment' => $row[5],
                'ricer' => false,
            ];
            return $a;
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
