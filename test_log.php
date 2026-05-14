<?php
$lines = file('storage/logs/laravel.log');
for ($i = count($lines)-1; $i >= 0; $i--) {
    if (strpos($lines[$i], '[202') === 0) {
        echo $lines[$i];
        break;
    }
}
