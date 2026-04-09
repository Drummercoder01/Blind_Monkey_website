<?php
/**
 * seed_music.php — Run once to insert Blind Monkey tracks into the DB
 * Access via: http://localhost/1.1-Blind-Monkey-site/scripts/seed_music.php
 * DELETE this file after running it!
 */
require("../code/initialisatie.inc.php");

$songs = [
    [
        'song_name' => 'Run Man',
        'embed'     => '<iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/6p6fkqkQplLXNOPAw4aKf7?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>',
        'song_order' => 1,
        'active'    => 1
    ],
    [
        'song_name' => 'Wake Up!',
        'embed'     => '<iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/4PNYz4fHHRiISmNZSifgvV?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>',
        'song_order' => 2,
        'active'    => 1
    ],
    [
        'song_name' => 'Hanxiety',
        'embed'     => '<iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/0a7YpuNhb0OeAehuHLWIjO?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>',
        'song_order' => 3,
        'active'    => 1
    ],
];

echo "<h2>🎵 Blind Monkey — Music Seeder</h2>";

try {
    // Clear existing songs first
    $clear = $_PDO->prepare("DELETE FROM t_music");
    $clear->execute();
    echo "<p>✅ Old songs cleared.</p>";

    // Insert new songs
    $insert = $_PDO->prepare("
        INSERT INTO t_music (song_name, embed, song_order, active)
        VALUES (:song_name, :embed, :song_order, :active)
    ");

    foreach ($songs as $song) {
        $insert->execute($song);
        echo "<p>✅ Added: <strong>{$song['song_name']}</strong></p>";
    }

    echo "<h3 style='color:green'>✅ Done! All songs inserted.</h3>";
    echo "<p><strong>⚠️ Delete this file now: scripts/seed_music.php</strong></p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";

    // Try to show the actual table columns
    try {
        $cols = $_PDO->query("DESCRIBE t_music")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Table columns:</h3><pre>" . print_r($cols, true) . "</pre>";
    } catch(Exception $ex) {
        echo "<p>Could not describe table: " . $ex->getMessage() . "</p>";
    }
}
?>
