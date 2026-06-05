<?php
/**
 * 聯絡訊息資料模型
 *
 * 負責 contact_messages 資料表的 CRUD 操作，
 * 以及未讀訊息的標記與計數功能。
 *
 * @package    CI3 Demo
 * @subpackage Models
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_model extends CI_Model {

    /**
     * 新增聯絡訊息
     *
     * @param  array $data 訊息資料（name, email, phone, subject, message）
     * @return bool        成功回傳 true
     */
    public function insert($data) {
        return $this->db->insert('contact_messages', $data);
    }

    /**
     * 取得所有聯絡訊息
     *
     * 依建立時間倒序排列（最新的在前）。
     *
     * @return array 聯絡訊息物件陣列
     */
    public function get_all() {
        return $this->db->order_by('created_at', 'DESC')->get('contact_messages')->result();
    }

    /**
     * 依 ID 取得單一聯絡訊息
     *
     * @param  int        $id 訊息 ID
     * @return object|null    找到時回傳訊息物件，否則回傳 null
     */
    public function get_by_id($id) {
        return $this->db->where('id', $id)->get('contact_messages')->row();
    }

    /**
     * 標記訊息為已讀
     *
     * 將指定 ID 的訊息 is_read 欄位設為 1，
     * 通常在管理員進入詳情頁時自動呼叫。
     *
     * @param  int  $id 訊息 ID
     * @return bool     成功回傳 true
     */
    public function mark_read($id) {
        return $this->db->where('id', $id)->update('contact_messages', ['is_read' => 1]);
    }

    /**
     * 刪除聯絡訊息
     *
     * @param  int  $id 訊息 ID
     * @return bool     成功回傳 true
     */
    public function delete($id) {
        return $this->db->where('id', $id)->delete('contact_messages');
    }

    /**
     * 計算未讀訊息數
     *
     * 供後台儀表板顯示未讀計數徽章使用。
     *
     * @return int 未讀訊息筆數
     */
    public function count_unread() {
        return $this->db->where('is_read', 0)->count_all_results('contact_messages');
    }
}
