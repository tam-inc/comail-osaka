<?php

namespace App\Http\Controllers;
use App\Rice;
use App\SpreadSheet;
use Carbon\Carbon;

class RiceController extends Controller
{
    public $Rice;

    public function __construct()
    {
        $this->Rice = new Rice();
    }

    /**
     * cronから実行、11時台ならピックアップする
     * @return string
     */
    public function pickup_cron()
    {
        if (Carbon::now()->hour == 11) {
            return $this->pickup();
        }
    }


    /**
     * ライサーを決めて通知する
     * 毎日 11:50 実施
     * @return string
     */
    public function pickup()
    {
        if ($this->Rice->isSelected()) {
            Log::warning('ピックアップ済み');
            return '0';
        }

        // スプレッドシートから取得
        $S = new SpreadSheet();
        $todayMembers = $S->getSpreadSheetByDate();

        if (empty($todayMembers)) {
            Log::info('no member today');
            return '0';
        }

        // きょうのデータを消しておき
        $this->Rice->removeToday();
        // DB保存
        $this->Rice->insert($todayMembers);

        // ピックアップ
        $ricer_email = $this->Rice->pickup();
        if (empty($ricer_email)) {
            Log::error('pickup failure');
            return '0';
        }

        // ピックアップデータを反映
        $ricer = $this->Rice->setRicer($ricer_email);

        // Slack と メールで通知
//        $this->Slack->send($ricer);
//        $this->Mail->send($ricer);

        return '1';
    }

    /**
     * きょうのライサーをリセット
     * @return string
     */
    public function reset()
    {
        $this->Rice->removeToday();
        return '1';
    }
}
