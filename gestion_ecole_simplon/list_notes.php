<?php
require_once "config.php";
session_start();

// Vérifie si l'utilisateur est connecté
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header("Location: login.php");
//     exit();
// }

$niveaux = ['L1', 'L2', 'L3', 'M1', 'M2'];

// Initialisation des variables pour les statistiques
$admis = 0;
$recalé = 0;

// Définir le niveau par défaut à "L1" si aucun niveau n'est passé en paramètre GET
$selected_niveau = isset($_GET['niveau']) ? $_GET['niveau'] : 'L1';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants par Niveau</title>
    <link rel="stylesheet" href="style css/list_notes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>
    <button id="toggle-mode"><i class="fas fa-sun"></i> Mode Jour</button>

    <div class="containerx">
        <h1>Liste des Étudiants par Niveau</h1>
        <div class="niveaux-container">
            <?php foreach ($niveaux as $niveau) : ?>
                <a href="?niveau=<?php echo htmlspecialchars($niveau); ?>" <?php echo ($niveau === $selected_niveau) ? 'style="background-color: #0056b3;"' : ''; ?>>
                    <i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($niveau); ?>
                </a>
            <?php endforeach; ?>
            <button><a href="todo.php" style="color: white;"><i class="fas fa-arrow-left"></i> Retour</a></button>
        </div>

        <?php
        // Récupérer les étudiants pour le niveau sélectionné
        $sql_etudiants = "
            SELECT e.id, e.nom, e.prenom, e.matricule, 
                   IFNULL(n.module1, '-') AS module1,
                   IFNULL(n.module2, '-') AS module2,
                   IFNULL(n.module3, '-') AS module3,
                   IFNULL(n.module4, '-') AS module4,
                   IFNULL((n.module1 + n.module2 + n.module3 + n.module4) / 4, '-') AS moyenne,
                   CASE
                       WHEN (n.module1 + n.module2 + n.module3 + n.module4) / 4 >= 10 THEN 'admis'
                       ELSE 'recalé'
                   END AS statut_admission
            FROM etudiants e
            LEFT JOIN notes n ON e.id = n.etudiant_id
            WHERE e.niveau = ?
            ORDER BY 
                CASE 
                    WHEN (n.module1 + n.module2 + n.module3 + n.module4) / 4 >= 10 THEN 1
                    ELSE 2
                END,
                e.nom ASC;
        ";

        if ($stmt_etudiants = mysqli_prepare($link, $sql_etudiants)) {
            mysqli_stmt_bind_param($stmt_etudiants, "s", $selected_niveau);
            mysqli_stmt_execute($stmt_etudiants);
            mysqli_stmt_bind_result($stmt_etudiants, $id, $nom, $prenom, $matricule, $module1, $module2, $module3, $module4, $moyenne, $statut_admission);

            $etudiants = [];
            while (mysqli_stmt_fetch($stmt_etudiants)) {
                $etudiants[$id] = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'matricule' => $matricule,
                    'module1' => $module1,
                    'module2' => $module2,
                    'module3' => $module3,
                    'module4' => $module4,
                    'moyenne' => $moyenne,
                    'statut_admission' => $statut_admission
                ];

                // Calculer les statistiques
                if ($statut_admission == 'admis') {
                    $admis++;
                } else {
                    $recalé++;
                }
            }

            mysqli_stmt_close($stmt_etudiants);

            echo "<table>";
            echo "<thead>";
            echo "<tr><th>Nom</th><th>Prénom</th><th>Module 1</th><th>Module 2</th><th>Module 3</th><th>Module 4</th><th>Moyenne</th><th>Statut</th><th>Actions</th></tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($etudiants as $etudiant_id => $etudiant) {
                // Déterminer la classe CSS en fonction du statut d'admission
                $statut_class = ($etudiant['statut_admission'] == 'admis') ? 'statut-admis' : ($etudiant['statut_admission'] == 'recalé' ? 'statut-recale' : 'statut-en-cours');

                echo "<tr class='$statut_class'>";
                echo "<td>" . htmlspecialchars($etudiant['nom']) . "</td>";
                echo "<td>" . htmlspecialchars($etudiant['prenom']) . "</td>";
                echo "<td class='module-col'>" . htmlspecialchars($etudiant['module1']) . "</td>";
                echo "<td class='module-col'>" . htmlspecialchars($etudiant['module2']) . "</td>";
                echo "<td class='module-col'>" . htmlspecialchars($etudiant['module3']) . "</td>";
                echo "<td class='module-col'>" . htmlspecialchars($etudiant['module4']) . "</td>";
                echo "<td class='moyenne-col'>" . htmlspecialchars($etudiant['moyenne']) . "</td>";
                echo "<td>" . htmlspecialchars($etudiant['statut_admission']) . "</td>";
                echo '<td class="actions">';
                if ($etudiant['module1'] !== '-' && $etudiant['module2'] !== '-' && $etudiant['module3'] !== '-' && $etudiant['module4'] !== '-') {
                    echo '<form action="edit_notes.php" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($etudiant_id) . '">';
                    echo '<button type="submit" class="edit-btn"><i class="fas fa-edit"></i> Modifier</button>';
                    echo '</form>';
                    echo '<form action="bulletin.php" method="GET" style="display:inline;">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($etudiant_id) . '">';
                    echo '<button type="submit" class="bulletin-btn"><i class="fas fa-file-alt"></i> Tirer un bulletin</button>';
                    echo '</form>';
                } else {
                    echo '<form action="ajout_noteform.php" method="GET" style="display:inline;">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($etudiant_id) . '">';
                    echo '<button type="submit" class="add-note-btn"><i class="fas fa-plus-circle"></i> Ajouter une note</button>';
                    echo '</form>';
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";

            echo "<p class='status'>Nombre d'étudiants admis : $admis</p>";
            echo "<p class='status'>Nombre d'étudiants recalés : $recalé</p>";
        } else {
            echo "<p>Erreur lors de la récupération des étudiants.</p>";
        }

        mysqli_close($link);
        ?>
    </div>
</body>
<script>
    const toggleButton = document.getElementById('toggle-mode');
    const body = document.body;

    toggleButton.addEventListener('click', function() {
        body.classList.toggle('day-mode');
        
        if (body.classList.contains('day-mode')) {
            toggleButton.innerHTML = '<i class="fas fa-moon"></i> Mode Nuit';
        } else {
            toggleButton.innerHTML = '<i class="fas fa-sun"></i> Mode Jour';
        }
    });
</script>
</html>
