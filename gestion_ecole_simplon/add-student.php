<?php
require_once "config.php";
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $date_naissance = $_POST['date_naissance'];
    $telephone = $_POST['telephone'];
    $niveau = $_POST['niveau'];

    // Générer le matricule personnalisé
    $jour_mois_naissance = date('dm', strtotime($date_naissance)); // Format 'ddmm'
    $lettre_nom = strtoupper(substr($nom, 0, 1)); // Première lettre du nom
    $lettre_prenom = strtoupper(substr($prenom, 0, 1)); // Première lettre du prénom
    $chiffres_auto = sprintf("%04d", rand(0, 9999)); // Générer quatre chiffres aléatoires

    $matricule = 'ET' . $jour_mois_naissance . $lettre_nom . $lettre_prenom . $chiffres_auto;

    // Déterminer si l'étudiant est archivé ou non
    $archive = isset($_POST['archive']) ? 1 : 0;

    // Préparer la requête d'insertion
    $sql = "INSERT INTO etudiants (nom, prenom, email, date_naissance, telephone, niveau, matricule, archive) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Lier les variables à la requête
        mysqli_stmt_bind_param($stmt, "sssssssi", $nom, $prenom, $email, $date_naissance, $telephone, $niveau, $matricule, $archive);

        // Exécuter la requête
        if (mysqli_stmt_execute($stmt)) {
            // Redirection après succès
            header("Location: todo.php");
            exit();
        } else {
            echo "<p>Erreur lors de l'exécution de la requête : " . mysqli_error($link) . "</p>";
        }

        // Fermer la déclaration
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Erreur de préparation de la requête : " . mysqli_error($link) . "</p>";
    }

    // Fermer la connexion
    mysqli_close($link);
} else {
    header("Location: add-student.html");
    exit();
}
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
