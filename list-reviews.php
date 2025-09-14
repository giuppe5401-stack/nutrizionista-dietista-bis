<?php
header('Content-Type: application/json; charset=utf-8');
$DATA_FILE = __DIR__ . '/data/reviews.jsonl';
$limit  = isset($_GET['limit'])  ? (int)$_GET['limit']  : 12;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
if ($limit < 1)  $limit = 12;
if ($limit > 200) $limit = 200;
if ($offset < 0) $offset = 0;
$items = []; $total = 0;
if (is_file($DATA_FILE)) {
  $lines = file($DATA_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $lines = array_reverse($lines); $total = count($lines);
  $slice = array_slice($lines, $offset, $limit);
  foreach ($slice as $line) { $row = json_decode($line, true); if (!$row) continue; if (isset($row['approved']) && !$row['approved']) continue; $items[] = $row; }
}
echo json_encode(['ok'=>true, 'reviews'=>$items, 'total'=>$total, 'offset'=>$offset, 'limit'=>$limit], JSON_UNESCAPED_UNICODE);
