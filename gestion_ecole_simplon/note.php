<?php
require_once "config.php";
session_start();

// Vérifier si le niveau est passé en paramètre
if (isset($_GET['niveau'])) {
    $niveau = $_GET['niveau'];
} else {
    echo "Niveau non spécifié.";
    exit();
}

// Préparer la requête SQL pour récupérer les étudiants du niveau spécifié
$sql_etudiants = "SELECT id, nom, prenom, matricule, date_naissance, email, niveau, statut_admission, archive 
                  FROM etudiants 
                  WHERE niveau = ?";
$stmt_etudiants = mysqli_prepare($link, $sql_etudiants);

if ($stmt_etudiants) {
    mysqli_stmt_bind_param($stmt_etudiants, "s", $niveau);
    mysqli_stmt_execute($stmt_etudiants);
    $result_etudiants = mysqli_stmt_get_result($stmt_etudiants);

    // Afficher les résultats
    if (mysqli_num_rows($result_etudiants) > 0) {
        echo '<div class="containerx">';
        echo '<h1>Liste des Étudiants - ' . htmlspecialchars($niveau) . '</h1>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nom</th>';
        echo '<th>Prénom</th>';
        echo '<th>Date de Naissance</th>';
        echo '<th>Email</th>';
        echo '<th>Matricule</th>';
        echo '<th>Statut Admission</th>';
        echo '<th>Note</th>';
        echo '<th>Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = mysqli_fetch_assoc($result_etudiants)) {
            $id_etudiant = $row['id'];

            // Préparer la requête SQL pour récupérer les notes de cet étudiant
            $sql_notes = "SELECT note FROM notes WHERE id_etudiant = ?";
            $stmt_notes = mysqli_prepare($link, $sql_notes);

            if ($stmt_notes) {
                mysqli_stmt_bind_param($stmt_notes, "i", $id_etudiant);
                mysqli_stmt_execute($stmt_notes);
                $result_notes = mysqli_stmt_get_result($stmt_notes);
                $notes = [];

                while ($note_row = mysqli_fetch_assoc($result_notes)) {
                    $notes[] = $note_row['note'];
                }

                // Fusionner les notes en une seule chaîne, séparée par des virgules
                $notes_display = implode(', ', $notes);

                mysqli_stmt_close($stmt_notes);
            } else {
                $notes_display = "Erreur lors de la récupération des notes.";
            }

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
            echo '<td class="' . $statut_class . '">' . htmlspecialchars($row['statut_admission']) . '</td>';
            echo '<td>' . htmlspecialchars($notes_display) . '</td>';
            echo '<td class="actions">';
            echo '<form action="edit_student.php" method="POST" style="display:inline;" onsubmit="return confirm(\'Êtes-vous sûr de vouloir modifier cet étudiant?\');">';
            echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
            echo '<button type="submit" class="vert">Modifier</button>';
            echo '</form>';
            echo '<form action="archiver.php" method="POST" style="display:inline;" onsubmit="return confirm(\'Êtes-vous sûr de vouloir archiver cet étudiant?\');">';
            echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
            echo '<button type="submit" class="orange">Archiver</button>';
            echo '</form>';
            echo '<form action="delete_etudiant.php" method="POST" style="display:inline;" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cet étudiant?\');">';
            echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
            echo '<button type="submit" class="rouge">Supprimer</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p>Aucun étudiant trouvé pour ce niveau.</p>';
    }

    mysqli_stmt_close($stmt_etudiants);
} else {
    echo "Erreur de préparation de la requête.";
}

mysqli_close($link);
?>
