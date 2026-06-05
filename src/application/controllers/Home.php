<?php
/**
 * 前台首頁控制器
 *
 * 取得首頁所需資料：
 * - 精選商品（最多 8 件）
 * - 最新發佈文章（最多 3 篇）
 * 並渲染首頁 View。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Front
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Front_Controller {

    /**
     * 建構式
     * 載入 News_model（Product_model 由 Front_Controller 父類別預先載入）。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('News_model');
    }

    /**
     * 首頁
     *
     * 取得精選商品與最新消息後傳入 View。
     *
     * @return void
     */
    public function index() {
        // 取得標記為精選（is_featured = 1）且上架中的商品，最多 8 件
        $data['featured_products'] = $this->Product_model->get_featured(8);
        // 取得最新 3 篇已發佈文章
        $data['latest_news']       = $this->News_model->get_published(3);
        $this->render('frontend/home/index', $data);
    }
}
