<?php
// Подключение к базе данных
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Открытие файла для чтения
$filename = "textfile.txt";
$file = fopen($filename, "r");

// Переменная для хранения порядкового номера слова в тексте
$word_order = 1;

// Считывание и запись каждого слова в базу данных
while (!feof($file)) {
    // Получение следующего слова из файла
    $word = trim(fgets($file));

    // Запрос для вставки слова и его порядкового номера в базу данных
    $sql = "INSERT INTO words (word, word_order) VALUES ('$word', $word_order)";

    // Выполнение запроса
    if ($conn->query($sql) === TRUE) {
        echo "Слово '$word' успешно записано в базу данных.\n";
    } else {
        echo "Ошибка записи слова '$word' в базу данных: " . $conn->error . "\n";
    }

    // Увеличение порядкового номера слова
    $word_order++;
}

// Закрытие файла и соединения с базой данных
fclose($file);
$conn->close();
?>
