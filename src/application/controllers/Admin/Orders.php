<?php
/**
 * 後台訂單控制器
 *
 * 提供管理員查看訂單列表、訂單詳情，以及更新訂單處理狀態的功能。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Admin
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Admin_Controller {

    /**
     * 建構式
     * 載入 Order_model 以操作訂單資料。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
    }

    /**
     * 訂單列表頁
     *
     * 取得全部訂單（依建立時間倒序）並傳入 View。
     *
     * @return void
     */
    public function index() {
        $this->render('admin/orders/index', ['orders' => $this->Order_model->get_all()]);
    }

    /**
     * 訂單詳情頁
     *
     * 依 ID 查詢訂單主檔，若不存在顯示 404；
     * 同時取得該訂單的所有商品明細。
     *
     * @param  int $id 訂單 ID
     * @return void
     */
    public function detail($id) {
        // 直接查詢訂單主檔，不透過 Model（簡單查詢）
        $data['order'] = $this->db->where('id', $id)->get('orders')->row();
        if (!$data['order']) show_404();

        // 取得該訂單下的所有商品項目
        $data['items'] = $this->Order_model->get_items($id);
        $this->render('admin/orders/detail', $data);
    }

    /**
     * 更新訂單狀態
     *
     * 從 POST 取得新狀態值並更新至資料庫，
     * 完成後設定 Flash 訊息並跳轉回訂單詳情頁。
     *
     * @param  int $id 訂單 ID
     * @return void
     */
    public function update_status($id) {
        $this->Order_model->update_status($id, $this->input->post('status'));
        $this->session->set_flashdata('success', '訂單狀態已更新');
        redirect('admin/orders/' . $id);
    }
}
