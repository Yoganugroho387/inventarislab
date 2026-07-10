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
 * Auth Hook - Middleware for route protection
 */
class Auth_hook {

    public function check_access() {
        $CI =& get_instance();
        $class = $CI->router->directory . $CI->router->class;
        $class = strtolower(trim($class, '/'));

        // Public routes that don't require authentication
        $public_routes = ['login', 'welcome', 'errors/page_404', 'verify'];
        foreach ($public_routes as $route) {
            if (strpos($class, $route) === 0) {
                return;
            }
        }

        // All other routes require login
        if (!$CI->auth_lib->is_logged_in()) {
            redirect('login');
            return;
        }

        // Check role-based access
        $role = $CI->auth_lib->role();

        if (strpos($class, 'admin/') === 0 && $role !== 'admin') {
            $CI->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect($role === 'staff' ? 'staff/dashboard' : 'login');
            return;
        }

        if (strpos($class, 'staff/') === 0 && $role !== 'staff') {
            $CI->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect($role === 'admin' ? 'admin/dashboard' : 'login');
            return;
        }
    }
}
