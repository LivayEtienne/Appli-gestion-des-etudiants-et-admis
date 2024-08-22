<?php
require_once "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $etudiant_id = $_POST['id'];
    $module1 = $_POST['module1'];
    $module2 = $_POST['module2'];
    $module3 = $_POST['module3'];
    $module4 = $_POST['module4'];

    // Mettre à jour les notes de l'étudiant
    $sql_update = "UPDATE notes SET module1 = ?, module2 = ?, module3 = ?, module4 = ? WHERE etudiant_id = ?";
    if ($stmt = mysqli_prepare($link, $sql_update)) {
        mysqli_stmt_bind_param($stmt, "ddddi", $module1, $module2, $module3, $module4, $etudiant_id);

        if (mysqli_stmt_execute($stmt)) {
            // Redirection vers la liste des notes avec l'ID de l'étudiant comme paramètre GET
            header("Location: list_notes.php?etudiant_id=" . urlencode($etudiant_id));
            exit();
        } else {
            echo "<p>Erreur lors de la mise à jour : " . mysqli_stmt_error($stmt) . "</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Erreur lors de la préparation de la requête : " . mysqli_error($link) . "</p>";
    }

    mysqli_close($link);
} else {
    echo "<p>Données invalides.</p>";
}
?>

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<script>
        // temps d'inactivité avant la déconnexion (en millisecondes)
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
    // Effacer le timer précédent
    clearTimeout(inactivityTimer);
    clearInterval(countdownTimer);

    // Réinitialiser le temps restant
    timeLeft = INACTIVITY_TIME;
    startCountdown();

    // Redémarrer le timer
    inactivityTimer = setTimeout(logoutUser, INACTIVITY_TIME);
}

function logoutUser() {
    // Logique pour déconnecter l'utilisateur
    window.location.href = './login.php'; // Ajustez cette URL en fonction de votre logique de déconnexion
}

// Créer l'élément de timer dans la page
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
    resetTimer(); // Initialiser le timer lorsque la page est chargée

    // Événements qui réinitialisent le timer
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.ontouchstart = resetTimer; // Pour les appareils tactiles
    document.onchange = resetTimer;

    // Assurez-vous de réinitialiser le timer lorsque l'utilisateur revient sur la page après une absence
    window.onfocus = resetTimer;
    window.onblur = function() {
        clearTimeout(inactivityTimer); // Efface le timer lorsque la fenêtre n'est pas active
        clearInterval(countdownTimer);
    };
};
    </script>
</body>
</html>
