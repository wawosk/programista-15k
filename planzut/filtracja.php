<?php
// Wczytaj dane z pliku JSON
$jsonFile = 'dane.json';

$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Definicja skrótów wydziałów i ich pełnych nazw
$departments = [
    'WNoŻiR' => 'Wydział Nauk o Żywności i Rybactwa',
    'WNoZiR' => 'Wydział Nauk o Żywności i Rybactwa',
    'WTMiT' => 'Wydział Techniki Morskiej i Transportu',
    'WBiIŚ' => 'Wydział Budownictwa i Inżynierii Środowiska',
    'WBiHZ' => 'Wydział Biotechnologii i Hodowli Zwierząt',
    'WKŚiR' => 'Wydział Kształtowania Środowiska i Rolnictwa',
    'WKSiR' => 'Wydział Kształtowania Środowiska i Rolnictwa',
    'WTiICh' => 'Wydział Technologii i Inżynierii Chemicznej',
    'WTiICH' => 'Wydział Technologii i Inżynierii Chemicznej',
    'WIMiM' => 'Wydział Inżynierii Mechanicznej i Mechatroniki',
    'WEkon' => 'Wydział Ekonomiczny',
    'WI' => 'Wydział Informatyki',
    'WE' => 'Wydział Elektryczny',
    'WA' => 'Wydział Architektury',
    'BMW' => 'Międzywydziałowe'
];

// Posortuj klucze wydziałów malejąco po długości
uksort($departments, function($a, $b) {
    return strlen($b) - strlen($a);
});

// Przygotowanie nowej struktury danych
$result = [];

foreach ($data as $workerName => $lectures) {
    $result[$workerName] = []; // Inicjalizuj tablicę dla każdego wykładowcy
    foreach ($lectures as $lecture) {
        if (isset($lecture['title'], $lecture['start'], $lecture['end'])) {
            // Wyciągnij datę i godziny z pól start i end
            $date = date('Y-m-d', strtotime($lecture['start']));
            $begin = date('H:i', strtotime($lecture['start']));
            $end = date('H:i', strtotime($lecture['end']));

            // Usuń pierwszy fragment w nawiasach () od końca w polu title
            $title = preg_replace('/\([^)]*\)(?!.*\([^)]*\))/', '', $lecture['title']);
            $title = trim($title);

            // Dodaj wydział na podstawie pola room
            $wydział = null;
            foreach ($departments as $short => $fullName) {
                if (strpos($lecture['room'] ?? '', $short) !== false) {
                    $wydział = $fullName;

                    // Usuń tylko pierwsze wystąpienie skrótu wydziału z pola room
                    $lecture['room'] = preg_replace('/' . preg_quote($short, '/') . '/', '', $lecture['room'], 1);
                    $lecture['room'] = trim($lecture['room']); // Usunięcie ewentualnych zbędnych spacji

                    break; // Zakończ, gdy znajdziesz pierwszy dopasowany wydział
                }
            }

            // Dodaj do wyników wymagane pola
            $result[$workerName][] = [
                'title' => $title,
                'date' => $date,
                'begin' => $begin,
                'end' => $end,
                'group_name' => $lecture['group_name'] ?? "brak danych",
                'room' => $lecture['room'] ?? "brak danych",
                'lesson_status' => $lecture['lesson_status'] ?? "brak danych",
                'wydział' => $wydział ?? "brak danych",
            ];
        }
    }
}

// Zapisz przetworzone dane do nowego pliku JSON
file_put_contents('przefiltrowane_dane.json', json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo "Dane zostały wyodrębnione i zapisane w pliku";
?>
