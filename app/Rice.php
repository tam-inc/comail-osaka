<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Rice extends Model
{
    /**
     * 本日抽選済みかどうかを返す
     * @param null $date
     * @return bool
     */
    static function isSelected($date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();

        $ricer = static::where('date', '=', $date)->where('ricer', '=', true)->count();

        return ($ricer > 0);
    }
}
