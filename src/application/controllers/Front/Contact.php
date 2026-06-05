<?php
/**
 * 前台聯絡我們控制器
 *
 * 顯示聯絡表單頁面，並處理使用者送出的聯絡訊息。
 * 透過 CodeIgniter Form Validation 進行欄位驗證，
 * 驗證通過後將資料寫入資料庫。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Front
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Front_Controller {

    /**
     * 建構式
     * 載入 Contact_model 以寫入聯絡訊息。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Contact_model');
    }

    /**
     * 聯絡我們頁面
     *
     * 顯示聯絡表單，若剛送出成功會透過 Flash Data 顯示成功訊息。
     *
     * @return void
     */
    public function index() {
        // 讀取上一次送出後的成功訊息（Flash Data 只讀取一次即消失）
        $data['success'] = $this->session->flashdata('success');
        $this->render('frontend/pages/contact', $data);
    }

    /**
     * 處理聯絡表單送出
     *
     * 驗證規則：
     * - 姓名、Email（需格式正確）、主旨、訊息內容均為必填。
     * 驗證通過後將訊息寫入資料庫並設定成功 Flash 訊息；
     * 無論驗證成功或失敗均跳轉回聯絡頁（Form Validation 錯誤由 View 顯示）。
     *
     * @return void
     */
    public function send() {
        $this->form_validation->set_rules('name',    '姓名',  'required');
        $this->form_validation->set_rules('email',   'Email', 'required|valid_email');
        $this->form_validation->set_rules('subject', '主旨',  'required');
        $this->form_validation->set_rules('message', '訊息',  'required');

        if ($this->form_validation->run()) {
            // 驗證通過：寫入聯絡訊息
            $this->Contact_model->insert([
                'name'    => $this->input->post('name'),
                'email'   => $this->input->post('email'),
                'phone'   => $this->input->post('phone'),
                'subject' => $this->input->post('subject'),
                'message' => $this->input->post('message'),
            ]);
            $this->session->set_flashdata('success', '感謝您的來信！我們將儘快回覆您。');
        }

        redirect('contact');
    }
}
