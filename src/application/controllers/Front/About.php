<?php
/**
 * 前台「關於我們」控制器
 *
 * 顯示品牌介紹或公司說明的靜態頁面，
 * 不需要從資料庫取得動態資料。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Front
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends Front_Controller {

    /**
     * 關於我們頁面
     *
     * 渲染靜態的關於我們 View。
     *
     * @return void
     */
    public function index() {
        $this->render('frontend/pages/about', []);
    }
}
