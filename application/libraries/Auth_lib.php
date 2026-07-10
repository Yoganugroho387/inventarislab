<?php
/**
 * ==========================================================================
 * Sistem Inventaris Laboratorium
 * ==========================================================================
 * 
 * Developed by : Yoga Nugroho
 * WhatsApp     : 089685027530
 * Support      : https://tako.id/YNGRHO
 * 
 * Open Jasa Pembuatan Website & Joki Tugas Website
 * 
 * NOTICE: Seluruh kode sumber pada sistem ini dilindungi hak cipta.
 * Dilarang keras menyalin, mendistribusikan, atau memodifikasi
 * tanpa izin tertulis dari pengembang.
 * ==========================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication Library for CI3
 */
class Auth_lib {
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Attempt login with username and password
     */
    public function login($username, $password) {
        $this->CI->db->select('users.*, roles.name as role_name');
        $this->CI->db->join('roles', 'roles.id = users.role_id');
        $this->CI->db->where('username', $username);
        $this->CI->db->where('is_active', 1);
        $user = $this->CI->db->get('users')->row_array();

        if ($user && password_verify($password, $user['password'])) {
            $this->CI->session->set_userdata([
                'user_id'   => $user['id'],
                'user_name' => $user['name'],
                'username'  => $user['username'],
                'role_id'   => $user['role_id'],
                'role_name' => $user['role_name'],
                'is_logged_in' => TRUE
            ]);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Destroy session and log out
     */
    public function logout() {
        $this->CI->session->sess_destroy();
    }

    /**
     * Check if user is logged in
     */
    public function is_logged_in() {
        return $this->CI->session->userdata('is_logged_in') === TRUE;
    }

    /**
     * Get current user data from session
     */
    public function user() {
        if (!$this->is_logged_in()) return NULL;
        return [
            'id'        => $this->CI->session->userdata('user_id'),
            'name'      => $this->CI->session->userdata('user_name'),
            'username'  => $this->CI->session->userdata('username'),
            'role_id'   => $this->CI->session->userdata('role_id'),
            'role_name' => $this->CI->session->userdata('role_name')
        ];
    }

    /**
     * Get current user ID
     */
    public function id() {
        return $this->CI->session->userdata('user_id');
    }

    /**
     * Get current user role name
     */
    public function role() {
        return $this->CI->session->userdata('role_name');
    }

    /**
     * Check if user has a specific role
     */
    public function check_role($required_role) {
        return $this->role() === $required_role;
    }
}
