<?php
/**
 * CodeIgniter 預設歡迎頁控制器
 *
 * 此為 CodeIgniter 框架安裝後預設產生的範例控制器，
 * 用於確認框架安裝是否正常運作。
 * 在正式專案中通常不會對外開放，可視需求刪除或覆蓋預設路由。
 *
 * @package    CI3 Demo
 * @subpackage Controllers
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    /**
     * 歡迎頁
     *
     * 載入並顯示 CodeIgniter 預設的歡迎訊息 View。
     * 對應路由：/index.php/welcome 或設為預設控制器時的 /
     *
     * @return void
     */
    public function index() {
        $this->load->view('welcome_message');
    }
}
