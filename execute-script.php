<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $path = escapeshellarg($_POST['path']);
    $version = escapeshellarg($_POST['version']);
    $locale = escapeshellarg($_POST['locale']);

    // Kommandozeilenbefehl erstellen
    $command = "sh wp_switcher.sh $path $version $locale";

    // Skript ausführen
    exec($command, $output, $return_var);

    if ($return_var === 0) {
        echo implode("\n", $output);
    } else {
        http_response_code(500);
        echo "Fehler beim Ausführen des Skripts.";
    }
} else {
    http_response_code(405);
    echo "HTTP-Methode nicht erlaubt. Bitte verwenden Sie POST.";
}
?>