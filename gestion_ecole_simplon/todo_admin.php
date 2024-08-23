<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Administrateurs</title>
    <link rel="stylesheet" href="style css/todo_admin.css">
</head>
<body>
    <div class="containerx">
        <h1>Liste des Administrateurs</h1>
        <div>
            <button><a href="admin.html">Ajouter un Administrateur</a></button>
            <button><a href="dashboard.php">Accueil</a></button>
        </div>
        <?php
        require_once "config.php";
        session_start();

        // Vérifiez si l'utilisateur est connecté
        // if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        //     header("Location: login.html");
        //     exit();
        // }

        // Récupérer les données des administrateurs
        $sql = "SELECT * FROM administrateurs";
        $result = mysqli_query($link, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Nom</th>';
                echo '<th>Prénom</th>';
                echo '<th>Email</th>';
                // echo '<th>Mot de passe</th>';
                echo '<th>Role</th>';
                echo '<th>Actions</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = mysqli_fetch_assoc($result)) {
                    // Déterminez la classe CSS en fonction du rôle
                    $roleClass = $row['role'] === 'super-admin' ? 'super-admin' : ($row['role'] === 'admin' ? 'admin' : '');

                    echo '<tr class="' . htmlspecialchars($roleClass) . '">';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nom']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['prenom']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                    // echo '<td>' . htmlspecialchars($row['mot_de_passe']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['role']) . '</td>';
                    echo '<td class="actions">';
                    echo '<form action="edit_admin.php" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<button type="submit" class="edit-btn">Modifier</button>';
                    echo '</form>';
                    /*echo '<form action="delete-student.php" method="POST" style="display:inline;" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cet étudiant?\');">';
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<button type="submit" class="delete-btn">Archiver</button>';
                    echo '</form>';*/
                    echo '<form action="delete_admin.php" method="POST" style="display:inline;" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cet étudiant?\');">';
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<button type="submit" class="delete-btn">Supprimer</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>Aucun administrateur enregistré.</p>';
            }
        } else {
            echo '<p>Erreur lors de la récupération des données : ' . mysqli_error($link) . '</p>';
        }

        // Fermer la connexion
        mysqli_close($link);
        ?>
    </div>
</body>
</html>
