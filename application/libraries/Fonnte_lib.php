<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fonnte_lib {

    // ganti dengan token Fonnte kamu
    private $token = 'aipuWq4B17osEqg21d1A';

    private $api_url = 'https://api.fonnte.com/send';

    public function kirim_otp($no_wa, $kode_otp)
    {
        // pastikan nomor dalam format internasional tanpa +
        // contoh: 6281234567890
        $target = preg_replace('/[^0-9]/', '', $no_wa);

        $message = "Kode OTP reset password Anda: *{$kode_otp}*\n\n"
                 . "Jangan berikan kode ini kepada siapa pun.\n"
                 . "Kode berlaku 5 menit.";

        $data = [
            'target'  => $target,
            'message' => $message,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_HTTPHEADER     => [
                "Authorization: ".$this->token
            ],
        ]);

        $result = curl_exec($ch);
        $err    = curl_error($ch);
        curl_close($ch);

        if ($err) {
            log_message('error', 'Fonnte error: '.$err);
            return false;
        }

        // kalau mau, bisa decode json-nya
        // $res = json_decode($result, true);

        return true;
    }
}
