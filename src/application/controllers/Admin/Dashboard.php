<?php
/**
 * 後台儀表板控制器
 *
 * 彙整網站各模組的統計數據（商品、訂單、未讀訊息、會員數等），
 * 並顯示最近 5 筆訂單與未讀聯絡訊息，提供管理員整體概覽。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Admin
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    /**
     * 儀表板首頁
     *
     * 統計資料包含：
     * - 商品總數 / 上架中商品數
     * - 訂單總數 / 待處理訂單數
     * - 未讀聯絡訊息數
     * - 會員總數
     *
     * 另外取得最近 5 筆訂單與最近 5 筆未讀聯絡訊息供快速瀏覽。
     *
     * @return void
     */
    public function index() {
        $this->load->model('Order_model');

        // 彙整各模組統計數字
        $data['stats'] = [
            'total_products'  => $this->db->count_all('products'),
            'active_products' => $this->db->where('is_active', 1)->count_all_results('products'),
            'total_orders'    => $this->Order_model->count_all(),
            'pending_orders'  => $this->db->where('status', 'pending')->count_all_results('orders'),
            'unread_messages' => $this->Contact_model->count_unread(),
            'total_members'   => $this->db->count_all('members'),
        ];

        // 取最近 5 筆訂單，供快速查看
        $data['recent_orders'] = $this->db->order_by('created_at', 'DESC')->limit(5)->get('orders')->result();

        // 取最近 5 筆未讀聯絡訊息
        $data['recent_messages'] = $this->db->where('is_read', 0)->order_by('created_at', 'DESC')->limit(5)->get('contact_messages')->result();

        $this->render('admin/dashboard/index', $data);
    }
}
