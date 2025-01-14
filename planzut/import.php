<?php
// Połączenie z bazą SQLite
$db = new PDO('sqlite:newuniversity.db');

// Wczytanie pliku JSON
$jsonFile = 'przefiltrowane_dane.json'; // Nazwa pliku JSON
$jsonData = json_decode(file_get_contents($jsonFile), true);

if (!$jsonData) {
    die("Nie udało się wczytać danych z pliku JSON.\n");
}

// Funkcja pomocnicza do dodawania danych do tabeli
function insertOrIgnore($db, $table, $data) {
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), "?"));
    $query = "INSERT OR IGNORE INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $db->prepare($query);
    $stmt->execute(array_values($data));
}

// Przetwarzanie danych z JSON-a
foreach ($jsonData as $wykladowca => $zajecia) {
    // Dodanie wykładowcy do tabeli "Wykładowca"
    $imieNazwisko = explode(" ", $wykladowca);
    $imie = $imieNazwisko[0];
    $nazwisko = $imieNazwisko[1];

    insertOrIgnore($db, 'Wykładowca', [
        'imie' => $imie,
        'nazwisko' => $nazwisko
    ]);

    // Pobranie ID wykładowcy
    $stmt = $db->prepare("SELECT id FROM Wykładowca WHERE imie = ? AND nazwisko = ?");
    $stmt->execute([$imie, $nazwisko]);
    $wykladowcaId = $stmt->fetchColumn();

    foreach ($zajecia as $zajecie) {
        // Dodanie sali do tabeli "Sala"
        $sala = $zajecie['room'] !== 'brak danych' ? $zajecie['room'] : null;
        $wydzial = $zajecie['wydział'] !== 'brak danych' ? $zajecie['wydział'] : null;

        if ($sala && $wydzial) {
            insertOrIgnore($db, 'Sala', [
                'identyfikator_sali' => $sala,
                'wydział' => $wydzial
            ]);

            // Pobranie ID sali
            $stmt = $db->prepare("SELECT id FROM Sala WHERE identyfikator_sali = ? AND wydział = ?");
            $stmt->execute([$sala, $wydzial]);
            $salaId = $stmt->fetchColumn();
        } else {
            $salaId = null; // Brak danych o sali
        }

        // Dodanie grupy do tabeli "Grupa"
        $grupa = $zajecie['group_name'] !== 'brak danych' ? $zajecie['group_name'] : null;
        if ($grupa) {
            insertOrIgnore($db, 'Grupa', [
                'identyfikator_grupy' => $grupa
            ]);
        }

        // Dodanie zajęć do tabeli "Zajęcia"
        insertOrIgnore($db, 'Zajęcia', [
            'nazwa_zajęć' => $zajecie['title'],
            'typ_zajęć' => $zajecie['lesson_status'],
            'data' => $zajecie['date'],
            'godzina_rozpoczęcia' => $zajecie['begin'],
            'godzina_zakończenia' => $zajecie['end'],
            'sala_id' => $salaId,
            'wykładowca_id' => $wykladowcaId,
            'identyfikator_grupy' => $grupa
        ]);
    }
}

echo "Dane zostały zaimportowane do bazy danych!\n";
?>
