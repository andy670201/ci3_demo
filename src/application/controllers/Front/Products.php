<?php
/**
 * 前台商品控制器
 *
 * 提供前台商品列表（全部 / 依分類）及商品詳情頁。
 * 列表頁支援分頁，只顯示上架中（is_active = 1）的商品。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Front
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Front_Controller {

    /**
     * 全部商品列表頁（支援分頁）
     *
     * 不限分類，每頁顯示 12 筆；透過 GET 參數 page 控制頁碼。
     * current_category 傳入 null 供 View 判斷目前未篩選任何分類。
     *
     * @return void
     */
    public function index() {
        $page     = (int)($this->input->get('page') ?: 1);
        $per_page = 12;

        $data['products']         = $this->Product_model->get_active(null, $per_page, ($page - 1) * $per_page);
        $data['total']            = $this->Product_model->count_active();
        $data['page']             = $page;
        $data['per_page']         = $per_page;
        $data['current_category'] = null;
        $this->render('frontend/products/index', $data);
    }

    /**
     * 依分類篩選商品列表頁（支援分頁）
     *
     * 依分類 slug 查詢分類資料，若分類不存在顯示 404。
     * 篩選結果同樣支援分頁，每頁 12 筆。
     *
     * @param  string $slug 分類網址識別名稱
     * @return void
     */
    public function category($slug) {
        $category = $this->Category_model->get_by_slug($slug);
        if (!$category) show_404();

        $page     = (int)($this->input->get('page') ?: 1);
        $per_page = 12;

        $data['category']         = $category;
        $data['products']         = $this->Product_model->get_active($category->id, $per_page, ($page - 1) * $per_page);
        $data['total']            = $this->Product_model->count_active($category->id);
        $data['page']             = $page;
        $data['per_page']         = $per_page;
        $data['current_category'] = $slug;
        $this->render('frontend/products/index', $data);
    }

    /**
     * 商品詳情頁
     *
     * 依 ID 查詢商品；若商品不存在或已下架則顯示 404。
     * 同時取得該商品的所有規格（size / color），
     * 以及相同分類下最多 4 件相關商品供推薦顯示。
     *
     * @param  int $id 商品 ID
     * @return void
     */
    public function detail($id) {
        $product = $this->Product_model->get_by_id($id);
        // 商品不存在或已下架均顯示 404
        if (!$product || !$product->is_active) show_404();

        $data['product']  = $product;
        $data['variants'] = $this->Product_model->get_variants($id);
        // 取同分類最多 4 件商品作為相關推薦
        $data['related']  = $this->Product_model->get_active($product->category_id, 4);
        $this->render('frontend/products/detail', $data);
    }
}
