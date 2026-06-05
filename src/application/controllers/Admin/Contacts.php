<?php
/**
 * 後台聯絡訊息控制器
 *
 * 提供管理員檢視、閱讀及刪除前台使用者所送出聯絡表單訊息的功能。
 * 繼承 Admin_Controller，所有 Method 均需登入後才能存取。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Admin
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends Admin_Controller {

    /**
     * 建構式
     * 載入 Contact_model 以操作聯絡訊息資料。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Contact_model');
    }

    /**
     * 訊息列表頁
     *
     * 取得所有聯絡訊息（依建立時間倒序）並傳入 View。
     *
     * @return void
     */
    public function index() {
        $this->render('admin/contacts/index', ['messages' => $this->Contact_model->get_all()]);
    }

    /**
     * 訊息詳情頁
     *
     * 依 ID 查詢單筆訊息，若不存在則顯示 404。
     * 顯示後自動標記為已讀。
     *
     * @param  int $id 聯絡訊息 ID
     * @return void
     */
    public function detail($id) {
        $data['message'] = $this->Contact_model->get_by_id($id);
        if (!$data['message']) show_404();

        // 進入詳情頁即自動標記為已讀，更新未讀計數
        $this->Contact_model->mark_read($id);
        $this->render('admin/contacts/detail', $data);
    }

    /**
     * 刪除訊息
     *
     * 依 ID 刪除指定聯絡訊息，完成後設定 Flash 訊息並跳轉至列表頁。
     *
     * @param  int $id 聯絡訊息 ID
     * @return void
     */
    public function delete($id) {
        $this->Contact_model->delete($id);
        $this->session->set_flashdata('success', '訊息已刪除');
        redirect('admin/contacts');
    }
}
