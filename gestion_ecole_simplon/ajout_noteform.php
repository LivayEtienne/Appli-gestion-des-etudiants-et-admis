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
$success_message = '';

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

// Traitement du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['etudiant_id'])) {
    $module1 = $_POST['module1'];
    $module2 = $_POST['module2'];
    $module3 = $_POST['module3'];
    $module4 = $_POST['module4'];

    // Validation des données
    if (is_numeric($module1) && is_numeric($module2) && is_numeric($module3) && is_numeric($module4)) {
        $sql = "INSERT INTO notes (etudiant_id, module1, module2, module3, module4) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                    module1 = VALUES(module1), 
                    module2 = VALUES(module2), 
                    module3 = VALUES(module3), 
                    module4 = VALUES(module4)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "idddd", $etudiant_id, $module1, $module2, $module3, $module4);
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Les notes ont été enregistrées avec succès.";
            } else {
                $success_message = "Erreur lors de l'enregistrement des notes.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $success_message = "Erreur lors de la préparation de la requête.";
        }
    } else {
        $success_message = "Veuillez entrer des valeurs numériques valides pour toutes les notes.";
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
    <style>
        /* Styles pour la modal */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            padding-top: 100px; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="containerx">
        <h1>Ajouter des Notes</h1>
        <?php if ($etudiant) : ?>
            <form action="ajout_noteform.php?id=<?php echo htmlspecialchars($etudiant['id']); ?>" method="POST">
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

    <?php if ($success_message) : ?>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p class="<?php echo strpos($success_message, 'succès') !== false ? 'success' : 'error'; ?>">
                <?php echo $success_message; ?>
            </p>
        </div>
    </div>
    <script>
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];

        // Afficher la modal
        modal.style.display = "block";

        // Fermer la modal en cliquant sur le bouton "x"
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Fermer la modal si l'utilisateur clique en dehors de la modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <?php endif; ?>

</body>
</html>
