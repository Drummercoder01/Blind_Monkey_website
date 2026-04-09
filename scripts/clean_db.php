<?php
/**
 * clean_db.php — Clear all The 5AM data from the database
 * Run ONCE via: http://localhost/1.1-Blind-Monkey-site/scripts/clean_db.php
 * DELETE this file immediately after running!
 */
require("../code/initialisatie.inc.php");

echo "<h2>🧹 Blind Monkey — Database Cleanup</h2>";

$tables = ['t_videos', 't_music', 't_press', 't_events', 't_photos'];

foreach ($tables as $table) {
    try {
        $count = $_PDO->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        $_PDO->exec("DELETE FROM `$table`");
        // Reset auto increment
        $_PDO->exec("ALTER TABLE `$table` AUTO_INCREMENT = 1");
        echo "<p>✅ <strong>$table</strong> — $count rows deleted, AUTO_INCREMENT reset.</p>";
    } catch (PDOException $e) {
        echo "<p>⚠️ <strong>$table</strong> — " . $e->getMessage() . "</p>";
    }
}

echo "<h3 style='color:green'>✅ Done! Database is clean for Blind Monkey.</h3>";
echo "<p><strong style='color:red'>⚠️ DELETE this file now: scripts/clean_db.php</strong></p>";
?>
