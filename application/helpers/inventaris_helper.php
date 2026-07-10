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
 * Custom Helper Functions for Inventaris Lab
 */

if (!function_exists('esc')) {
    function esc($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($amount) {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        return site_url($path);
    }
}

if (!function_exists('activeClass')) {
    function activeClass($routePath, $activeClass = 'bg-teal-700 text-white font-medium', $defaultClass = 'text-slate-300 hover:bg-slate-800 hover:text-white') {
        $CI =& get_instance();
        $currentUri = $CI->uri->uri_string();
        $routePath = trim($routePath, '/');

        if ($currentUri === $routePath || (strpos($currentUri, $routePath . '/') === 0 && $routePath !== '')) {
            return $activeClass;
        }
        return $defaultClass;
    }
}

if (!function_exists('old')) {
    function old($field, $default = '') {
        $CI =& get_instance();
        $old = $CI->session->flashdata('old_input');
        if (is_array($old) && isset($old[$field])) {
            return $old[$field];
        }
        return $default;
    }
}

if (!function_exists('error')) {
    function error($field) {
        $CI =& get_instance();
        $errors = $CI->session->flashdata('form_errors');
        if (is_array($errors) && isset($errors[$field])) {
            return $errors[$field];
        }
        return NULL;
    }
}

if (!function_exists('old_value')) {
    function old_value($field, $default = '') {
        return old($field, $default);
    }
}

if (!function_exists('form_error_msg')) {
    function form_error_msg($field) {
        return error($field);
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        $CI =& get_instance();
        return '<input type="hidden" name="' . $CI->security->get_csrf_token_name() . '" value="' . $CI->security->get_csrf_hash() . '">';
    }
}

if (!function_exists('current_user')) {
    function current_user() {
        $CI =& get_instance();
        return $CI->auth_lib->user();
    }
}

if (!function_exists('has_role')) {
    function has_role($role) {
        $CI =& get_instance();
        return $CI->auth_lib->role() === $role;
    }
}

/**
 * Static Compatibility Wrappers for Legacy MVC Views
 */
class Auth {
    public static function user() {
        $CI =& get_instance();
        return $CI->auth_lib->user();
    }

    public static function role() {
        $CI =& get_instance();
        return $CI->auth_lib->role();
    }

    public static function id() {
        $CI =& get_instance();
        return $CI->auth_lib->id();
    }
}
