<?php
require_once "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $etudiant_id = isset($_POST['etudiant_id']) ? intval($_POST['etudiant_id']) : null;
    $module1 = $_POST['module1'];
    $module2 = $_POST['module2'];
    $module3 = $_POST['module3'];
    $module4 = $_POST['module4'];
    

    if ($etudiant_id === null) {
        die("L'ID de l'étudiant est manquant.");
    }

    // Calculer la moyenne
    $moyenne = ($module1 + $module2 + $module3 + $module4) / 4;
    $statut_admission = $moyenne >= 10 ? 'admis' : 'recalé';

    // Vérifier si l'étudiant existe
    $sql_check_student = "SELECT id FROM etudiants WHERE id = ?";
    if ($stmt_check = mysqli_prepare($link, $sql_check_student)) {
        mysqli_stmt_bind_param($stmt_check, "i", $etudiant_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) == 0) {
            echo "L'étudiant avec l'ID $etudiant_id n'existe pas.";
            echo 'ID de l\'étudiant : ' . $etudiant_id; // Ajoutez ceci pour déboguer
            mysqli_stmt_close($stmt_check);
            mysqli_close($link);
            exit();
        }

        mysqli_stmt_close($stmt_check);
    } else {
        echo "Erreur lors de la préparation de la vérification de l'étudiant : " . mysqli_error($link);
        mysqli_close($link);
        exit();
    }

    // Préparer la requête d'ajout des notes
    $sql_note = "INSERT INTO notes (etudiant_id, module1, module2, module3, module4) VALUES (?, ?, ?, ?, ?)";
    if ($stmt_note = mysqli_prepare($link, $sql_note)) {
        mysqli_stmt_bind_param($stmt_note, "idddd", $etudiant_id, $module1, $module2, $module3, $module4);
        if (mysqli_stmt_execute($stmt_note)) {
            mysqli_stmt_close($stmt_note);
        } else {
            echo "Erreur lors de l'ajout des notes : " . mysqli_error($link);
            mysqli_close($link);
            exit();
        }
    } else {
        echo "Erreur lors de la préparation de la requête d'ajout des notes : " . mysqli_error($link);
        mysqli_close($link);
        exit();
    }

    // Mettre à jour le statut d'admission
    $sql_admission = "UPDATE etudiants SET statut_admission = ? WHERE id = ?";
    if ($stmt_admission = mysqli_prepare($link, $sql_admission)) {
        mysqli_stmt_bind_param($stmt_admission, "si", $statut_admission, $etudiant_id);
        if (mysqli_stmt_execute($stmt_admission)) {
            mysqli_stmt_close($stmt_admission);
            mysqli_close($link);
            header("Location: list_notes.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour du statut d'admission : " . mysqli_error($link);
            mysqli_close($link);
            exit();
        }
    } else {
        echo "Erreur lors de la préparation de la requête de mise à jour du statut : " . mysqli_error($link);
        mysqli_close($link);
        exit();
    }

} else {
    header("Location: ajout_noteform.php");
    exit();
}
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
