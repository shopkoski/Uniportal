<?php
// Simple script to check if data is in Railway MySQL
require_once 'frontend/database/config.php';

echo "🔍 Checking Railway MySQL Database...\n";
echo "=====================================\n";

try {
    $pdo = get_pdo();
    
    // Check if we can connect
    echo "✅ Connected to database successfully!\n";
    
    // List all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "📊 Tables found: " . count($tables) . "\n";
    
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    
    // Check Users table specifically
    if (in_array('Users', $tables)) {
        $userCount = $pdo->query("SELECT COUNT(*) FROM Users")->fetchColumn();
        echo "👥 Users in database: $userCount\n";
        
        if ($userCount > 0) {
            $users = $pdo->query("SELECT username, role FROM Users LIMIT 5")->fetchAll();
            echo "📋 Sample users:\n";
            foreach ($users as $user) {
                echo "  - {$user['username']} ({$user['role']})\n";
            }
        }
    }
    
    // Check Students table
    if (in_array('Students_Table_1', $tables)) {
        $studentCount = $pdo->query("SELECT COUNT(*) FROM Students_Table_1")->fetchColumn();
        echo "🎓 Students in database: $studentCount\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
