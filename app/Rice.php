<?php

namespace App;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Rice extends Model
{
    protected $table = 'rice';

    /** @var array */
    protected $guarded = ['id', 'ricer'];


    /**
     * 本日の担当を返す
     * @param null $date
     * @return mixed
     */
    public function getRicer($date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();

        return $this
            ->where('date', $date)
            ->where('ricer', '=', true)
            ->first();
    }

    /**
     * 本日抽選済みかどうかを返す
     * @param null $date
     * @return bool
     */
    public function isSelected($date = null)
    {
        return ($this->getRicer($date) != null);
    }

    /**
     * 本日のメンバーリストを返す
     * @param null $date
     * @return mixed
     */
    public function getTodayMembers($date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();

        return $this
            ->where('date', $date)
            ->where('volume', '>', 0)
            ->orderBy('ricer', 'desc')
            ->inRandomOrder()
            ->pluck('name');
    }

    /**
     * 本日の分量を返す
     * @param null $date
     * @return mixed
     */
    public function getVolume($date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();

        return $this
            ->where('date', $date)
            ->where('volume', '>', 0)
            ->sum('volume');
    }

    /**
     * 最近20日間の担当者を返す
     * @return mixed
     */
    public function getResult()
    {
        return $this
            ->where('date', '>=', Carbon::parse('-20days')->toDateString())
            ->where('ricer', true)
            ->orderBy('date', 'desc')
            ->pluck('name', 'date');
    }

    /**
     * 担当者をえらぶ
     */
    public function pickup($date = null)
    {
        $date = $date ?? Carbon::today()->toDateString();

        // 本日の対象者 (過去7日間で担当の少ない人を選ぶ)
        $ricer = DB::table($this->table)
            ->select(DB::raw('rice.*, count(rice2.id) as cnt'))
            ->leftJoin('rice as rice2', function($join) use ($date) {

                // join: 過去7日間で担当した人を抽出
                $fromDate = Carbon::parse('-7days')->toDateString();
                $join
                    ->on('rice.email', '=', 'rice2.email')
                    ->where('rice2.date', '>=', $fromDate)
                    ->where('rice2.ricer', true);
            })
            ->where('rice.date', '=', $date)
            ->where('rice.volume', '>', 0)
            ->groupBy('rice.email')
            ->orderBy('cnt', 'asc')
            ->inRandomOrder()
            ->first();

        if (empty($ricer)) {
            return false;
        }

        return $ricer;
    }
}
