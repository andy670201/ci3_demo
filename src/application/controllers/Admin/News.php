<?php
/**
 * 後台最新消息控制器
 *
 * 提供管理員對最新消息（文章）進行 CRUD 操作，
 * 以及切換發佈狀態的功能。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Admin
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Admin_Controller {

    /**
     * 建構式
     * 載入 News_model 以操作最新消息資料。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('News_model');
    }

    /**
     * 文章列表頁
     *
     * 取得所有文章（含未發佈）並傳入 View。
     *
     * @return void
     */
    public function index() {
        $this->render('admin/news/index', ['news' => $this->News_model->get_all()]);
    }

    /**
     * 新增文章表單頁
     *
     * 顯示空白新增表單，news 傳入 null 表示新建模式。
     *
     * @return void
     */
    public function create() {
        $this->render('admin/news/form', ['news' => null]);
    }

    /**
     * 儲存新增文章
     *
     * 取得 POST 資料，若勾選「立即發佈」則同時記錄發佈時間；
     * 否則 published_at 留 null。儲存後跳轉至列表頁。
     *
     * @return void
     */
    public function store() {
        // 將 checkbox 轉為 0/1 整數
        $p = $this->input->post('is_published') ? 1 : 0;

        $this->News_model->insert([
            'title'        => $this->input->post('title'),
            'summary'      => $this->input->post('summary'),
            'content'      => $this->input->post('content'),
            'is_published' => $p,
            // 立即發佈時記錄發佈時間，草稿則為 null
            'published_at' => $p ? date('Y-m-d H:i:s') : null,
        ]);

        $this->session->set_flashdata('success', '文章已建立');
        redirect('admin/news');
    }

    /**
     * 編輯文章表單頁
     *
     * 取得指定 ID 的文章資料並傳入表單 View（編輯模式）。
     *
     * @param  int $id 文章 ID
     * @return void
     */
    public function edit($id) {
        $this->render('admin/news/form', ['news' => $this->News_model->get_by_id($id)]);
    }

    /**
     * 更新文章
     *
     * 依 ID 更新文章內容，發佈狀態同樣處理 checkbox 轉換。
     * 完成後跳轉至列表頁。
     *
     * @param  int $id 文章 ID
     * @return void
     */
    public function update($id) {
        $p = $this->input->post('is_published') ? 1 : 0;

        $this->News_model->update($id, [
            'title'        => $this->input->post('title'),
            'summary'      => $this->input->post('summary'),
            'content'      => $this->input->post('content'),
            'is_published' => $p,
        ]);

        $this->session->set_flashdata('success', '文章已更新');
        redirect('admin/news');
    }

    /**
     * 刪除文章
     *
     * 依 ID 刪除文章，完成後設定 Flash 訊息並跳轉至列表頁。
     *
     * @param  int $id 文章 ID
     * @return void
     */
    public function delete($id) {
        $this->News_model->delete($id);
        $this->session->set_flashdata('success', '文章已刪除');
        redirect('admin/news');
    }

    /**
     * 切換發佈狀態
     *
     * 呼叫 News_model::toggle() 將文章在「發佈」與「草稿」之間切換，
     * 完成後跳轉至列表頁。
     *
     * @param  int $id 文章 ID
     * @return void
     */
    public function toggle($id) {
        $this->News_model->toggle($id);
        redirect('admin/news');
    }
}
