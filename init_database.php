<?php
// Simple script to initialize the Railway database
require_once 'frontend/database/init_railway_db.php';

echo "🚀 Starting Railway Database Initialization...\n";
echo "==============================================\n";

try {
    init_railway_database();
    echo "\n🎉 SUCCESS! Your Railway database is now ready!\n";
    echo "You can now access your application at the Railway URLs.\n";
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Please check your environment variables and try again.\n";
}
