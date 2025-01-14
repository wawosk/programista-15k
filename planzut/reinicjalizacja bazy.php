<?php
// Tworzenie bazy danych SQLite

$db = new PDO('sqlite:newuniversity.db');
// Tworzenie tabel
$queries = [
    // Tabela "Wykładowca"
    "CREATE TABLE IF NOT EXISTS Wykładowca (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        imie TEXT NOT NULL,
        nazwisko TEXT NOT NULL
    )",

    // Tabela "Sala"
    "CREATE TABLE IF NOT EXISTS Sala (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        identyfikator_sali TEXT NOT NULL,
        wydział TEXT NOT NULL
    )",

    // Tabela "Grupa"
    "CREATE TABLE IF NOT EXISTS Grupa (
        identyfikator_grupy TEXT PRIMARY KEY
    )",

    // Tabela "Student"
    "CREATE TABLE IF NOT EXISTS Student (
        numer_albumu TEXT PRIMARY KEY
    )",

    // Tabela relacyjna "Student_Grupa"
    "CREATE TABLE IF NOT EXISTS Student_Grupa (
        numer_albumu TEXT NOT NULL,
        identyfikator_grupy TEXT NOT NULL,
        PRIMARY KEY (numer_albumu, identyfikator_grupy),
        FOREIGN KEY (numer_albumu) REFERENCES Student(numer_albumu),
        FOREIGN KEY (identyfikator_grupy) REFERENCES Grupa(identyfikator_grupy)
    )",

    // Tabela "Zajęcia"
    "CREATE TABLE IF NOT EXISTS Zajęcia (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nazwa_zajęć TEXT NOT NULL,
        typ_zajęć TEXT NOT NULL,
        data TEXT NOT NULL,
        godzina_rozpoczęcia TEXT NOT NULL,
        godzina_zakończenia TEXT NOT NULL,
        sala_id INTEGER NOT NULL,
        wykładowca_id INTEGER NOT NULL,
        identyfikator_grupy TEXT NOT NULL,
        FOREIGN KEY (sala_id) REFERENCES Sala(id),
        FOREIGN KEY (wykładowca_id) REFERENCES Wykładowca(id),
        FOREIGN KEY (identyfikator_grupy) REFERENCES Grupa(identyfikator_grupy)
    )"
];

// Wykonywanie zapytań
foreach ($queries as $query) {
    $db->exec($query);
}

echo "Baza danych została pomyślnie zainicjalizowana!";
?>
