<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style css/bulletin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Bulletin de Notes</title>
</head>
<body>
    <div class="bulletin-container">
    <?php
    require_once "config.php";
    session_start();

    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.html");
        exit();
    }

    // Vérifie si l'ID de l'étudiant a été fourni
    if (!isset($_GET['id'])) {
        echo "ID d'étudiant manquant.";
        exit();
    }

    $id = $_GET['id'];

    // Requête pour récupérer les détails de l'étudiant
    $sql_bulletin = "
        SELECT e.nom, e.prenom, e.matricule, 
               n.module1, n.module2, n.module3, n.module4,
               (n.module1 + n.module2 + n.module3 + n.module4) / 4 AS moyenne
        FROM etudiants e
        LEFT JOIN notes n ON e.id = n.etudiant_id
        WHERE e.id = ?
    ";

    if ($stmt_bulletin = mysqli_prepare($link, $sql_bulletin)) {
        mysqli_stmt_bind_param($stmt_bulletin, "i", $id);
        mysqli_stmt_execute($stmt_bulletin);
        mysqli_stmt_bind_result($stmt_bulletin, $nom, $prenom, $matricule, $module1, $module2, $module3, $module4, $moyenne);
        mysqli_stmt_fetch($stmt_bulletin);
        mysqli_stmt_close($stmt_bulletin);
    } else {
        echo "<p>Erreur lors de la récupération du bulletin de l'étudiant.</p>";
        exit();
    }

    mysqli_close($link);
    ?>

        <div class="bulletin-header">
            <h1>Bulletin de Notes</h1>
            <p>Université Cheich Anta Diop</p>
            <p>Année Académique: 2023-2024</p>
        </div>

        <div class="student-info">
            <p><strong>Nom :</strong> <?= htmlspecialchars($nom); ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($prenom); ?></p>
            <p><strong>Matricule :</strong> <?= htmlspecialchars($matricule); ?></p>
        </div>

        <table class="module-scores">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><i class="fas fa-book module-icon"></i> Module 1</td>
                    <td><?= htmlspecialchars($module1); ?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-calculator module-icon"></i> Module 2</td>
                    <td><?= htmlspecialchars($module2); ?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-flask module-icon"></i> Module 3</td>
                    <td><?= htmlspecialchars($module3); ?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-laptop module-icon"></i> Module 4</td>
                    <td><?= htmlspecialchars($module4); ?></td>
                </tr>
            </tbody>
        </table>

        <p class="moyenne">Moyenne Générale: <?= htmlspecialchars($moyenne); ?></p>

        <?php
        // Afficher un message basé sur la moyenne
        if ($moyenne < 10) {
            echo '<div class="result-message recalé">Vous êtes recalé.</div>';
        } elseif ($moyenne >= 10 && $moyenne < 12) {
            echo '<div class="result-message peu-mieux-faire">Peut mieux faire.</div>';
        } elseif ($moyenne >= 12) {
            echo '<div class="result-message félicitation">Félicitations ! Vous avez droit au tableau d\'honneur.</div>';
        }
        ?>

        <div class="footer">
            <p><a href="dashboard.php">Retour au tableau de bord</a></p>
        </div>
    </div>
</body>
</html>
