<?php
/**
 * 前台會員控制器
 *
 * 處理會員相關功能，包含：
 * - 會員註冊（表單驗證 + 密碼雜湊）
 * - 會員登入 / 登出
 * - 會員個人儀表板（查看歷史訂單）
 * - 查看單筆訂單詳情
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Front
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Front_Controller {

    /**
     * 建構式
     * 載入 Member_model 與 Order_model。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Member_model');
        $this->load->model('Order_model');
    }

    /**
     * 會員註冊
     *
     * GET  → 顯示註冊表單；已登入者跳轉至首頁。
     * POST → 驗證欄位後建立會員帳號，
     *        建立成功後自動登入（寫入 Session）並跳轉至會員儀表板。
     *
     * 驗證規則：姓名必填、Email 必填且格式正確且不重複、密碼最少 6 碼。
     *
     * @return void
     */
    public function register() {
        // 已登入者無需再註冊
        if ($this->session->userdata('member')) redirect('/');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('name',     '姓名', 'required');
            $this->form_validation->set_rules('email',    'Email', 'required|valid_email|is_unique[members.email]');
            $this->form_validation->set_rules('password', '密碼', 'required|min_length[6]');

            if ($this->form_validation->run()) {
                // 建立新會員並取回 ID
                $id = $this->Member_model->register([
                    'name'     => $this->input->post('name'),
                    'email'    => $this->input->post('email'),
                    'password' => $this->input->post('password'),
                    'phone'    => $this->input->post('phone'),
                ]);
                // 註冊後自動登入：將會員資料寫入 Session
                $this->session->set_userdata('member', (array)$this->Member_model->get_by_id($id));
                redirect('member/dashboard');
            }
        }

        $this->render('frontend/member/register', []);
    }

    /**
     * 會員登入
     *
     * GET  → 顯示登入表單；已登入者跳轉至首頁。
     * POST → 驗證 Email 與密碼（bcrypt 比對），
     *        成功後將會員資料存入 Session 並跳轉至儀表板；
     *        失敗則傳回錯誤訊息重新顯示登入表單。
     *
     * @return void
     */
    public function login() {
        if ($this->session->userdata('member')) redirect('/');

        if ($this->input->method() === 'post') {
            $m = $this->Member_model->verify(
                $this->input->post('email'),
                $this->input->post('password')
            );
            if ($m) {
                $this->session->set_userdata('member', (array)$m);
                redirect('member/dashboard');
            }
            // 驗證失敗：帶入錯誤訊息
            $this->render('frontend/member/login', ['error' => 'Email 或密碼錯誤']);
            return;
        }

        $this->render('frontend/member/login', []);
    }

    /**
     * 會員登出
     *
     * 清除 Session 中的會員資料，跳轉至首頁。
     *
     * @return void
     */
    public function logout() {
        $this->session->unset_userdata('member');
        redirect('/');
    }

    /**
     * 會員個人儀表板
     *
     * 需登入才可存取；未登入跳轉至登入頁。
     * 顯示該會員的所有歷史訂單列表。
     *
     * @return void
     */
    public function dashboard() {
        if (!$this->session->userdata('member')) redirect('member/login');

        $m              = $this->session->userdata('member');
        $data['orders'] = $this->Order_model->get_by_member($m['id']);
        $this->render('frontend/member/dashboard', $data);
    }

    /**
     * 會員訂單詳情頁
     *
     * 需登入才可存取；查詢時帶入會員 ID 避免查看他人訂單。
     * 若找不到訂單顯示 404；否則同時取得訂單明細列表。
     *
     * @param  string $order_number 訂單編號
     * @return void
     */
    public function order_detail($order_number) {
        if (!$this->session->userdata('member')) redirect('member/login');

        $m             = $this->session->userdata('member');
        // 查詢時限定 member_id，確保會員只能看自己的訂單
        $data['order'] = $this->Order_model->get_detail($order_number, $m['id']);
        if (!$data['order']) show_404();

        $data['items'] = $this->Order_model->get_items($data['order']->id);
        $this->render('frontend/member/order_detail', $data);
    }
}
