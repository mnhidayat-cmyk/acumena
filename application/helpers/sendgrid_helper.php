<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kirim email via SendGrid REST API.
 *
 * @param string $to        Alamat email tujuan
 * @param string $subject   Subjek email
 * @param string $html      Konten HTML
 * @param string|null $text Konten plain text (opsional, akan dibuat otomatis jika null)
 * @param array $opts       Opsi tambahan:
 *   - from_email, from_name
 *   - cc (array), bcc (array)
 *   - reply_to (email)
 *   - attachments (array) : [['filename'=>'a.pdf','content'=>base64_encode($raw),'type'=>'application/pdf']]
 *   - unsubscribe_url (string) : untuk header List-Unsubscribe
 *   - headers (assoc) : custom headers
 *
 * @return array ['success'=>bool, 'http_code'=>int, 'result'=>string, 'error'=>string|null, 'message_id'=>string|null]
 */
function sendgrid_send($to, $subject, $html, $text = null, array $opts = [])
{
    $CI =& get_instance();
    $CI->config->load('sendgrid');
    $cfg = $CI->config->item('sendgrid');
    // Pastikan helper main (get_setting) tersedia
    $CI->load->helper('main');

    // Prioritaskan nilai dari tabel settings; fallback ke config/sendgrid.php
    $apiKey     = function_exists('get_setting') ? get_setting('sendgrid_api_key', $cfg['api_key'] ?? null) : ($cfg['api_key'] ?? null);
    $fromEmail  = isset($opts['from_email']) ? $opts['from_email'] : (function_exists('get_setting') ? get_setting('sendgrid_from_email', $cfg['from_email'] ?? null) : ($cfg['from_email'] ?? null));
    $fromName   = isset($opts['from_name'])  ? $opts['from_name']  : (function_exists('get_setting') ? get_setting('sendgrid_from_name', $cfg['from_name'] ?? null) : ($cfg['from_name'] ?? null));
    $endpoint   = $cfg['endpoint'];

    if (empty($apiKey)) {
        log_message('error', 'SendGrid API key not set (settings or config).');
        return [
            'success'    => false,
            'http_code'  => 0,
            'result'     => '',
            'error'      => 'Missing API key',
            'message_id' => null,
        ];
    }

    if (!$text) {
        // fallback text version (remove tags basic)
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
    }

    // personalizations
    $toList = [];
    if (is_array($to)) {
        foreach ($to as $t) $toList[] = ['email' => $t];
    } else {
        $toList[] = ['email' => $to];
    }

    $personalization = [
        'to' => $toList,
        'subject' => $subject
    ];

    if (!empty($opts['cc'])) {
        $personalization['cc'] = array_map(fn($e)=>['email'=>$e], (array)$opts['cc']);
    }
    if (!empty($opts['bcc'])) {
        $personalization['bcc'] = array_map(fn($e)=>['email'=>$e], (array)$opts['bcc']);
    }

    $payload = [
        'personalizations' => [$personalization],
        'from' => ['email' => $fromEmail, 'name' => $fromName],
        'content' => [
            ['type' => 'text/plain', 'value' => $text],
            ['type' => 'text/html',  'value' => $html]
        ],
    ];

    if (!empty($opts['reply_to'])) {
        $payload['reply_to'] = ['email' => $opts['reply_to']];
    }

    // List-Unsubscribe
    $headers = $opts['headers'] ?? [];
    if (!empty($opts['unsubscribe_url'])) {
        $headers['List-Unsubscribe'] = '<' . $opts['unsubscribe_url'] . '>';
    }
    if (!empty($headers)) {
        $payload['headers'] = $headers;
    }

    // Attachments
    if (!empty($opts['attachments'])) {
        // SendGrid minta base64 content
        // Format item: ['filename'=>'name.pdf','content'=>base64_string,'type'=>'application/pdf']
        $payload['attachments'] = $opts['attachments'];
    }

    // cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $endpoint,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT        => 15,
    ]);

    $result    = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErrNo = curl_errno($ch);
    $curlErr   = curl_error($ch);
    curl_close($ch);

    $success = ($httpCode === 202); // SendGrid sukses = 202 Accepted
    $messageId = null;

    // Ambil Message-ID dari header response kalau ada (kadang di-return pada beberapa libs)
    // Di endpoint ini tidak selalu ada, jadi optional.
    if ($success) {
        // leave as null (202 tanpa body)
    }

    return [
        'success'    => $success,
        'http_code'  => $httpCode,
        'result'     => (string)$result,
        'error'      => $curlErrNo ? $curlErr : null,
        'message_id' => $messageId,
    ];
}
