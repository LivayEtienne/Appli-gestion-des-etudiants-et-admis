<?php
require_once "config.php";
session_start();

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

// Vérification de la présence de l'ID de l'étudiant
if (!isset($_GET['id'])) {
    echo "ID d'étudiant manquant.";
    exit();
}

$id = $_GET['id'];

// Requête pour récupérer les détails de l'étudiant et ses notes
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin de Notes</title>
    
    <!-- Lien vers le fichier CSS principal -->
    <link rel="stylesheet" href="style css/bulletin.css">
    
    <!-- Lien vers la bibliothèque d'icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <div class="bulletin-container">
        
        <!-- Bouton de retour -->
        <div class="top-left-button">
            <a href="list_notes.php" class="btn-retour">
                <i class="fas fa-arrow-left"></i> Retour à la liste des notes
            </a>
        </div>
        
        <!-- En-tête du bulletin -->
        <div class="bulletin-header">
            <h1>Bulletin de Notes</h1>
            <p>Université Cheikh Anta Diop</p>
            <p>Année Académique: 2023-2024</p>
        </div>
        
        <!-- Informations sur l'étudiant -->
        <div class="student-info">
            <p><strong>Nom :</strong> <?= htmlspecialchars($nom); ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($prenom); ?></p>
            <p><strong>Matricule :</strong> <?= htmlspecialchars($matricule); ?></p>
        </div>
        
        <!-- Tableau des notes -->
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
        
        <!-- Affichage de la moyenne générale -->
        <p class="moyenne">Moyenne Générale: <?= htmlspecialchars(number_format($moyenne, 2)); ?></p>
        
        <!-- Message en fonction de la moyenne -->
        <?php if ($moyenne !== null): ?>
            <?php if ($moyenne < 10): ?>
                <div class="result-message echec">
                    <i class="fas fa-times-circle"></i> Vous êtes recalé.
                </div>
            <?php elseif ($moyenne >= 10 && $moyenne < 12): ?>
                <div class="result-message encouragement">
                    <i class="fas fa-exclamation-circle"></i> Peut mieux faire.
                </div>
            <?php else: ?>
                <div class="result-message felicitation">
                    <i class="fas fa-check-circle"></i> Félicitations ! Vous avez droit au tableau d'honneur.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="result-message info">
                <i class="fas fa-info-circle"></i> Notes non disponibles.
            </div>
        <?php endif; ?>
        
        <!-- Lien de retour au tableau de bord -->
        <div class="footer">
            <a href="dashboard.php" class="btn-dashboard">
                <i class="fas fa-tachometer-alt"></i> Retour au tableau de bord
            </a>
        </div>
    </div>
</body>
</html>
