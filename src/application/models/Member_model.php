<?php
/**
 * 會員資料模型
 *
 * 負責 members 資料表的查詢、註冊與驗證操作。
 * 密碼以 PHP 內建的 bcrypt（PASSWORD_BCRYPT）演算法進行雜湊後儲存，
 * 不以明文或其他弱雜湊方式保存。
 *
 * @package    CI3 Demo
 * @subpackage Models
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model {

    /**
     * 依 Email 查詢會員
     *
     * Email 在系統中作為登入帳號，具唯一性。
     *
     * @param  string     $email 會員 Email
     * @return object|null       找到時回傳會員物件，否則回傳 null
     */
    public function get_by_email($email) {
        return $this->db->where('email', $email)->get('members')->row();
    }

    /**
     * 依 ID 查詢會員
     *
     * @param  int        $id 會員 ID
     * @return object|null    找到時回傳會員物件，否則回傳 null
     */
    public function get_by_id($id) {
        return $this->db->where('id', $id)->get('members')->row();
    }

    /**
     * 會員註冊
     *
     * 在寫入資料庫前，將明文密碼以 bcrypt 雜湊，
     * 原始 $data 陣列中的 password 欄位會被覆寫為雜湊值。
     *
     * @param  array $data 會員資料（name, email, password, phone）
     * @return int         新增成功後的會員 ID
     */
    public function register($data) {
        // 儲存前將密碼轉為 bcrypt 雜湊，確保資料庫不存明文密碼
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->db->insert('members', $data);
        return $this->db->insert_id();
    }

    /**
     * 驗證會員帳號密碼
     *
     * 先以 Email 查詢會員資料，再用 password_verify() 比對雜湊密碼。
     *
     * @param  string      $email    會員 Email
     * @param  string      $password 明文密碼
     * @return object|false          驗證成功回傳會員物件，失敗回傳 false
     */
    public function verify($email, $password) {
        $m = $this->get_by_email($email);
        // 帳號存在且密碼比對正確才算驗證通過
        if ($m && password_verify($password, $m->password)) return $m;
        return false;
    }
}
