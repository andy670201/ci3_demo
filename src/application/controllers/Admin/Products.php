<?php
/**
 * 後台商品管理控制器
 *
 * 提供管理員對商品進行完整的 CRUD 操作，
 * 以及管理商品規格（尺寸、顏色、庫存）與切換上下架狀態。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Admin
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller {

    /**
     * 建構式
     * 載入 Product_model 與 Category_model。
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
    }

    /**
     * 商品列表頁
     *
     * 取得所有商品（含下架商品）及分類列表並傳入 View。
     *
     * @return void
     */
    public function index() {
        $data['products']   = $this->Product_model->get_all_admin();
        $data['categories'] = $this->Category_model->get_all();
        $this->render('admin/products/index', $data);
    }

    /**
     * 新增商品表單頁
     *
     * 顯示空白新增表單，product 傳入 null、variants 傳入空陣列表示新建模式。
     *
     * @return void
     */
    public function create() {
        $this->render('admin/products/form', [
            'categories' => $this->Category_model->get_all(),
            'product'    => null,
            'variants'   => [],
        ]);
    }

    /**
     * 儲存新增商品
     *
     * 1. 寫入商品主資料（名稱、分類、描述、售價、原價、庫存、狀態旗標）。
     * 2. 逐筆寫入商品規格（尺寸 / 顏色 / 各自庫存）。
     * 完成後跳轉至列表頁。
     *
     * @return void
     */
    public function store() {
        // 寫入商品主資料並取得新商品 ID
        $id = $this->Product_model->insert([
            'name'           => $this->input->post('name'),
            'category_id'    => $this->input->post('category_id'),
            'description'    => $this->input->post('description'),
            'price'          => $this->input->post('price'),
            'original_price' => $this->input->post('original_price'),
            'stock'          => $this->input->post('stock'),
            'is_active'      => $this->input->post('is_active')   ? 1 : 0,
            'is_featured'    => $this->input->post('is_featured')  ? 1 : 0,
        ]);

        // 處理規格陣列（sizes / colors / variant_stocks 為同長度的平行陣列）
        $sizes  = $this->input->post('sizes')          ?: [];
        $colors = $this->input->post('colors')         ?: [];
        $stocks = $this->input->post('variant_stocks') ?: [];

        foreach ($sizes as $i => $size) {
            // 尺寸與顏色都有值才建立規格列
            if ($size && isset($colors[$i])) {
                $this->Product_model->insert_variant([
                    'product_id' => $id,
                    'size'       => $size,
                    'color'      => $colors[$i],
                    'stock'      => $stocks[$i] ?? 0,
                ]);
            }
        }

        $this->session->set_flashdata('success', '商品已新增');
        redirect('admin/products');
    }

    /**
     * 編輯商品表單頁
     *
     * 取得指定商品資料與其規格列表，傳入表單 View（編輯模式）。
     *
     * @param  int $id 商品 ID
     * @return void
     */
    public function edit($id) {
        $this->render('admin/products/form', [
            'product'    => $this->Product_model->get_by_id($id),
            'variants'   => $this->Product_model->get_variants($id),
            'categories' => $this->Category_model->get_all(),
        ]);
    }

    /**
     * 更新商品
     *
     * 1. 更新商品主資料。
     * 2. 先刪除舊有規格，再重新寫入表單送出的規格列。
     * 完成後跳轉至列表頁。
     *
     * @param  int $id 商品 ID
     * @return void
     */
    public function update($id) {
        $this->Product_model->update($id, [
            'name'           => $this->input->post('name'),
            'category_id'    => $this->input->post('category_id'),
            'description'    => $this->input->post('description'),
            'price'          => $this->input->post('price'),
            'original_price' => $this->input->post('original_price'),
            'stock'          => $this->input->post('stock'),
            'is_active'      => $this->input->post('is_active')   ? 1 : 0,
            'is_featured'    => $this->input->post('is_featured')  ? 1 : 0,
        ]);

        // 先清空舊規格，再依表單資料重新建立
        $this->Product_model->delete_variants($id);

        $sizes  = $this->input->post('sizes')          ?: [];
        $colors = $this->input->post('colors')         ?: [];
        $stocks = $this->input->post('variant_stocks') ?: [];

        foreach ($sizes as $i => $size) {
            if ($size && isset($colors[$i])) {
                $this->Product_model->insert_variant([
                    'product_id' => $id,
                    'size'       => $size,
                    'color'      => $colors[$i],
                    'stock'      => $stocks[$i] ?? 0,
                ]);
            }
        }

        $this->session->set_flashdata('success', '商品已更新');
        redirect('admin/products');
    }

    /**
     * 刪除商品
     *
     * 依 ID 刪除商品（相關規格應由資料庫外鍵或 Model 一併處理），
     * 完成後設定 Flash 訊息並跳轉至列表頁。
     *
     * @param  int $id 商品 ID
     * @return void
     */
    public function delete($id) {
        $this->Product_model->delete($id);
        $this->session->set_flashdata('success', '商品已刪除');
        redirect('admin/products');
    }

    /**
     * 切換上下架狀態
     *
     * 呼叫 Product_model::toggle() 將商品在「上架」與「下架」之間切換，
     * 完成後跳轉至列表頁。
     *
     * @param  int $id 商品 ID
     * @return void
     */
    public function toggle($id) {
        $this->Product_model->toggle($id);
        redirect('admin/products');
    }
}
