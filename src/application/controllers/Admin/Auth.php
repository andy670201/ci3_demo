<?php
/**
 * 後台認證控制器
 *
 * 負責管理員的登入與登出功能，
 * 驗證帳號密碼後將登入狀態寫入 Session。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Admin
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    /**
     * 建構式
     * 載入 Admin_model 以進行帳號驗證。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    /**
     * 登入頁面 / 登入處理
     *
     * GET  → 顯示登入表單；若已登入則直接跳轉至儀表板。
     * POST → 取得表單帳號密碼，呼叫 Admin_model::verify() 驗證。
     *        驗證成功後將登入狀態與管理員姓名存入 Session 並跳轉；
     *        失敗則重新顯示登入表單並帶入錯誤訊息。
     *
     * @return void
     */
    public function login() {
        // 已登入者直接跳轉，避免重複登入
        if ($this->session->userdata('admin_logged_in')) redirect('admin/dashboard');

        if ($this->input->method() === 'post') {
            // 透過 Model 驗證帳號密碼（含 bcrypt 比對）
            $admin = $this->Admin_model->verify(
                $this->input->post('username'),
                $this->input->post('password')
            );

            if ($admin) {
                // 驗證成功：將登入旗標與姓名寫入 Session
                $this->session->set_userdata(['admin_logged_in' => true, 'admin_name' => $admin->name]);
                redirect('admin/dashboard');
            }

            // 驗證失敗：回傳錯誤訊息給 View
            $this->load->view('admin/auth/login', ['error' => '帳號或密碼錯誤']);
            return;
        }

        // GET 請求：顯示空白登入表單
        $this->load->view('admin/auth/login', []);
    }

    /**
     * 登出
     *
     * 清除 Session 中的管理員登入資訊，並跳轉至登入頁。
     *
     * @return void
     */
    public function logout() {
        $this->session->unset_userdata(['admin_logged_in', 'admin_name']);
        redirect('admin/login');
    }
}
