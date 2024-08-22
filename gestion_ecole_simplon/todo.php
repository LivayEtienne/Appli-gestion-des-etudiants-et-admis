<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants</title>
    <link rel="stylesheet" href="style css/todo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        
    </style>
</head>
<body>
    <div class="containerx">
        <h1>Liste des Étudiants</h1>
           <!-- Barre de recherche -->
        <form method="GET" action="todo.php" style="text-align: center; margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Rechercher par nom, prénom ou matricule" style="padding: 10px; width: 300px; border-radius: 20px; border: 1px solid #ccc;">
            <button type="submit" style="padding: 10px 20px; border-radius: 20px; background-color: #007bff; color: white; border: none; cursor: pointer;">Rechercher</button>
        </form>
        <div class="bouton">
            <button><a href="etudiant_archiver.php">Etudiants archivés</a></button>
            <button><a href="add-student.html">Ajouter un étudiant</a></button>
            <button><a href="dashboard.php">Accueil</a></button>
            <button><a href="list_notes.php">Gérer les Notes</a></button>
        </div>
 <?php
    require_once "config.php";
    session_start();

    // RécupérET2000DOer le terme de recherche si présent
    $search = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';

    // Requête pour compter le nombre total d'étudiants non archivés
    $count_sql = "SELECT COUNT(*) as total FROM etudiants WHERE archive = 0";
    if (!empty($search)) {
        $count_sql .= " AND (nom LIKE '%$search%' OR prenom LIKE '%$search%' OR matricule LIKE '%$search%')";
    }
    $count_result = mysqli_query($link, $count_sql);
    $total_students = mysqli_fetch_assoc($count_result)['total'];

    echo "<h2 style='text-align:center;'>Nombre total d'étudiants: $total_students</h2>";

    // Requête SQL pour récupérer uniquement les étudiants non archivés avec recherche
    $sql = "SELECT * FROM etudiants WHERE archive = 0";
    if (!empty($search)) {
        $sql .= " AND (nom LIKE '%$search%' OR prenom LIKE '%$search%' OR matricule LIKE '%$search%')";
    }
    $sql .= " ORDER BY 
                CASE 
                    WHEN statut_admission = 'admis' THEN 1
                    WHEN statut_admission = 'en cours' THEN 2
                    WHEN statut_admission = 'recalé' THEN 3
                    ELSE 4
                END, nom ASC";

    $result = mysqli_query($link, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Nom</th>';
            echo '<th>Prénom</th>';
            echo '<th>Date de Naissance</th>';
            echo '<th>Email</th>';
            echo '<th>Matricule</th>';
            echo '<th>Niveau</th>';
            echo '<th>Statut Admission</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            while ($row = mysqli_fetch_assoc($result)) {
                // Déterminer la classe CSS en fonction du statut
                $statut_class = '';
                if ($row['statut_admission'] == 'admis') {
                    $statut_class = 'statut-admis';
                } elseif ($row['statut_admission'] == 'en cours') {
                    $statut_class = 'statut-en-cours';
                } elseif ($row['statut_admission'] == 'recalé') {
                    $statut_class = 'statut-recale';
                }
            
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nom']) . '</td>';
                echo '<td>' . htmlspecialchars($row['prenom']) . '</td>';
                echo '<td>' . htmlspecialchars($row['date_naissance']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['matricule']) . '</td>';
                echo '<td>' . htmlspecialchars($row['niveau']) . '</td>';
                echo '<td class="' . $statut_class . '">' . htmlspecialchars($row['statut_admission']) . '</td>';
                echo '<td class="actions">';
                echo '<form action="edit_student.php" method="POST" style="display:inline;" onsubmit="return confirm(\'Êtes-vous sûr de vouloir modifier cet étudiant?\');">';
                echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="update-btn"><i class="fas fa-edit"></i> Modifier</button>';
                echo '</form>';
                echo '<form action="archiver.php" method="POST" style="display:inline;" onsubmit="return confirm(\'Êtes-vous sûr de vouloir archiver cet étudiant?\');">';
                echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="archive-btn"><i class="fas fa-archive"></i> Archiver</button>';
                echo '</form>';
                echo '<form action="delete_etudiant.php" method="POST" style="display:inline;" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cet étudiant?\');">';
                echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="delete-btn"><i class="fas fa-trash"></i> Supprimer</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p style="text-align:center;">Aucun étudiant trouvé.</p>';
        }
    } else {
        echo '<p style="text-align:center; color:red;">Erreur lors de la récupération des données : ' . mysqli_error($link) . '</p>';
    }

    // Fermer la connexion
    mysqli_close($link);
?>


    </div>
</body>
</html>
