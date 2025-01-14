<?php
//PAMIĘTAĆ O EL FRAYU


$semesterEndDate = '2025-03-01'; // Replace with the actual end date of the semester

// Define the file paths
$wykladowcyFile = 'wykladowcy.json';
$daneFile = 'nowedane.json';
$blacklistFile = 'blacklista.txt';

// Try to delete the old file if it exists (wykladowcy.json)
if (file_exists($wykladowcyFile)) {
    unlink($wykladowcyFile);
}

// Enable error reporting for debugging
ini_set('display_errors', true);
ini_set('user_agent', 'Mozilla/5.0 (iPhone Simulator; U; CPU iPhone OS 4_3_2 like Mac OS X; en-us) AppleWebKit/535.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5');

// Initialize an empty array to store all items
$itemArray = [];

// Define the range of letters 'a' to 'z' for queries
$letters = array_merge(range('a', 'z'), ['ć', 'ł', 'ó', 'ś', 'ź', 'ż']);

// Loop through each letter from 'a' to 'z'
foreach ($letters as $letter) {
    // Build the URL for each query with a single letter
    $url = 'https://plan.zut.edu.pl/schedule.php?kind=teacher&query=' . $letter;

    // Print the query URL to the screen
    echo "Querying URL: $url\n";

    // Get the response from the URL
    $response = file_get_contents($url);

    // Decode the response as JSON
    $json = json_decode($response);

    // Extract 'item' values and add to the array if they exist
    foreach ($json as $entry) {
        if (isset($entry->item)) {
            $itemArray[] = $entry->item;
        }
    }
}

// Check for duplicates
$itemArrayUnique = array_unique($itemArray);

// If there were duplicates, print them
if (count($itemArray) != count($itemArrayUnique)) {
    echo "Znaleziono powtarzające się imie i nazwisko:\n";
    $duplicates = array_diff_assoc($itemArray, $itemArrayUnique);
    print_r($duplicates);
}

// Sort the array alphabetically
sort($itemArrayUnique);

// Convert the array to JSON and save it to the file (wykladowcy.json)
$output = json_encode($itemArrayUnique, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents($wykladowcyFile, $output);
echo "Dane wykładowców zostały zapisane do pliku $wykladowcyFile\n";

// Initialize an array to store schedules
$schedules = [];

// Read blacklisted teachers from the blacklista.txt file
$blacklist = file($blacklistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Remove teachers from the item array if they are on the blacklist
$itemArrayUnique = array_filter($itemArrayUnique, function($teacherName) use ($blacklist) {
    return !in_array($teacherName, $blacklist);
});

// Reindex the array after filtering
$itemArrayUnique = array_values($itemArrayUnique);


$itemArrayUnique = array_slice($itemArrayUnique,0,3);

// For each teacher in the item array, query their schedule and save it to dane.json
foreach ($itemArrayUnique as $teacherName) {
    $nameParts = explode(' ', $teacherName); // Split by space
    echo $nameParts[0].$nameParts[1]."\n";
    // Build the URL for the schedule
    $scheduleUrl = 'https://plan.zut.edu.pl/schedule_student.php?teacher=' . urlencode($nameParts[0] . ' ' . $nameParts[1]) . '&start=' . date("Y-m-d") . '&end=' . $semesterEndDate;
    // Get the schedule data
    $scheduleResponse = file_get_contents($scheduleUrl);
    if($scheduleResponse === [[]]){
        continue;
    }
    if($scheduleResponse === false)
    {
        $scheduleUrl = 'https://plan.zut.edu.pl/schedule_student.php?teacher=' . urlencode($nameParts[0] . ' ' . $nameParts[1]) . '&start=' . date("Y-m-d") . '&end=' . $semesterEndDate;
        // Get the schedule data
        $scheduleResponse = file_get_contents($scheduleUrl);
    }

    $schedules[$teacherName] = $scheduleResponse;

}
// Save the schedules to zajecia.json
file_put_contents($daneFile, json_encode($schedules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Dane zajęć zostały zapisane do pliku $daneFile\n";




// Tworzenie kopii zapasowej
$staredane = 'dane.json';
$nowedane = 'nowedane.json';
$backupDir = 'backup';     // Katalog backupowy
$date = date('Y-m-d_H-i-s');
$backupFile = $backupDir . '/backup_danych_' . $date . '.json';
/*
// Odczytaj zawartość pliku staredane
$staredaneContent = file_get_contents($staredane);

// Zapisz zawartość do backupu
file_put_contents($backupFile, $staredaneContent);

// Odczytaj zawartość nowedane
$nowedaneContent = file_get_contents($nowedane);

// Nadpisz staredane nową zawartością
file_put_contents($staredane, $nowedaneContent);*/

?>