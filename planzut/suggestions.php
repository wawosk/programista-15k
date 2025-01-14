<?php
// Odbieramy dane JSON z POST
$inputData = json_decode(file_get_contents('php://input'), true);

// Tworzymy połączenie z bazą danych SQLite
$db = new PDO('sqlite:newuniversity.db');

header('Content-Type: application/json; charset=utf-8');

try {
    // Podstawowe zapytanie bez filtrów
    $basequery = "
        SELECT 
            Zajęcia.id AS zajęcia_id,
            Zajęcia.nazwa_zajęć AS nazwazajec,
            Zajęcia.typ_zajęć AS typzajec,
            Zajęcia.data AS data,
            Zajęcia.godzina_rozpoczęcia,
            Zajęcia.godzina_zakończenia,
            Sala.identyfikator_sali AS id_sali,
            Sala.wydział AS wydzial, 
            Wykładowca.imie AS wykładowca_imie,
            Wykładowca.nazwisko AS wykładowca_nazwisko,
            Grupa.identyfikator_grupy AS id_grupy,
            Student.numer_albumu AS numeralbumu
        FROM 
            Zajęcia
        JOIN 
            Sala ON Zajęcia.sala_id = Sala.id
        JOIN 
            Wykładowca ON Zajęcia.wykładowca_id = Wykładowca.id
        JOIN 
            Grupa ON Zajęcia.identyfikator_grupy = Grupa.identyfikator_grupy
        LEFT JOIN 
            Student_Grupa ON Grupa.identyfikator_grupy = Student_Grupa.identyfikator_grupy
        LEFT JOIN 
            Student ON Student_Grupa.numer_albumu = Student.numer_albumu
    ";

    // Odbieramy filtry i wybranepole
    $filtry = [];
    $wpisanedane = [];
    $wybranepole = '';

    // Sprawdzamy, czy dane zostały przesłane z frontendu
    if (isset($inputData['wybranepole'])) {
        $wybranepole = $inputData['wybranepole'];
        unset($inputData['wybranepole']);
    }

    // Przypisujemy klucze i wartości do zmiennych filtry i wpisanedane
    foreach ($inputData as $key => $value) {
        $filtry[] = $key;
        $wpisanedane[] = $value;
    }

    // Zmienna na warunki WHERE
    $conditions = [];

    // Budowanie warunków filtrów
    if (count($filtry) == 1) {
        switch ($filtry[0]) {
            case "wykladowca":
                $conditions[] = "Wykładowca.imie || ' ' || Wykładowca.nazwisko LIKE '%" . $wpisanedane[0] . "%'";
                break;
            case "sala":
                $conditions[] = "id_sali LIKE '%" . $wpisanedane[0] . "%'";
                break;
            case "Student":
                $conditions[] = "Student.numer_albumu LIKE '%" . $wpisanedane[0] . "%'";
                break;
            case "przedmiot":
                $conditions[] = "Zajęcia.nazwa_zajęć LIKE '%" . $wpisanedane[0] . "%'";
                break;
            case "grupa":
                $conditions[] = "Grupa.identyfikator_grupy LIKE '%" . $wpisanedane[0] . "%'";
                break;
            case "forma":
                $conditions[] = "Zajęcia.typ_zajęć LIKE '%" . $wpisanedane[0] . "%'";
                break;
            case "wydzial":
                $conditions[] = "wydzial LIKE '%" . $wpisanedane[0] . "%'";
                break;
        }
    } else if (count($filtry) > 1) {
        // Dynamiczne dodawanie warunków dla wielu filtrów
        foreach ($filtry as $index => $filter) {
            if ($index < count($wpisanedane)) {
                switch ($filter) {
                    case "wykladowca":
                        $conditions[] = "Wykładowca.imie || ' ' || Wykładowca.nazwisko LIKE '%" . $wpisanedane[$index] . "%'";
                        break;
                    case "sala":
                        $conditions[] = "id_sali LIKE '%" . $wpisanedane[$index] . "%'";
                        break;
                    case "Student":
                        $conditions[] = "Student.numer_albumu LIKE '%" . $wpisanedane[$index] . "%'";
                        break;
                    case "przedmiot":
                        $conditions[] = "Zajęcia.nazwa_zajęć LIKE '%" . $wpisanedane[$index] . "%'";
                        break;
                    case "grupa":
                        $conditions[] = "Grupa.identyfikator_grupy LIKE '%" . $wpisanedane[$index] . "%'";
                        break;
                    case "forma":
                        $conditions[] = "Zajęcia.typ_zajęć LIKE '%" . $wpisanedane[$index] . "%'";
                        break;
                    case "wydzial":
                        $conditions[] = "wydzial LIKE '%" . $wpisanedane[$index] . "%'";
                        break;
                }
            }
        }
    }

    // Tworzenie zapytania z warunkami WHERE
    if (count($conditions) > 0) {
        $basequery .= " WHERE " . implode(" AND ", $conditions);
    }

    // Modyfikacja zapytania na wybór jednej kolumny
    switch ($wybranepole) {
        case "wykladowca":
            $basequery = "SELECT DISTINCT Wykładowca_imie || ' ' || Wykładowca_nazwisko AS wykladowca FROM (" . $basequery . ")";
            break;
        case "sala":
            $basequery = "SELECT DISTINCT id_sali AS sala FROM (" . $basequery . ")";
            break;
        case "Student":
            $basequery = "SELECT DISTINCT Student.numer_albumu AS student FROM (" . $basequery . ")";
            break;
        case "przedmiot":
            $basequery = "SELECT DISTINCT nazwazajec AS przedmiot FROM (" . $basequery . ")";
            break;
        case "grupa":
            $basequery = "SELECT DISTINCT id_grupy AS grupa FROM (" . $basequery . ")";
            break;
        case "forma":
            $basequery = "SELECT DISTINCT typzajec AS forma FROM (" . $basequery . ")";
            break;
        case "wydzial":
            $basequery = "SELECT DISTINCT wydzial AS wydzial FROM (" . $basequery . ")";
            break;
    }

    // Wykonywanie zapytania
    $stmt = $db->query($basequery);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo json_encode($results);
    } else {
        echo json_encode(["message" => "Brak wyników."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Błąd zapytania: " . $e->getMessage()]);
}

?>