<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

foreach(\App\Models\HeroSlide::all() as $s) {
    echo "ID: " . $s->id . " | MEDIA_URL: " . $s->media_url . " | IMAGE: " . $s->image . " | MEDIA_ATTR: " . $s->media . PHP_EOL;
}
