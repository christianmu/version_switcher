<?php
$message = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_version = $_POST['php_version'];
    $available_versions = ['php5.6', 'php7.4', 'php8.1', 'php8.2', 'php8.3'];

    if (in_array($selected_version, $available_versions)) {
        // Deaktivieren aller PHP-Module
        foreach ($available_versions as $version) {
            shell_exec("sudo a2dismod $version 2>&1");
        }
        // Aktivieren des ausgewählten PHP-Moduls
        shell_exec("sudo a2enmod $selected_version 2>&1");

        // Apache-Neustart im Hintergrund ausführen
        shell_exec("nohup sudo systemctl restart apache2 > /dev/null 2>&1 &");

        // Erfolgsmeldung erstellen
        $message = "PHP-Version erfolgreich gewechselt zu <strong>$selected_version</strong>. <br>
                    Der Apache-Webserver wurde neu gestartet.";
    } else {
        $message = "Ungültige PHP-Version ausgewählt.";
    }
}

$current_version = shell_exec("php -v | grep -o '^PHP [0-9]\.[0-9]' | head -1");
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>PHP-Version wechseln</title>
	<style>
	* {
		box-sizing: border-box;
	}

	body {
		font-family: Arial, sans-serif;
		margin: 20px;
		padding: 0;
	}

	h1 {
		color: #253043;
	}


	p {
		max-width: 500px;
		margin: auto;
		text-align: center;
		font-size: 16px;
		color: green;
	}

	.success {
		color: #4a5054;
		font-weight: bold;
	}

	.error {
		color: red;
		font-weight: bold;
	}

	.button-container {
		display: flex;
		justify-content: center;
		gap: 20px;
		max-width: 500px;
		margin: auto;
		margin-top: 20px;
	}

	button {
		background-color: #253043;
		color: white;
		border: none;
		padding: 10px 20px;
		font-size: 16px;
		cursor: pointer;
	}

	button:hover {
		background-color: #445566;
	}
	</style>
</head>

<body>

	<?php if (!empty($message)): ?>
	<p class="<?php echo strpos($message, 'erfolgreich') !== false ? 'success' : 'error'; ?>">
		<?php echo $message; ?>
	</p>
	<?php endif; ?>


	<div class="button-container">
		<!-- Button zum Öffnen der info.php -->
		<form action="http://localhost/tools/version_switcher/phpinfo.php" method="get" target="_blank">
			<button type="submit">phpinfo.php öffnen</button>
		</form>
		<!-- Button zum Öffnen der index.html -->
		<form action="http://localhost/tools/version_switcher/" method="get">
			<button type="submit">Erneuter Versionswechsel</button>
		</form>
	</div>
</body>

</html>