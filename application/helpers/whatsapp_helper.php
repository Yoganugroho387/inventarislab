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

if (!function_exists('send_whatsapp')) {
    /**
     * Send WhatsApp message using Fonnte API
     * @param string $target Target phone number (e.g. 089xxx or 6289xxx)
     * @param string $message Message content
     * @return array Response status and message
     */
    function send_whatsapp($target, $message) {
        $CI =& get_instance();
        $CI->load->model('setting_model');
        
        $token = $CI->setting_model->get_val('whatsapp_token', '');
        $enabled = $CI->setting_model->get_val('whatsapp_enabled', '0');

        if ($enabled !== '1' || empty($token)) {
            log_message('info', 'WhatsApp Notification skipped: Disabled or Token empty.');
            return ['status' => false, 'message' => 'WhatsApp disabled or token empty'];
        }

        // Standardize Indonesian numbers to 62 if starts with 0
        if (strpos($target, '0') === 0) {
            $target = '62' . substr($target, 1);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            log_message('error', 'Fonnte cURL Error: ' . $err);
            return ['status' => false, 'message' => $err];
        }

        $result = json_decode($response, true);
        if (isset($result['status']) && $result['status'] == true) {
            return ['status' => true, 'message' => 'Pesan terkirim'];
        }

        log_message('error', 'Fonnte API Error response: ' . $response);
        return ['status' => false, 'message' => isset($result['reason']) ? $result['reason'] : 'Unknown API error'];
    }
}
