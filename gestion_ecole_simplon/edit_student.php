<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Etudiants</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .containerx {
            width: 80%;
            background: white;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
        }

        input, select {
            margin-top: 5px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="containerx">
        <h1>Modification Etudiant</h1>
        <?php
        require_once "config.php";
        session_start();

        // Vérifiez si l'utilisateur est connecté
        // if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        //     header("Location: login.html");
        //     exit();
        // }

        // Récupérer l'ID de l'administrateur à modifier
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $id = $_POST['id'];

            // Préparer la requête de sélection
            $sql = "SELECT * FROM etudiants WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $id);

                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        ?>

                        <form action="update_student.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                            <label for="nom">Nom:</label>
                            <input type="text" id="nom" name="nom" placeholder="Nom de l'étudiant" pattern="[A-Za-z\s]+" title="Lettres uniquement" value="<?php echo htmlspecialchars($row['nom']); ?> "required >

                            <label for="prenom">Prénom:</label>
                            <input type="text" id="prenom" name="prenom" placeholder="Prénom de l'étudiant" required pattern="[A-Za-z\s]+" title="Lettres uniquement" value=" <?php echo htmlspecialchars($row['prenom']); ?>">

                            <label for="date_naissance">Date de naissance:</label>
                            <input type="date" id="date_naissance" name="date_naissance" required value="<?php echo htmlspecialchars($row['date_naissance']); ?>">

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="Email de l'étudiant" required value="<?php echo htmlspecialchars($row['email']); ?>">

                            <label for="telephone">Téléphone:</label>
                            <input type="tel" id="telephone" name="telephone" placeholder="Téléphone de l'étudiant" required pattern="[0-9]+" title="Chiffres uniquement" value="<?php echo htmlspecialchars($row['telephone']); ?>">

                            <label for="niveau">Niveau:</label>
                            <select id="niveau" name="niveau" required>
                                <option value="L1" <?php echo ($row['niveau'] == 'L1') ? 'selected' : ''; ?>>L1</option>
                                <option value="L2" <?php echo ($row['niveau'] == 'L2') ? 'selected' : ''; ?>>L2</option>
                                <option value="L3" <?php echo ($row['niveau'] == 'L3') ? 'selected' : ''; ?>>L3</option>
                                <option value="M1" <?php echo ($row['niveau'] == 'M1') ? 'selected' : ''; ?>>M1</option>
                                <option value="M2" <?php echo ($row['niveau'] == 'M2') ? 'selected' : ''; ?>>M2</option>
                            </select>


                            <button type="submit">Mettre à jour</button>
                        </form>

                        <?php
                    } else {
                        echo "<p>Administrateur non trouvé.</p>";
                    }
                } else {
                    echo "<p>Erreur lors de la récupération des données : " . mysqli_error($link) . "</p>";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "<p>Erreur de préparation de la requête : " . mysqli_error($link) . "</p>";
            }
        } else {
            echo "<p>ID non spécifié ou invalide.</p>";
        }

        mysqli_close($link);
        ?>
    </div>
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

