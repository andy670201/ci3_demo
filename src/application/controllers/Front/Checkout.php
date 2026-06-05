<?php
/**
 * 前台結帳控制器
 *
 * 處理購物車結帳流程，包含：
 * - 顯示結帳確認頁（收件人資訊填寫）
 * - 確認送出訂單（建立訂單主檔與明細）
 * - 訂單完成頁（顯示訂單號碼及摘要）
 *
 * 所有 Method 均需會員登入且購物車不為空才可存取。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Front
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends Front_Controller {

    /**
     * 建構式
     * 載入 Order_model 以進行訂單建立與查詢。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
    }

    /**
     * 結帳確認頁
     *
     * 顯示購物車商品清單、總金額及收件人資訊填寫表單。
     * 未登入或購物車為空時自動跳轉。
     *
     * @return void
     */
    public function index() {
        if (!$this->session->userdata('member')) redirect('member/login');

        $cart = $this->session->userdata('cart') ?: [];
        if (empty($cart)) redirect('cart');

        $data['cart']   = $cart;
        $data['total']  = array_sum(array_map(function ($i) {
            return $i['price'] * $i['quantity'];
        }, $cart));
        $data['member'] = $this->session->userdata('member');
        $this->render('frontend/checkout/index', $data);
    }

    /**
     * 確認並送出訂單
     *
     * 1. 將購物車內容轉換為訂單明細陣列。
     * 2. 計算訂單總金額。
     * 3. 呼叫 Order_model::create() 建立訂單主檔與明細，取得訂單編號。
     * 4. 清空購物車 Session。
     * 5. 跳轉至訂單完成頁。
     *
     * @return void
     */
    public function confirm() {
        if (!$this->session->userdata('member')) redirect('member/login');

        $cart = $this->session->userdata('cart') ?: [];
        $m    = $this->session->userdata('member');
        if (empty($cart)) redirect('cart');

        // 將 Session 購物車轉換為訂單明細格式
        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'product_id'   => $item['product_id'],
                'product_name' => $item['name'],
                'size'         => $item['size'],
                'color'        => $item['color'],
                'quantity'     => $item['quantity'],
                'price'        => $item['price'],
            ];
        }

        $total = array_sum(array_map(function ($i) {
            return $i['price'] * $i['quantity'];
        }, $cart));

        // 建立訂單並取回系統產生的訂單編號
        $order_number = $this->Order_model->create([
            'member_id'         => $m['id'],
            'total_amount'      => $total,
            'recipient_name'    => $this->input->post('recipient_name'),
            'recipient_phone'   => $this->input->post('recipient_phone'),
            'recipient_address' => $this->input->post('recipient_address'),
            'note'              => $this->input->post('note'),
        ], $items);

        // 訂單建立成功後清空購物車
        $this->session->unset_userdata('cart');
        redirect('checkout/success/' . $order_number);
    }

    /**
     * 訂單完成頁
     *
     * 依訂單編號查詢訂單資料（限本會員），顯示訂單摘要。
     * 若傳入 member 為 null（訪客）則不限制會員 ID 查詢。
     *
     * @param  string $order_number 訂單編號（如 ORD20240101120000123）
     * @return void
     */
    public function success($order_number) {
        $m             = $this->session->userdata('member');
        // 限制查詢屬於本會員的訂單（避免猜測他人訂單編號）
        $data['order'] = $this->Order_model->get_detail($order_number, $m ? $m['id'] : null);
        $this->render('frontend/checkout/success', $data);
    }
}
