<?php

$files = ['scraping.php', 'filtracja.php', 'reinicjalizacja bazy.php', 'import.php'];

while (true) {
    foreach ($files as $file) {
        require_once $file; // Wczytuje pliki w podanej kolejności
    }

    // Ścieżki do plików bazy danych
    $old_db_file = 'university.db';
    $new_db_file = 'newuniversity.db';

    copy($new_db_file, $old_db_file);

    // Poczekaj godzinę (3600 sekund)
    sleep(3600);

}
