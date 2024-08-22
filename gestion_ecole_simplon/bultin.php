<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "config.php";
session_start();

// Vérifier si l'ID de l'étudiant est fourni
if (!isset($_GET['id'])) {
    echo "<p class='error-message'>ID d'étudiant non fourni.</p>";
    exit();
}

$etudiant_id = $_GET['id'];

// Récupérer les informations de l'étudiant
$sql_etudiant = "SELECT nom, prenom FROM etudiants WHERE id = ?";
if ($stmt_etudiant = mysqli_prepare($link, $sql_etudiant)) {
    mysqli_stmt_bind_param($stmt_etudiant, "i", $etudiant_id);
    mysqli_stmt_execute($stmt_etudiant);
    mysqli_stmt_bind_result($stmt_etudiant, $nom, $prenom);
    
    if (mysqli_stmt_fetch($stmt_etudiant)) {
        mysqli_stmt_close($stmt_etudiant); // Fermer l'instruction préparée

        // Récupérer les notes de l'étudiant
        $sql_notes = "SELECT module1, module2, module3, module4 FROM notes WHERE etudiant_id = ?";
        if ($stmt_notes = mysqli_prepare($link, $sql_notes)) {
            mysqli_stmt_bind_param($stmt_notes, "i", $etudiant_id);
            mysqli_stmt_execute($stmt_notes);
            mysqli_stmt_bind_result($stmt_notes, $module1, $module2, $module3, $module4);
            
            if (mysqli_stmt_fetch($stmt_notes)) {
                // Calculer la moyenne
                $moyenne = ($module1 + $module2 + $module3 + $module4) / 4;
                $statut_admission = ($moyenne >= 10) ? 'admis' : 'recalé';
                
                // Afficher le bulletin
                ?>
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Bulletin de l'Étudiant</title>
                    <link rel="stylesheet" href="bultin.css"> <!-- Assurez-vous que le fichier CSS est correctement lié -->
                </head>
                <body>
                    <div class="container">
                        <h1>Bulletin de l'Étudiant</h1>
                        <h2><?php echo htmlspecialchars($nom) . ' ' . htmlspecialchars($prenom); ?></h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Module 1</th>
                                    <th>Module 2</th>
                                    <th>Module 3</th>
                                    <th>Module 4</th>
                                    <th>Moyenne</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo number_format($module1, 2); ?></td>
                                    <td><?php echo number_format($module2, 2); ?></td>
                                    <td><?php echo number_format($module3, 2); ?></td>
                                    <td><?php echo number_format($module4, 2); ?></td>
                                    <td><?php echo number_format($moyenne, 2); ?></td>
                                    <td><?php echo htmlspecialchars($statut_admission); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="text-align: center;">
                            <button><a href="edit_notes.php?id=<?php echo urlencode($etudiant_id); ?>">Modifier les Notes</a></button>
                            <button><a href="todo.php">Retour</a></button>
                        </div>
                    </div>
                </body>
                </html>
                <?php
            } else {
                echo "<p class='error-message'>Erreur : impossible de récupérer les notes de l'étudiant.</p>";
            }
            mysqli_stmt_close($stmt_notes); // Fermer l'instruction préparée pour les notes
        } else {
            echo "<p class='error-message'>Erreur : " . mysqli_error($link) . "</p>";
        }
    } else {
        echo "<p class='error-message'>Erreur : impossible de récupérer les informations de l'étudiant.</p>";
    }
} else {
    echo "<p class='error-message'>Erreur : " . mysqli_error($link) . "</p>";
}

// Fermer la connexion
mysqli_close($link);
?>
