<?php
function trace(string $msg): void
{
    $line = date('Y-m-d H:i:s') . ' | ' . ($_SESSION['user'] ?? 'guest') . ' | ' . $msg . PHP_EOL;
    file_put_contents('trace.log', $line, FILE_APPEND);
}
?>
