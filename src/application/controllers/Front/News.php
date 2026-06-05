<?php
/**
 * 前台最新消息控制器
 *
 * 提供前台最新消息列表（分頁）及文章詳情頁。
 * 只顯示已發佈（is_published = 1）的文章。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Front
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Front_Controller {

    /**
     * 建構式
     * 載入 News_model 以查詢最新消息資料。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('News_model');
    }

    /**
     * 最新消息列表頁（支援分頁）
     *
     * 每頁顯示 9 筆；透過 GET 參數 page 控制當前頁碼（預設第 1 頁）。
     * 傳入 View 的資料包含文章列表、總筆數、當前頁碼與每頁筆數（供前端產生分頁連結）。
     *
     * @return void
     */
    public function index() {
        $page     = (int)($this->input->get('page') ?: 1);
        $per_page = 9;

        $data['news']     = $this->News_model->get_published($per_page, ($page - 1) * $per_page);
        $data['total']    = $this->News_model->count_published();
        $data['page']     = $page;
        $data['per_page'] = $per_page;
        $this->render('frontend/news/index', $data);
    }

    /**
     * 最新消息詳情頁
     *
     * 依 ID 查詢文章；若文章不存在或尚未發佈則顯示 404。
     * 同時取得最近 3 篇已發佈文章供側欄「近期文章」顯示。
     *
     * @param  int $id 文章 ID
     * @return void
     */
    public function detail($id) {
        $article = $this->News_model->get_by_id($id);
        // 文章不存在或為草稿均顯示 404
        if (!$article || !$article->is_published) show_404();

        $data['article']     = $article;
        $data['recent_news'] = $this->News_model->get_published(3);
        $this->render('frontend/news/detail', $data);
    }
}
