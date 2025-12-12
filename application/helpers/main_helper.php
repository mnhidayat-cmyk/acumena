<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use GuzzleHttp\Client;

if (!function_exists('generate_uuid')) {
    function generate_uuid()
    {
        // Versi sederhana berbasis uniqid + random
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // versi 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // varian
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

if (!function_exists('generate_otp')) {
    function generate_otp(){
        $otp = rand(100000, 999999);
        return $otp;
    }
}

if (!function_exists('get_setting')) {
    function get_setting($name, $default = null)
    {
        $ci = get_instance();
        $ci->load->database();
        $query = $ci->db->get_where('settings', ['name' => $name]);
        
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row ? $row->value : $default;
        }
        
        return $default;
    }
}

if (!function_exists('generate_invoice_number')) {
    function generate_invoice_number()
    {
        // INVOICE NUMBER FORMAT: YYMM001 (cek invoice terakhir di user_subscription_history.invoice_number)
        // jika bulan baru, invoice number direset menjadi 001
        $ci = get_instance();
        $ci->load->database();
        $query = $ci->db->order_by('invoice_number', 'desc')->get('user_subscription_history');
        
        $current_year = date('y');
        $current_month = date('m');
        
        // Jika tidak ada data invoice sebelumnya, mulai dengan 001
        if ($query->num_rows() == 0) {
            return $current_year . $current_month . '001';
        }
        
        $last_invoice_row = $query->row();
        if (!$last_invoice_row || !isset($last_invoice_row->invoice_number)) {
            return $current_year . $current_month . '001';
        }
        
        $last_invoice_number = $last_invoice_row->invoice_number;
        
        // Validasi format invoice number (minimal 7 karakter: YYMM001)
        if (strlen($last_invoice_number) < 7) {
            return $current_year . $current_month . '001';
        }
        
        $last_invoice_number_year = substr($last_invoice_number, 0, 2);
        $last_invoice_number_month = substr($last_invoice_number, 2, 2);
        $last_invoice_number_number = substr($last_invoice_number, 4, 3);
        
        if ($last_invoice_number_year != $current_year || $last_invoice_number_month != $current_month) {
            $invoice_number = $current_year . $current_month . '001';
        } else {
            $invoice_number = $current_year . $current_month . sprintf('%03d', intval($last_invoice_number_number) + 1);
        }
        return $invoice_number;
    }
}

if (!function_exists('send_mail')) {
    function send_mail($to, $subject, $message, $from_email = 'anam@sevencols.com', $from_name = 'Acumena')
    {
        $CI =& get_instance();
        $CI->load->library('email');

        // Konfigurasi Mailjet SMTP
        // $config = [
        //     'protocol'      => 'smtp',
        //     'smtp_host'     => 'smtp.mailjet.com',
        //     'smtp_user'     => get_setting('mailjet_api_key'),     // ganti dengan API Key
        //     'smtp_pass'     => get_setting('mailjet_secret_key'),  // ganti dengan Secret Key
        //     'smtp_port'     => 587,
        //     'smtp_crypto'   => 'tls',
        //     'mailtype'      => 'html',
        //     'charset'       => 'utf-8',
        //     'newline'       => "\r\n",
        //     'crlf'          => "\r\n",
        //     'smtp_timeout'  => 10,
        //     'useragent'     => 'CI3-Mailjet'
        // ];

        // Konfigurasi Hostinger SMTP
        $config = [
            'protocol'      => 'smtp',
            'smtp_host'     => 'smtp.hostinger.com',
            'smtp_user'     => 'anam@sevencols.com',     // ganti dengan API Key
            'smtp_pass'     => 'Anamsukses12,',  // ganti dengan Secret Key
            'smtp_port'     => 587,
            'smtp_crypto'   => 'tls',
            'mailtype'      => 'html',
            'charset'       => 'utf-8',
            'newline'       => "\r\n",
            'crlf'          => "\r\n",
            'smtp_timeout'  => 10,
            'useragent'     => 'CI3-Hostinger'
        ];

        $CI->email->initialize($config);

        $CI->email->from($from_email, $from_name);
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);

        if ($CI->email->send()) {
            return true;
        } else {
            log_message('error', 'Mailjet send error: ' . $CI->email->print_debugger(['headers']));
            return false;
        }
    }
}

/**
 * Panggil Gemini dan pastikan output JSON sesuai schema.
 *
 * @param  string $prompt         Instruksi / data Anda ke model
 * @param  array  $schema         JSON Schema PHP-array (lihat contoh di bawah)
 * @param  string $model          Default: gemini-1.5-flash (hemat & cepat)
 * @param  float  $temperature    Default: 0.2 (lebih konsisten)
 * @param  int    $maxTokens      Default: 1200
 * @return array|false            Array hasil json_decode() atau false jika gagal
 */
function gemini_call_json(
        string $prompt,
        array $schema,
        string $model = 'gemini-2.5-flash',
        float $temperature = 0.2,
        int $maxTokens = 1500
    ) {
        $api_key = get_setting('gemini_api_key');
        if (!$api_key) {
            log_message('error', 'Gemini API key not found');
            return false;
        }
    
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$api_key}";
    
    // Pastikan Composer autoload aktif agar Guzzle tersedia
    if (!class_exists('\\GuzzleHttp\\Client')) {
        $autoloadCandidates = [
            APPPATH . '../vendor/autoload.php',
            defined('FCPATH') ? FCPATH . 'vendor/autoload.php' : null,
            APPPATH . 'vendor/autoload.php',
        ];
        foreach ($autoloadCandidates as $autoload) {
            if ($autoload && is_file($autoload)) {
                require_once $autoload;
                break;
            }
        }
    }

    $client = new Client([
        'timeout' => 20,
        'headers' => ['Content-Type' => 'application/json'],
    ]);

    // Payload dasar (JSON only via response_schema)
    $basePayload = [
        "contents" => [[
            "role"  => "user",
            "parts" => [["text" => $prompt]]
        ]],
        "generationConfig" => [
            "temperature"        => $temperature,
            "maxOutputTokens"    => $maxTokens,
            "response_mime_type" => "application/json",
            "response_schema"    => $schema
        ]
    ];

    // Kirim + parse (dengan 1x retry kalau JSON gagal)
    $attempts = 0;
    do {
        try {
            $response = $client->post($url, ['json' => $basePayload]);
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            // Cek blocked / safety
            if (isset($data['promptFeedback']['blockReason']) && $data['promptFeedback']['blockReason'] !== 'BLOCK_NONE') {
                log_message('error', 'Gemini Safety Block: ' . json_encode($data['promptFeedback']));
                return false;
            }

            // Ambil teks JSON dari kandidat
            $jsonText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if (!$jsonText) {
                // Jika kehabisan token, coba sekali lagi dengan prompt lebih ketat dan maxOutputTokens lebih kecil
                $finishReason = $data['candidates'][0]['finishReason'] ?? null;
                log_message('error', 'Gemini missing text parts: ' . $body);
                if ($attempts === 0) {
                    // Perketat instruksi dan kecilkan output tokens untuk menghindari MAX_TOKENS
                    $basePayload['contents'][0]['parts'][0]['text'] =
                        $prompt . "\n\nRules: Return ONLY JSON that matches the schema. No prose. No explanation.";
                    $currentMax = (int)($basePayload['generationConfig']['maxOutputTokens'] ?? $maxTokens);
                    $basePayload['generationConfig']['maxOutputTokens'] = max(300, (int)floor($currentMax * 0.7));
                } else {
                    // Fallback generic sesuai schema: kembalikan array kosong yang valid
                    if (is_array($schema)) {
                        if (isset($schema['properties']['pairs'])) {
                            return ['pairs' => []];
                        } elseif (isset($schema['properties']['strategies'])) {
                            return ['strategies' => []];
                        }
                    }
                    return false;
                }
                // lanjut ke retry
                $attempts++;
                continue;
            }

            // Harus JSON valid (karena sudah response_mime_type JSON)
            $parsed = json_decode($jsonText, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                return $parsed;
            }

            // Kalau masih tidak valid, log & retry sekali dengan instruksi lebih ketat
            if ($attempts === 0) {
                $basePayload['contents'][0]['parts'][0]['text'] =
                    $prompt . "\n\nRules: Return VALID JSON that matches the schema. No extra text.";
            } else {
                log_message('error', 'Gemini returned invalid JSON after retry: ' . substr($jsonText, 0, 500));
                return false;
            }

        } catch (\Throwable $e) {
            log_message('error', 'Gemini API request error: ' . $e->getMessage());
            if ($attempts > 0) return false;
        }
        $attempts++;
    } while ($attempts < 2);

    return false;
}

function gemini_call(){
 $api_key = get_setting('gemini_api_key');
    if (!$api_key) {
        log_message('error', 'Gemini API key not found');
        return false;
    }

    $model = 'gemini-2.5-flash'; // kamu bisa ganti model jika perlu
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$api_key}";

    $client = new \GuzzleHttp\Client([
        'timeout' => 10,
    ]);

    try {
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "contents" => [[
                    "role" => "user",
                    "parts" => [
                        ["text" => "Halo Gemini, apakah kamu bekerja dengan baik?"]
                    ]
                ]],
                "generationConfig" => [
                    "temperature" => 0.1,
                    "maxOutputTokens" => 1000
                ]
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            return $data['candidates'][0]['content']['parts'][0]['text'];
        }

        log_message('error', 'Gemini API response missing text: ' . $body);
        return false;

    } catch (\Throwable $e) {
        log_message('error', 'Gemini API request error: ' . $e->getMessage());
        return false;
    }
}

function gemini_test_call_resilient($model = 'gemini-2.5-flash') {
    $api_key = get_setting('gemini_api_key');
    if (!$api_key) {
        log_message('error', 'Gemini API key not found');
        return false;
    }

    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$api_key}";
    $client = new Client([
        'timeout'     => 15,
        'http_errors' => false,
    ]);

    // attempt configs
    $attempts = [
        [
            'prompt' => 'Halo Gemini, balas 1 kata saja: OK',
            'max_tokens' => 256
        ],
        [
            'prompt' => 'OK',
            'max_tokens' => 512
        ],
    ];

    foreach ($attempts as $i => $cfg) {
        try {
            $payload = [
                "contents" => [[
                    "role"  => "user",
                    "parts" => [["text" => $cfg['prompt']]]
                ]],
                "generationConfig" => [
                    "temperature"      => 0.1,
                    "maxOutputTokens"  => $cfg['max_tokens']
                ]
                // Optional (kalau ingin longgar):
                // "safetySettings" => [
                //   ["category"=>"HARM_CATEGORY_DANGEROUS_CONTENT","threshold"=>"BLOCK_NONE"],
                //   ["category"=>"HARM_CATEGORY_HATE_SPEECH","threshold"=>"BLOCK_NONE"],
                //   ["category"=>"HARM_CATEGORY_HARASSMENT","threshold"=>"BLOCK_NONE"],
                //   ["category"=>"HARM_CATEGORY_SEXUAL_CONTENT","threshold"=>"BLOCK_NONE"]
                // ]
            ];

            $t0 = microtime(true);
            $res = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
                'json' => $payload
            ]);
            $elapsed = round((microtime(true) - $t0) * 1000);

            $status = $res->getStatusCode();
            $body   = (string)$res->getBody();
            log_message('debug', "[Gemini attempt ".($i+1)."] HTTP {$status} in {$elapsed}ms; body(first300)=".substr($body,0,300));

            if ($status >= 400) {
                log_message('error', "[Gemini attempt ".($i+1)."] HTTP error {$status}: ".substr($body,0,500));
                continue; // coba attempt berikutnya
            }

            $data = json_decode($body, true);
            $finish = $data['candidates'][0]['finishReason'] ?? '';
            $text   = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            // Safety block?
            if (($data['promptFeedback']['blockReason'] ?? 'BLOCK_NONE') !== 'BLOCK_NONE') {
                log_message('error', '[Gemini] Safety Block: '.json_encode($data['promptFeedback']));
                continue;
            }

            // Kalau kena MAX_TOKENS / text kosong, coba attempt berikutnya
            if (!$text || $finish === 'MAX_TOKENS') {
                log_message('debug', "[Gemini attempt ".($i+1)."] finishReason={$finish}, text missing -> retry next attempt");
                continue;
            }

            // Berhasil
            log_message('debug', "[Gemini attempt ".($i+1)."] OK finishReason={$finish}");
            return $text;

        } catch (RequestException $e) {
            $respBody = $e->hasResponse() ? (string)$e->getResponse()->getBody() : '';
            log_message('error', '[Gemini] RequestException: '.$e->getMessage().' body='.substr($respBody,0,500));
        } catch (\Throwable $e) {
            log_message('error', '[Gemini] Throwable: '.$e->getMessage());
        }
    }

    // Semua attempt gagal
    log_message('error', '[Gemini] All attempts failed (MAX_TOKENS or no text)');
    return false;
}

// ---------------- OpenAI helper: JSON-only Chat Completions -----------------
if (!function_exists('openai_call_json')) {
    /**
     * Memanggil OpenAI Chat Completions dengan format respons JSON saja.
     * Mengembalikan array hasil json_decode() atau false jika gagal.
     */
    function openai_call_json($prompt, $schema = null, $model = 'gpt-4o-mini', $temperature = 0.2, $maxOutputTokens = 800) {
        // Ambil API key dari env atau dari tabel settings (config)
        $CI = function_exists('get_instance') ? get_instance() : null;
        $apiKey = getenv('OPENAI_API_KEY');
        if (!$apiKey && $CI && isset($CI->config)) {
            // gunakan helper get_setting jika tersedia
            if (function_exists('get_setting')) {
                $apiKey = get_setting('openai_api_key');
            } else {
                $apiKey = $CI->config->item('openai_api_key');
            }
        }
        if (!$apiKey) {
            log_message('error', 'OpenAI API key not set. Use env OPENAI_API_KEY atau settings.openai_api_key');
            return false;
        }

        // Susun pesan sistem & user
        $system = 'You are a structured JSON generator. Return ONLY JSON. No prose. No explanation.';
        $schemaJson = null;
        if (!empty($schema)) {
            $schemaJson = is_array($schema) ? json_encode($schema) : (string)$schema;
        }
        $userContent = ($schemaJson
            ? ("Strictly follow this JSON schema.\nSchema:\n" . $schemaJson . "\n\nTask:\n" . (string)$prompt)
            : ("Return only valid JSON.\nTask:\n" . (string)$prompt)
        );

        $payload = [
            'model' => $model,
            'temperature' => $temperature,
            'max_tokens' => $maxOutputTokens,
            // Paksa JSON object agar isi choices[0].message.content berupa string JSON
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user',   'content' => $userContent],
            ],
        ];

        try {
            $client = new Client([
                'base_uri' => 'https://api.openai.com',
                'timeout'  => 30,
            ]);

            // Attempt 1
            $res = $client->post('/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode((string)$res->getBody(), true);
            $content = $data['choices'][0]['message']['content'] ?? null;
            $finish  = $data['choices'][0]['finish_reason'] ?? null;

            // Coba decode langsung
            if (is_string($content)) {
                $out = json_decode($content, true);
                if (is_array($out)) return $out;
                // Coba ekstrak blok JSON jika ada teks ekstra
                $start = strpos($content, '{');
                $end   = strrpos($content, '}');
                if ($start !== false && $end !== false && $end > $start) {
                    $jsonStr = substr($content, $start, $end - $start + 1);
                    $out2 = json_decode($jsonStr, true);
                    if (is_array($out2)) return $out2;
                }
            }

            // Retry 1x dengan instruksi lebih ketat dan token lebih kecil
            $retryTokens = max(300, (int)floor($maxOutputTokens * 0.7));
            $payload['max_tokens'] = $retryTokens;
            $payload['messages'][0]['content'] = 'Return ONLY JSON strictly matching the schema. No markdown. No prose.';
            $payload['messages'][1]['content'] = ($schemaJson
                ? ("Schema:" . $schemaJson . "\n\n" . (string)$prompt)
                : ("Return only valid JSON.\n" . (string)$prompt)
            );

            $res2 = $client->post('/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data2 = json_decode((string)$res2->getBody(), true);
            $content2 = $data2['choices'][0]['message']['content'] ?? null;
            if (is_string($content2)) {
                $out = json_decode($content2, true);
                if (is_array($out)) return $out;
                $start = strpos($content2, '{');
                $end   = strrpos($content2, '}');
                if ($start !== false && $end !== false && $end > $start) {
                    $jsonStr = substr($content2, $start, $end - $start + 1);
                    $out2 = json_decode($jsonStr, true);
                    if (is_array($out2)) return $out2;
                }
            }

            log_message('error', 'OpenAI returned non-JSON or empty content');
            return false;

        } catch (\Throwable $e) {
            log_message('error', 'OpenAI call error: ' . $e->getMessage());
            return false;
        }
    }
}

// CURL API SUMOPOD
function sumopod_ai($prompt, $model = 'gpt-4o-mini', $temperature = 0.7, $max_token = 150)
{
    $apiKey = $CI->config->item('sumopod_api_key');
    
    $url = "https://ai.sumopod.com/v1/chat/completions";

    $data = [
        "model" => $model,
        "messages" => [
            [
                "role" => "user",
                "content" => $prompt
            ]
        ],
        "max_tokens" => $max_token,
        "temperature" => $temperature
    ];

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . $apiKey
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        echo $response;
    }

    curl_close($ch);
}

function sumopod_call_json($prompt, $schema = null, $model = 'gpt-4o-mini', $temperature = 0.2, $maxOutputTokens = 800) {
    $CI = function_exists('get_instance') ? get_instance() : null;
    $apiKey = getenv('SUMOPOD_API_KEY');
    if (!$apiKey && function_exists('get_setting')) {
        $apiKey = get_setting('sumopod_api_key');
    }
    if (!$apiKey && $CI && isset($CI->config)) {
        $apiKey = $CI->config->item('sumopod_api_key');
    }
    if (!$apiKey) {
        return false;
    }

    $client = new Client([
        'base_uri' => 'https://ai.sumopod.com',
        'timeout'  => 30,
    ]);

    $system = 'You are a structured JSON generator. Return ONLY JSON. No prose. No explanation.';
    $schemaJson = null;
    if (!empty($schema)) {
        $schemaJson = is_array($schema) ? json_encode($schema) : (string)$schema;
    }
    $userContent = ($schemaJson
        ? ("Strictly follow this JSON schema.\nSchema:\n" . $schemaJson . "\n\nTask:\n" . (string)$prompt)
        : ("Return only valid JSON.\nTask:\n" . (string)$prompt)
    );

    $payload = [
        'model' => $model,
        'temperature' => $temperature,
        'max_tokens' => $maxOutputTokens,
        'response_format' => ['type' => 'json_object'],
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user',   'content' => $userContent],
        ],
    ];

    try {
        $res = $client->post('/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'json' => $payload,
        ]);

        $data = json_decode((string)$res->getBody(), true);
        $content = $data['choices'][0]['message']['content'] ?? null;
        if (is_string($content)) {
            $out = json_decode($content, true);
            if (is_array($out)) return $out;
            $start = strpos($content, '{');
            $end   = strrpos($content, '}');
            if ($start !== false && $end !== false && $end > $start) {
                $jsonStr = substr($content, $start, $end - $start + 1);
                $out2 = json_decode($jsonStr, true);
                if (is_array($out2)) return $out2;
            }
        }

        $retryTokens = max(300, (int)floor($maxOutputTokens * 0.7));
        $payload['max_tokens'] = $retryTokens;
        $payload['messages'][0]['content'] = 'Return ONLY JSON strictly matching the schema. No markdown. No prose.';
        $payload['messages'][1]['content'] = ($schemaJson
            ? ("Schema:" . $schemaJson . "\n\n" . (string)$prompt)
            : ("Return only valid JSON.\n" . (string)$prompt)
        );

        $res2 = $client->post('/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'json' => $payload,
        ]);

        $data2 = json_decode((string)$res2->getBody(), true);
        $content2 = $data2['choices'][0]['message']['content'] ?? null;
        if (is_string($content2)) {
            $out = json_decode($content2, true);
            if (is_array($out)) return $out;
            $start = strpos($content2, '{');
            $end   = strrpos($content2, '}');
            if ($start !== false && $end !== false && $end > $start) {
                $jsonStr = substr($content2, $start, $end - $start + 1);
                $out2 = json_decode($jsonStr, true);
                if (is_array($out2)) return $out2;
            }
        }

        return false;
    } catch (\Throwable $e) {
        return false;
    }
}