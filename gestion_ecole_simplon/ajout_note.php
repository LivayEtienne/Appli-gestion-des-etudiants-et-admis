<?php
require_once "config.php";
session_start();

// Vérification de la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['etudiant_id'])) {
    $etudiant_id = $_POST['etudiant_id'];

    // Préparation de la requête SQL pour mettre à jour ou insérer les notes
    $sql_update = "UPDATE notes SET ";
    $params = [];
    $types = "";

    if (!empty($_POST['module1'])) {
        $sql_update .= "module1 = ?, ";
        $params[] = $_POST['module1'];
        $types .= "d";
    }
    if (!empty($_POST['module2'])) {
        $sql_update .= "module2 = ?, ";
        $params[] = $_POST['module2'];
        $types .= "d";
    }
    if (!empty($_POST['module3'])) {
        $sql_update .= "module3 = ?, ";
        $params[] = $_POST['module3'];
        $types .= "d";
    }
    if (!empty($_POST['module4'])) {
        $sql_update .= "module4 = ?, ";
        $params[] = $_POST['module4'];
        $types .= "d";
    }

    // Supprimer la virgule finale et ajouter la condition WHERE
    $sql_update = rtrim($sql_update, ", ") . " WHERE etudiant_id = ?";
    $params[] = $etudiant_id;
    $types .= "i";

    // Préparation de la requête SQL
    if ($stmt = mysqli_prepare($link, $sql_update)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        if (mysqli_stmt_execute($stmt)) {
            echo "Les notes ont été mises à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour des notes : " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur de préparation de la requête : " . mysqli_error($link);
    }
} else {
    echo "Aucun étudiant sélectionné ou données manquantes.";
}

mysqli_close($link);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'ajout des Notes</title>
    <link rel="stylesheet" href="style.css">
 
</head>
<body>
    <div class="containerx">
        <h1>Confirmation</h1>
        <p>Les notes ont été ajoutées avec succès.</p>
        <p><a href="list_notes.php" class="button-link">Retour à la liste des notes</a></p>
    </div>

    <script>
        // Temps d'inactivité avant la déconnexion (en millisecondes)
        const INACTIVITY_TIME = 60000; // 1 minute

        let inactivityTimer;
        let countdownTimer;
        let timeLeft = INACTIVITY_TIME;

        function startCountdown() {
            const countdownDisplay = document.getElementById('countdown');
            countdownTimer = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(countdownTimer);
                    logoutUser();
                    return;
                }

                const minutes = Math.floor(timeLeft / 60000);
                const seconds = Math.floor((timeLeft % 60000) / 1000);
                countdownDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                timeLeft -= 1000;
            }, 1000);
        }

        function resetTimer() {
            clearTimeout(inactivityTimer);
            clearInterval(countdownTimer);

            timeLeft = INACTIVITY_TIME;
            startCountdown();

            inactivityTimer = setTimeout(logoutUser, INACTIVITY_TIME);
        }

        function logoutUser() {
            window.location.href = './login.php'; // Ajustez cette URL en fonction de votre logique de déconnexion
        }

        function createTimerDisplay() {
            const timerDiv = document.createElement('div');
            timerDiv.id = 'timer-container';
            timerDiv.style.position = 'fixed';
            timerDiv.style.top = '0';
            timerDiv.style.right = '0';
            timerDiv.style.backgroundColor = '#f0f0f0';
            timerDiv.style.border = '1px solid #ccc';
            timerDiv.style.padding = '5px 10px';
            timerDiv.style.zIndex = '1000';
            timerDiv.style.fontFamily = 'Arial, sans-serif';
            timerDiv.style.fontSize = '14px';
            timerDiv.style.color = '#333';

            const icon = document.createElement('img');
            icon.src = 'time.png'; // Remplacez par le chemin vers votre icône d'horloge
            icon.style.width = '16px';
            icon.style.height = '16px';
            icon.style.verticalAlign = 'middle';
            icon.alt = 'Clock Icon';

            const countdownDisplay = document.createElement('span');
            countdownDisplay.id = 'countdown';
            countdownDisplay.textContent = '1:00'; // Initialisation à 1 minute

            timerDiv.appendChild(icon);
            timerDiv.appendChild(document.createTextNode(' '));
            timerDiv.appendChild(countdownDisplay);

            document.body.appendChild(timerDiv);
        }

        window.onload = function() {
            createTimerDisplay();
            resetTimer();

            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            document.ontouchstart = resetTimer; // Pour les appareils tactiles
            document.onchange = resetTimer;

            window.onfocus = resetTimer;
            window.onblur = function() {
                clearTimeout(inactivityTimer);
                clearInterval(countdownTimer);
            };
        };
    </script>
</body>
</html>
