<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Administrateur</title>
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
    <div class="container">
        <h1>Modifier Administrateur</h1>
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
            $sql = "SELECT * FROM administrateurs WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $id);

                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        ?>

                        <form action="update_admin.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                            <label for="nom">Nom:</label>
                            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($row['nom']); ?>" required>

                            <label for="prenom">Prénom:</label>
                            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($row['prenom']); ?>" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

                            <label for="mot_de_passe">Mot de passe:</label>
                            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Laissez vide pour ne pas changer">

                            <label for="role">Rôle:</label>
                            <select id="role" name="role" required>
                                <option value="superAdmin" <?php echo $row['role'] === 'superAdmin' ? 'selected' : ''; ?>>superAdmin</option>
                                <option value="admin" <?php echo $row['role'] === 'admin' ? 'selected' : ''; ?>>admin</option>
                            </select>

                            <button type="submit"><a href="todo_admin.php"></a>Mettre à jour</button>
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

