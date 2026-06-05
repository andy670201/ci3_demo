<?php
/**
 * 商品資料模型
 *
 * 負責 products（商品主檔）與 product_variants（商品規格）資料表的操作。
 * 大多數查詢均 JOIN categories 資料表以帶入分類名稱（category_name）。
 *
 * @package    CI3 Demo
 * @subpackage Models
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    /**
     * 取得上架中的商品列表（支援分類篩選與分頁）
     *
     * JOIN categories 以取得 category_name。
     * $category_id 為 null 時不篩選分類；$limit 為 null 時取全部。
     *
     * @param  int|null $category_id 分類 ID（null 表示不限分類）
     * @param  int|null $limit       每頁筆數（null 表示不限制）
     * @param  int      $offset      偏移量（預設 0）
     * @return array                 商品物件陣列（含 category_name）
     */
    public function get_active($category_id = null, $limit = null, $offset = 0) {
        $this->db->select('p.*, c.name as category_name')
                 ->from('products p')
                 ->join('categories c', 'c.id = p.category_id')
                 ->where('p.is_active', 1)
                 ->order_by('p.created_at', 'DESC');
        if ($category_id) $this->db->where('p.category_id', $category_id);
        if ($limit)       $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    /**
     * 計算上架中的商品總數（支援分類篩選）
     *
     * 供分頁功能計算總頁數使用。
     *
     * @param  int|null $category_id 分類 ID（null 表示不限分類）
     * @return int                   商品筆數
     */
    public function count_active($category_id = null) {
        $this->db->where('is_active', 1);
        if ($category_id) $this->db->where('category_id', $category_id);
        return $this->db->count_all_results('products');
    }

    /**
     * 取得精選商品
     *
     * 篩選條件：上架中（is_active = 1）且標記為精選（is_featured = 1）。
     * 供首頁精選商品區塊使用。
     *
     * @param  int   $limit 取得筆數上限（預設 8）
     * @return array        精選商品物件陣列（含 category_name）
     */
    public function get_featured($limit = 8) {
        return $this->db->select('p.*, c.name as category_name')
                        ->from('products p')
                        ->join('categories c', 'c.id = p.category_id')
                        ->where('p.is_active', 1)
                        ->where('p.is_featured', 1)
                        ->limit($limit)
                        ->get()->result();
    }

    /**
     * 依 ID 取得單一商品（含分類名稱）
     *
     * 不限制是否上架，後台編輯及前台詳情頁均使用；
     * 前台需額外判斷 is_active 狀態。
     *
     * @param  int        $id 商品 ID
     * @return object|null    找到時回傳商品物件（含 category_name），否則回傳 null
     */
    public function get_by_id($id) {
        return $this->db->select('p.*, c.name as category_name')
                        ->from('products p')
                        ->join('categories c', 'c.id = p.category_id')
                        ->where('p.id', $id)
                        ->get()->row();
    }

    /**
     * 取得商品的所有規格
     *
     * 每筆規格包含 size（尺寸）、color（顏色）與 stock（庫存）。
     *
     * @param  int   $product_id 商品 ID
     * @return array             規格物件陣列
     */
    public function get_variants($product_id) {
        return $this->db->where('product_id', $product_id)->get('product_variants')->result();
    }

    /**
     * 後台取得所有商品（含下架商品）
     *
     * 依建立時間倒序排列，JOIN categories 取得分類名稱。
     *
     * @return array 商品物件陣列（含 category_name）
     */
    public function get_all_admin() {
        return $this->db->select('p.*, c.name as category_name')
                        ->from('products p')
                        ->join('categories c', 'c.id = p.category_id')
                        ->order_by('p.created_at', 'DESC')
                        ->get()->result();
    }

    /**
     * 新增商品
     *
     * @param  array $data 商品資料（name, category_id, description, price 等）
     * @return int         新增成功後的商品 ID
     */
    public function insert($data) {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    /**
     * 新增商品規格
     *
     * @param  array $data 規格資料（product_id, size, color, stock）
     * @return bool        成功回傳 true
     */
    public function insert_variant($data) {
        return $this->db->insert('product_variants', $data);
    }

    /**
     * 更新商品資料
     *
     * @param  int   $id   商品 ID
     * @param  array $data 要更新的欄位與值
     * @return bool        成功回傳 true
     */
    public function update($id, $data) {
        return $this->db->where('id', $id)->update('products', $data);
    }

    /**
     * 刪除指定商品的所有規格
     *
     * 用於更新商品規格前先清除舊有資料，再重新寫入新規格。
     *
     * @param  int  $id 商品 ID
     * @return bool     成功回傳 true
     */
    public function delete_variants($id) {
        return $this->db->where('product_id', $id)->delete('product_variants');
    }

    /**
     * 刪除商品
     *
     * 注意：相關規格（product_variants）需確認已另行處理或透過資料庫外鍵級聯刪除。
     *
     * @param  int  $id 商品 ID
     * @return bool     成功回傳 true
     */
    public function delete($id) {
        return $this->db->where('id', $id)->delete('products');
    }

    /**
     * 切換商品上下架狀態
     *
     * 讀取目前 is_active 值後取反（1→0 或 0→1）。
     *
     * @param  int  $id 商品 ID
     * @return bool     成功回傳 true
     */
    public function toggle($id) {
        $p = $this->get_by_id($id);
        // is_active 為 1 則改為 0（下架），反之改為 1（上架）
        return $this->db->where('id', $id)->update('products', ['is_active' => $p->is_active ? 0 : 1]);
    }
}
