<?php
require_once "config.php";
session_start();

// Vérifie si l'utilisateur est connecté
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header("Location: login.php");
//     exit();
// }

$etudiant_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$etudiant = null;

// Récupérer les informations de l'étudiant sélectionné
if ($etudiant_id > 0) {
    $sql_etudiant = "SELECT nom, prenom FROM etudiants WHERE id = ?";
    if ($stmt_etudiant = mysqli_prepare($link, $sql_etudiant)) {
        mysqli_stmt_bind_param($stmt_etudiant, "i", $etudiant_id);
        mysqli_stmt_execute($stmt_etudiant);
        mysqli_stmt_bind_result($stmt_etudiant, $nom, $prenom);

        if (mysqli_stmt_fetch($stmt_etudiant)) {
            $etudiant = [
                'id' => $etudiant_id,
                'nom' => $nom,
                'prenom' => $prenom
            ];
        }

        mysqli_stmt_close($stmt_etudiant);
    }
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter des Notes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style css/ajout_noteform.css">
</head>
<body>
    <div class="containerx">
        <h1>Ajouter des Notes</h1>
        <?php if ($etudiant) : ?>
            <form action="list_notes.php" method="POST">
                <input type="hidden" name="etudiant_id" value="<?php echo htmlspecialchars($etudiant['id']); ?>">
                            <p class="etudiant-info">
                <i class="fas fa-user"></i>
                <strong>Nom :</strong> <?php echo htmlspecialchars($etudiant['nom']); ?>
            </p>
            <p class="etudiant-info">
                <i class="fas fa-user"></i>
                <strong>Prénom :</strong> <?php echo htmlspecialchars($etudiant['prenom']); ?>
            </p>

                <br>
                <label for="module1">Note Module 1 :</label>
                <input type="number" name="module1" id="module1" step="0.01" min="0" max="20">
                <br>
                <label for="module2">Note Module 2 :</label>
                <input type="number" name="module2" id="module2" step="0.01" min="0" max="20">
                <br>
                <label for="module3">Note Module 3 :</label>
                <input type="number" name="module3" id="module3" step="0.01" min="0" max="20">
                <br>
                <label for="module4">Note Module 4 :</label>
                <input type="number" name="module4" id="module4" step="0.01" min="0" max="20">
                <br>
                <button type="submit">Ajouter les Notes</button>
            </form>
        <?php else : ?>
            <p>Étudiant non trouvé.</p>
        <?php endif; ?>
        <a href="list_notes.php" class="button-link">Retour à la liste des notes</a>
    </div>
</body>
</html>


        
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
    </div>
</body>
</html>
