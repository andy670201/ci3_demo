<?php
/**
 * 最新消息資料模型
 *
 * 負責 news 資料表的完整 CRUD 操作，
 * 以及已發佈文章的查詢、計數與發佈狀態切換。
 *
 * @package    CI3 Demo
 * @subpackage Models
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends CI_Model {

    /**
     * 取得已發佈的文章列表（支援分頁）
     *
     * 依發佈時間倒序排列；$limit 為 null 時取全部。
     *
     * @param  int|null $limit  每頁筆數（null 表示不限制）
     * @param  int      $offset 偏移量（從第幾筆開始取，預設 0）
     * @return array            已發佈文章物件陣列
     */
    public function get_published($limit = null, $offset = 0) {
        $this->db->where('is_published', 1)->order_by('published_at', 'DESC');
        if ($limit) $this->db->limit($limit, $offset);
        return $this->db->get('news')->result();
    }

    /**
     * 計算已發佈文章總數
     *
     * 供分頁功能計算總頁數使用。
     *
     * @return int 已發佈文章筆數
     */
    public function count_published() {
        return $this->db->where('is_published', 1)->count_all_results('news');
    }

    /**
     * 依 ID 取得單一文章（不限發佈狀態）
     *
     * 後台編輯及前台詳情頁均使用，前台需額外判斷 is_published。
     *
     * @param  int        $id 文章 ID
     * @return object|null    找到時回傳文章物件，否則回傳 null
     */
    public function get_by_id($id) {
        return $this->db->where('id', $id)->get('news')->row();
    }

    /**
     * 取得所有文章（含草稿）
     *
     * 供後台列表頁使用，依建立時間倒序排列。
     *
     * @return array 文章物件陣列
     */
    public function get_all() {
        return $this->db->order_by('created_at', 'DESC')->get('news')->result();
    }

    /**
     * 新增文章
     *
     * @param  array $data 文章資料（title, summary, content, is_published, published_at）
     * @return bool        成功回傳 true
     */
    public function insert($data) {
        return $this->db->insert('news', $data);
    }

    /**
     * 更新文章
     *
     * @param  int   $id   文章 ID
     * @param  array $data 要更新的欄位與值
     * @return bool        成功回傳 true
     */
    public function update($id, $data) {
        return $this->db->where('id', $id)->update('news', $data);
    }

    /**
     * 刪除文章
     *
     * @param  int  $id 文章 ID
     * @return bool     成功回傳 true
     */
    public function delete($id) {
        return $this->db->where('id', $id)->delete('news');
    }

    /**
     * 切換文章發佈狀態
     *
     * 將文章在「已發佈」與「草稿」之間切換。
     * 若從草稿改為發佈，同時記錄 published_at 為當前時間；
     * 若從發佈改為草稿，不清除 published_at（保留原始發佈時間紀錄）。
     *
     * @param  int  $id 文章 ID
     * @return bool     成功回傳 true
     */
    public function toggle($id) {
        $n = $this->get_by_id($id);
        $d = ['is_published' => $n->is_published ? 0 : 1];
        // 從草稿切換為發佈時，補上發佈時間戳記
        if (!$n->is_published) $d['published_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->update('news', $d);
    }
}
