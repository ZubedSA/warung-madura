<?php
require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

// Manually bootstrap Laravel for a quick check
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $tables = DB::connection('libsql')->select("SELECT name FROM sqlite_master WHERE type='table'");
    echo "Tables in Turso:\n";
    foreach ($tables as $table) {
        echo "- " . $table->name . "\n";
    }

    $userCount = DB::connection('libsql')->table('users')->count();
    echo "\nUser Count: " . $userCount . "\n";

} catch (\Exception $e) {
    echo "Error connecting to Turso: " . $e->getMessage() . "\n";
}
