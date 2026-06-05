<?php
/**
 * 商品分類資料模型
 *
 * 負責商品分類（categories 資料表）的查詢操作。
 * 分類以 sort_order 欄位控制顯示順序，
 * 以 slug 欄位作為前台 URL 識別名稱。
 *
 * @package    CI3 Demo
 * @subpackage Models
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    /**
     * 取得所有分類
     *
     * 依 sort_order 升序排列，供導覽選單或下拉選單使用。
     *
     * @return array 分類物件陣列
     */
    public function get_all() {
        return $this->db->order_by('sort_order')->get('categories')->result();
    }

    /**
     * 依 slug 查詢單一分類
     *
     * slug 為前台 URL 路徑識別名稱（如 /products/category/t-shirts）。
     *
     * @param  string $slug 分類 URL 識別名稱
     * @return object|null  找到時回傳分類物件，否則回傳 null
     */
    public function get_by_slug($slug) {
        return $this->db->where('slug', $slug)->get('categories')->row();
    }

    /**
     * 依 ID 查詢單一分類
     *
     * @param  int        $id 分類 ID
     * @return object|null    找到時回傳分類物件，否則回傳 null
     */
    public function get_by_id($id) {
        return $this->db->where('id', $id)->get('categories')->row();
    }
}
