<?php
/**
 * 管理員資料模型
 *
 * 負責管理員帳號相關的資料庫操作。
 * 目前提供帳號密碼驗證功能，密碼以 bcrypt 雜湊儲存。
 *
 * @package    CI3 Demo
 * @subpackage Models
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    /**
     * 驗證管理員帳號密碼
     *
     * 依 username 查詢管理員資料，
     * 再以 password_verify() 比對 bcrypt 雜湊密碼。
     *
     * @param  string $username 管理員帳號
     * @param  string $password 明文密碼（由此方法進行雜湊比對）
     * @return object|false     驗證成功回傳管理員資料物件，失敗回傳 false
     */
    public function verify($username, $password) {
        $admin = $this->db->where('username', $username)->get('admins')->row();
        // password_verify 比對明文與資料庫中的 bcrypt 雜湊值
        if ($admin && password_verify($password, $admin->password)) return $admin;
        return false;
    }
}
