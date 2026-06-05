<?php
/**
 * 前台購物車控制器
 *
 * 以 Session 作為購物車儲存媒介，提供：
 * - 顯示購物車內容
 * - 加入商品（AJAX JSON 回應）
 * - 更新商品數量
 * - 移除商品
 *
 * 購物車 key 格式：{product_id}_{size}_{color}，可區分不同規格的同款商品。
 *
 * @package    CI3 Demo
 * @subpackage Controllers/Front
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends Front_Controller {

    /**
     * 購物車列表頁
     *
     * 從 Session 讀取購物車資料，計算商品總金額後傳入 View。
     *
     * @return void
     */
    public function index() {
        $cart         = $this->session->userdata('cart') ?: [];
        $data['cart'] = $cart;
        // 以 price × quantity 加總計算總金額
        $data['total'] = array_sum(array_map(function ($i) {
            return $i['price'] * $i['quantity'];
        }, $cart));
        $this->render('frontend/cart/index', $data);
    }

    /**
     * 加入購物車（AJAX）
     *
     * 僅限已登入會員使用；未登入時回傳 JSON 錯誤並附上登入頁網址。
     * 相同商品規格（product_id + size + color）已存在時，累加數量；
     * 否則新建購物車項目。
     *
     * 回傳 JSON：
     * - success: bool
     * - message: string
     * - cart_count: int（成功時，購物車總件數）
     * - redirect: string（失敗且需跳轉時）
     *
     * @return void
     */
    public function add() {
        // 未登入會員不允許加入購物車
        if (!$this->session->userdata('member')) {
            echo json_encode(['success' => false, 'message' => '請先登入會員', 'redirect' => base_url('member/login')]);
            return;
        }

        $product_id = (int)$this->input->post('product_id');
        $size       = $this->input->post('size');
        $color      = $this->input->post('color');
        $quantity   = (int)($this->input->post('quantity') ?: 1);

        $product = $this->Product_model->get_by_id($product_id);
        if (!$product) {
            echo json_encode(['success' => false, 'message' => '商品不存在']);
            return;
        }

        $cart = $this->session->userdata('cart') ?: [];
        // 以「商品 ID_尺寸_顏色」為唯一鍵，區分不同規格
        $key = $product_id . '_' . $size . '_' . $color;

        if (isset($cart[$key])) {
            // 相同規格已在購物車中：累加數量
            $cart[$key]['quantity'] += $quantity;
        } else {
            // 全新規格：建立購物車項目
            $cart[$key] = [
                'product_id' => $product_id,
                'name'       => $product->name,
                'price'      => $product->price,
                'size'       => $size,
                'color'      => $color,
                'quantity'   => $quantity,
            ];
        }

        $this->session->set_userdata('cart', $cart);
        echo json_encode([
            'success'    => true,
            'cart_count' => array_sum(array_column($cart, 'quantity')),
            'message'    => '已加入購物車',
        ]);
    }

    /**
     * 更新購物車數量
     *
     * 依 POST 傳入的 key 找到對應商品規格，
     * 若數量 ≤ 0 則從購物車移除，否則更新數量。
     * 完成後跳轉回購物車頁。
     *
     * @return void
     */
    public function update() {
        $cart = $this->session->userdata('cart') ?: [];
        $key  = $this->input->post('key');
        $qty  = (int)$this->input->post('quantity');

        if ($qty <= 0) {
            unset($cart[$key]);        // 數量為 0 視為移除
        } else {
            $cart[$key]['quantity'] = $qty;
        }

        $this->session->set_userdata('cart', $cart);
        redirect('cart');
    }

    /**
     * 移除購物車商品
     *
     * 依 product_id 尋找並移除購物車中對應的第一筆項目，
     * 完成後跳轉回購物車頁。
     *
     * @param  int $product_id 要移除的商品 ID
     * @return void
     */
    public function remove($product_id) {
        $cart = $this->session->userdata('cart') ?: [];
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart[$key]);
                break;
            }
        }
        $this->session->set_userdata('cart', $cart);
        redirect('cart');
    }
}
