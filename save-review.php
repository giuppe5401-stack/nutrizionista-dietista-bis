<?php
$DATA_DIR         = __DIR__ . '/data';
$STORAGE_FILE     = $DATA_DIR . '/reviews.jsonl';
$RATE_LIMIT_SEC   = 120;
$REDIRECT_ANCHOR  = '#lascia-recensione';
$MAX_TEXT_CHARS   = 800;
$MIN_TEXT_CHARS   = 30;
if (!is_dir($DATA_DIR)) { @mkdir($DATA_DIR, 0755, true); }
function respond_json($payload, $status = 200) { http_response_code($status); header('Content-Type: application/json; charset=utf-8'); echo json_encode($payload, JSON_UNESCAPED_UNICODE); exit; }
function redirect_back($anchor = '#') { $ref = $_SERVER['HTTP_REFERER'] ?? '/'; $url = parse_url($ref); $host = $_SERVER['HTTP_HOST'] ?? ''; if (!isset($url['host']) || $url['host'] !== $host) { $ref = '/'; } if ($anchor && strpos($ref, $anchor) === false) { $ref .= $anchor; } header('Location: ' . $ref); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'GET') { respond_json(['ok'=>true,'message'=>'save-review endpoint online'], 200); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { respond_json(['ok'=>false,'error':'method_not_allowed'], 405); }
function get_field($key) { return isset($_POST[$key]) ? trim((string)$_POST[$key]) : ''; }
if (get_field('company') !== '') { respond_json(['ok'=>false,'error':'spam_detected'], 400); }
$name    = mb_substr(strip_tags(get_field('name')), 0, 60);
$role    = mb_substr(strip_tags(get_field('role')), 0, 60);
$rating  = (int) get_field('rating');
$text    = mb_substr(strip_tags(get_field('text')), 0, $MAX_TEXT_CHARS);
$consent = isset($_POST['consent']) && $_POST['consent'] !== '';
if ($name === '' || $rating < 1 || $rating > 5 || $text === '' || mb_strlen($text) < $MIN_TEXT_CHARS || !$consent) { respond_json(['ok'=>false,'error':'validation_failed'], 422); }
$ip     = $_SERVER['REMOTE_ADDR']     ?? '';
$ua     = $_SERVER['HTTP_USER_AGENT'] ?? '';
$rlFile = $DATA_DIR . '/ratelimit_' . md5($ip) . '.txt';
$now = time();
if (is_file($rlFile)) { $last = (int) @file_get_contents($rlFile); if ($now - $last < $RATE_LIMIT_SEC) { $retry = $RATE_LIMIT_SEC - ($now - $last); respond_json(['ok'=>false,'error':'too_many_requests','retry_after'=>$retry], 429); } }
@file_put_contents($rlFile, (string)$now);
$record = ['created_at'=>date('c'),'name'=>$name,'role'=>$role?:'Cliente','rating'=>$rating,'text'=>$text,'ip_hash'=>substr(hash('sha256',$ip.'|'.$ua),0,16),'approved'=>true];
$fh = @fopen($STORAGE_FILE, 'a'); if (!$fh) { respond_json(['ok'=>false,'error':'storage_unavailable'], 500); }
flock($fh, LOCK_EX); fwrite($fh, json_encode($record, JSON_UNESCAPED_UNICODE) . PHP_EOL); flock($fh, LOCK_UN); fclose($fh);
$accept = $_SERVER['HTTP_ACCEPT'] ?? '';
if (stripos($accept, 'text/html') !== false) { redirect_back($REDIRECT_ANCHOR); }
respond_json(['ok'=>true]);
