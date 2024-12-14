<?php

try {
    // Ścieżka do pliku bazy danych
    $dbPath = 'nowa_baza.db';

    // Sprawdzenie, czy plik bazy danych istnieje i jego usunięcie
    if (file_exists($dbPath)) {
        unlink($dbPath); // Usunięcie pliku bazy danych
        echo "Baza danych została usunięta.<br>";
    }

    // Utworzenie (lub otwarcie) pliku bazy danych SQLite
    $pdo = new PDO("sqlite:" . $dbPath);

    // Ustawienie trybu błędów na wyjątki
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lista zapytań SQL do wykonania
    $sql = "
    CREATE TABLE IF NOT EXISTS Student (
        student_id INTEGER PRIMARY KEY AUTOINCREMENT,
        numer_albumu VARCHAR(8) UNIQUE NOT NULL
    );

    CREATE TABLE IF NOT EXISTS Grupa (
        grupa_id INTEGER PRIMARY KEY AUTOINCREMENT,
        nazwa VARCHAR(50) NOT NULL,
        numer VARCHAR(20) NOT NULL
    );

    CREATE TABLE IF NOT EXISTS Student_Grupa (
        student_id INTEGER,
        grupa_id INTEGER,
        PRIMARY KEY (student_id, grupa_id),
        FOREIGN KEY (student_id) REFERENCES Student(student_id) ON DELETE CASCADE,
        FOREIGN KEY (grupa_id) REFERENCES Grupa(grupa_id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS Wykladowca (
        wykladowca_id INTEGER PRIMARY KEY AUTOINCREMENT,
        imie VARCHAR(20) NOT NULL,
        nazwisko VARCHAR(50) NOT NULL
    );

    CREATE TABLE IF NOT EXISTS Sala (
        sala_id INTEGER PRIMARY KEY AUTOINCREMENT,
        numer VARCHAR(8) NOT NULL,
        wydzial VARCHAR(100),
        budynek VARCHAR(100) NOT NULL
    );

    CREATE TABLE IF NOT EXISTS Zajecia (
        zajecia_id INTEGER PRIMARY KEY AUTOINCREMENT,
        nazwa_kursu VARCHAR(100) NOT NULL,
        forma_zajec VARCHAR(30),
        data DATE NOT NULL,
        godzina TIME NOT NULL,
        sala_id INTEGER,
        wykladowca_id INTEGER,
        grupa_id INTEGER,
        FOREIGN KEY (sala_id) REFERENCES Sala(sala_id) ON DELETE SET NULL,
        FOREIGN KEY (wykladowca_id) REFERENCES Wykladowca(wykladowca_id) ON DELETE SET NULL,
        FOREIGN KEY (grupa_id) REFERENCES Grupa(grupa_id) ON DELETE CASCADE
    );";

    // Wykonanie zapytań SQL
    $pdo->exec($sql);

    echo "Baza danych i tabele zostały utworzone pomyślnie.";

} catch (PDOException $e) {
    echo "Błąd: " . $e->getMessage();
} finally {
    // Zamknięcie połączenia
    $pdo = null;
}
?>
