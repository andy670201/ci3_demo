<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
}

// 前台基底
class Front_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
    }

    protected function render($view, $data = []) {
        $cart = $this->session->userdata('cart') ?: [];
        $data['cart_count'] = array_sum(array_column($cart, 'quantity'));
        $data['member']     = $this->session->userdata('member');
        $data['categories'] = $this->Category_model->get_all();
        $this->load->view('layouts/header', $data);
        $this->load->view($view, $data);
        $this->load->view('layouts/footer', $data);
    }
}

// 後台基底
class Admin_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }
        $this->load->model('Contact_model');
    }

    protected function render($view, $data = []) {
        $data['admin_name']   = $this->session->userdata('admin_name');
        $data['unread_count'] = $this->Contact_model->count_unread();
        $this->load->view('layouts/admin_header', $data);
        $this->load->view($view, $data);
        $this->load->view('layouts/admin_footer', $data);
    }
}
