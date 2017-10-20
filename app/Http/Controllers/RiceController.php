<?php

namespace App\Http\Controllers;
use App\Rice;
use App\SpreadSheet;
use App\Notify;
use Slack;
use Log;

class RiceController extends Controller
{
    public $Rice;

    public function __construct()
    {
        $this->Rice = new Rice();
    }

    /**
     * ライサーを決めて通知する
     * 毎日 11:50 実施
     * @return string
     */
    public function pickup_cron()
    {
        if ($this->Rice->isSelected()) {
            Log::warning('ピックアップ済み');
            return '0';
        }

        // スプレッドシートから取得
        $S = new SpreadSheet();
        $todayMembers = $S->getSpreadSheetByDate();

        if (empty($todayMembers)) {
            Log::info('本日希望者なし');
            return '0';
        }

        // きょうのデータを消しておき
        $this->Rice->removeToday();
        // DB保存
        $this->Rice->insert($todayMembers);

        // ピックアップ
        $ricer_email = $this->Rice->pickup();
        if (empty($ricer_email)) {
            Log::error('ピックアップエラー');
            return '0';
        }

        // ピックアップデータを反映
        $ricer = $this->Rice->setRicer($ricer_email);

        // 分量を取得
        $volume = $this->Rice->getVolume();

        // Slack通知
        Slack::send("本日の米炊き当番は 【{$ricer->name}さん】に決定！！ (分量 {$volume}合)");

        // メール通知
        Notify::mail($ricer->email, $ricer->name, $volume);

        Log::info("ピックアップ: {$ricer->name}");
        return '1';
    }

    /**
     * きょうのライサーをリセット
     * @return string
     */
    public function reset()
    {
        $this->Rice->removeToday();
        Log::info("ピックアップ reset");
        return '1';
    }


    /**
     * ごちそうさま＆お片付けありがとうメールを送信
     */
    public function cleanup_cron()
    {
        //今日の担当者を取得
        $todayRicer = $this->Rice->getRicer();


        //希望者がいなかった日は何もしない
        if (empty($todayRicer)) {
            Log::info('本日希望者なし');
            return '0';
        }

        //担当者にメール投げる
        Notify::cleanupMail($todayRicer->email,$todayRicer->name);
        Log::info("お片付けリマインダ: {$todayRicer->name}");
        return '1';
    }

}
