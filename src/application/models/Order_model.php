<?php
/**
 * 訂單資料模型
 *
 * 負責 orders（訂單主檔）與 order_items（訂單明細）資料表的操作。
 * 訂單編號由系統自動產生（ORD + 時間戳 + 3 位隨機數），確保唯一性。
 *
 * @package    CI3 Demo
 * @subpackage Models
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    /**
     * 建立訂單（主檔 + 明細）
     *
     * 1. 自動產生訂單編號並寫入訂單主檔。
     * 2. 逐筆寫入訂單明細（order_items）。
     * 3. 回傳訂單編號供跳轉至完成頁使用。
     *
     * @param  array  $data  訂單主檔資料（member_id, total_amount, recipient_name 等）
     * @param  array  $items 訂單明細陣列，每筆含 product_id, product_name, size, color, quantity, price
     * @return string        產生的訂單編號（如 ORD20240101120000123）
     */
    public function create($data, $items) {
        // 以時間戳 + 隨機數組合出唯一訂單編號，極低機率碰撞
        $data['order_number'] = 'ORD' . date('YmdHis') . rand(100, 999);
        $this->db->insert('orders', $data);
        $oid = $this->db->insert_id();

        // 批次寫入訂單明細
        foreach ($items as $item) {
            $item['order_id'] = $oid;
            $this->db->insert('order_items', $item);
        }

        return $data['order_number'];
    }

    /**
     * 取得指定會員的所有訂單
     *
     * 依建立時間倒序排列，供會員儀表板顯示歷史訂單。
     *
     * @param  int   $mid 會員 ID
     * @return array      訂單物件陣列
     */
    public function get_by_member($mid) {
        return $this->db->where('member_id', $mid)->order_by('created_at', 'DESC')->get('orders')->result();
    }

    /**
     * 依訂單編號（及選擇性的會員 ID）取得訂單詳情
     *
     * 若傳入 $mid，則額外限制 member_id，
     * 防止會員查看他人訂單（安全防護）。
     * 後台管理員查詢時傳入 null 不限制會員。
     *
     * @param  string     $order_number 訂單編號
     * @param  int|null   $mid          會員 ID（null 表示不限制）
     * @return object|null              找到時回傳訂單物件，否則回傳 null
     */
    public function get_detail($order_number, $mid = null) {
        $this->db->where('order_number', $order_number);
        // 帶入 member_id 以限制只能查詢自己的訂單
        if ($mid) $this->db->where('member_id', $mid);
        return $this->db->get('orders')->row();
    }

    /**
     * 取得訂單的所有商品明細
     *
     * @param  int   $oid 訂單 ID（order_items.order_id）
     * @return array      訂單明細物件陣列
     */
    public function get_items($oid) {
        return $this->db->where('order_id', $oid)->get('order_items')->result();
    }

    /**
     * 取得所有訂單
     *
     * 依建立時間倒序排列，供後台訂單列表頁使用。
     *
     * @return array 訂單物件陣列
     */
    public function get_all() {
        return $this->db->order_by('created_at', 'DESC')->get('orders')->result();
    }

    /**
     * 更新訂單狀態
     *
     * 狀態值如：pending（待處理）、processing（處理中）、
     * shipped（已出貨）、completed（已完成）、cancelled（已取消）。
     *
     * @param  int    $id     訂單 ID
     * @param  string $status 新狀態值
     * @return bool           成功回傳 true
     */
    public function update_status($id, $status) {
        return $this->db->where('id', $id)->update('orders', ['status' => $status]);
    }

    /**
     * 計算訂單總數
     *
     * 供後台儀表板統計數字使用。
     *
     * @return int 訂單總筆數
     */
    public function count_all() {
        return $this->db->count_all('orders');
    }
}
