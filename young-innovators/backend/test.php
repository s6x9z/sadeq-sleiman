<?php
require_once __DIR__ . '/config.php';
try {
    db();
    echo "DB CONNECTED SUCCESSFULLY";
} catch (Throwable $e) {
    http_response_code(500);
    echo "DB ERROR: " . $e->getMessage();
}
