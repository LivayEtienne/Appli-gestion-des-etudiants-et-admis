<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants</title>
    <link rel="stylesheet" href="style css/todo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="light-mode"> <!-- Mode jour par défaut -->
    <div class="containerx">
        <button class="toggle-btn" onclick="toggleMode()">Mode Nuit</button>
        <h1>Liste des Étudiants</h1>
        <!-- Barre de recherche -->
        <form method="GET" action="todo.php" style="text-align: center; margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Rechercher par nom, prénom ou matricule" style="padding: 10px; width: 300px; border-radius: 20px; border: 1px solid #ccc;">
            <button type="submit" style="padding: 10px 20px; border-radius: 20px; background-color: #007bff; color: white; border: none; cursor: pointer;">Rechercher</button>
        </form>
        <div class="bouton">
            <button><a href="etudiant_archiver.php"><i class="fas fa-archive"></i> Étudiants archivés</a></button>
            <button><a href="add-student.html"><i class="fas fa-user-plus"></i> Ajouter un étudiant</a></button>
            <button><a href="dashboard.php"><i class="fas fa-home"></i> Accueil</a></button>
            <button><a href="list_notes.php"><i class="fas fa-list"></i> Gérer les Notes</a></button>
        </div>

        <?php
        require_once "config.php";
        session_start();

        // Afficher les messages de succès ou d'erreur
        if (isset($_GET['success'])) {
            $message = '';
            if ($_GET['success'] == 'archived') {
                $message = 'Étudiant archivé avec succès.';
            } elseif ($_GET['success'] == 'updated') {
                $message = 'Étudiant modifié avec succès.';
            }
            if ($message) {
                echo "<p style='text-align:center; color:green;'>$message</p>";
            }
        }

        if (isset($_GET['error'])) {
            echo '<p style="text-align:center; color:red;">Erreur : ' . htmlspecialchars($_GET['error']) . '</p>';
        }

        // Récupérer le terme de recherche si présent
        $search = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';

        // Requête pour compter le nombre total d'étudiants non archivés
        $count_sql = "SELECT COUNT(*) as total FROM etudiants WHERE archive = 0";
        if (!empty($search)) {
            $count_sql .= " AND (nom LIKE '%$search%' OR prenom LIKE '%$search%' OR matricule LIKE '%$search%')";
        }
        $count_result = mysqli_query($link, $count_sql);
        $total_students = mysqli_fetch_assoc($count_result)['total'];

        echo "<h2 class='total-students' style='text-align:center;'>Nombre total d'étudiants: $total_students</h2>";

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
                //echo '<th>ID</th>';
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

                    // Appliquer strtoupper pour afficher en majuscules
                    $nom = strtoupper(htmlspecialchars($row['nom']));
                    $prenom = strtoupper(htmlspecialchars($row['prenom']));
                    $email = strtoupper(htmlspecialchars($row['email']));

                    echo '<tr>';
                   // echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>' . $nom . '</td>';
                    echo '<td>' . $prenom . '</td>';
                    echo '<td>' . htmlspecialchars($row['date_naissance']) . '</td>';
                    echo '<td>' . $email . '</td>';
                    echo '<td>' . htmlspecialchars($row['matricule']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['niveau']) . '</td>';
                    echo '<td class="' . $statut_class . '">' . htmlspecialchars($row['statut_admission']) . '</td>';
                    echo '<td class="actions">';
                    echo '<form action="edit_student.php" method="POST" style="display:inline;" class="action-form">';
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<button type="button" class="update-btn" onclick="openConfirmModal(\'Êtes-vous sûr de vouloir modifier cet étudiant ?\', this.form, \'submit\')"><i class="fas fa-edit"></i> Modifier</button>';
                    echo '</form>';
                    echo '<form action="archiver.php" method="POST" style="display:inline;" class="action-form">';
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<button type="button" class="archive-btn" onclick="openConfirmModal(\'Êtes-vous sûr de vouloir archiver cet étudiant ?\', this.form, \'submit\')"><i class="fas fa-archive"></i> Archiver</button>';
                    echo '</form>';
                    echo '<form action="delete_etudiant.php" method="POST" style="display:inline;" class="action-form">';
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<button type="button" class="delete-btn" onclick="openConfirmModal(\'Êtes-vous sûr de vouloir supprimer cet étudiant ?\', this.form, \'delete\')"><i class="fas fa-trash"></i> Supprimer</button>';
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

    <!-- Modal de confirmation -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="confirmMessage"></p>
            <button id="confirmYes" class="modal-btn">Oui</button>
            <button id="confirmNo" class="modal-btn">Non</button>
        </div>
    </div>

    <!-- Modal de succès -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="successMessage">Opération réussie !</p>
        </div>
    </div>


    <script>
   function toggleMode() {
    var body = document.body;
    body.classList.toggle('dark-mode');
    body.classList.toggle('light-mode');

    var toggleBtn = document.querySelector('.toggle-btn');
    toggleBtn.textContent = body.classList.contains('dark-mode') ? 'Mode Jour' : 'Mode Nuit';
}

// Gestion de la modal de succès
var successModal = document.getElementById("successModal");
var closeModal = successModal ? document.querySelector("#successModal .close") : null;
var successMessage = document.getElementById("successMessage");

// Initialement caché
if (successModal) {
    successModal.style.display = "none"; // Initialement caché
}

// Fonction pour afficher la modal de succès avec un message personnalisé
function showSuccessModal(message) {
    if (successMessage) {
        successMessage.textContent = message;
    }
    if (successModal) {
        successModal.style.display = "block";

        // Masquer la modal de succès après 3 secondes
        setTimeout(function() {
            successModal.style.display = "none";
        }, 3000); // Temps en millisecondes (3000 ms = 3 secondes)
    }
}

// Fonction pour gérer la modal de confirmation
var confirmModal = document.getElementById("confirmModal");
var confirmMessage = document.getElementById("confirmMessage");
var confirmYes = document.getElementById("confirmYes");
var confirmNo = document.getElementById("confirmNo");
var currentForm;
var currentAction;

function openConfirmModal(message, form, action) {
    confirmMessage.textContent = message;
    confirmModal.style.display = "block";
    currentForm = form;
    currentAction = action;
}

function handleConfirmAction(action) {
    if (action === 'submit') {
        currentForm.submit();
    } else if (action === 'delete') {
        currentForm.action = 'delete_etudiant.php';
        currentForm.submit();
    }
    confirmModal.style.display = "none";
}

// Événements pour les boutons de la modal de confirmation
confirmYes.onclick = function () {
    handleConfirmAction(currentAction);
};

confirmNo.onclick = function () {
    confirmModal.style.display = "none";
};

// Fermeture de la modal de confirmation en cliquant sur la croix
var closeConfirmModal = document.querySelector("#confirmModal .close");
closeConfirmModal.onclick = function () {
    confirmModal.style.display = "none";
};

// Ajout de la fermeture manuelle de la modal de succès
if (closeModal) {
    closeModal.onclick = function () {
        successModal.style.display = "none";
    };
}

// Événement global pour fermer les modales en cliquant en dehors
window.onclick = function (event) {
    if (event.target == confirmModal) {
        confirmModal.style.display = "none";
    } else if (event.target == successModal) {
        successModal.style.display = "none";
    }
};

// Afficher la modal de succès si un paramètre 'success' est présent dans l'URL
function checkSuccessParam() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        const successType = urlParams.get('success');
        let message = '';

        switch (successType) {
            case 'archived':
                message = 'Étudiant archivé avec succès.';
                break;
            case 'updated':
                message = 'Étudiant modifié avec succès.';
                break;
            default:
                message = 'Opération réussie.';
                break;
        }

        showSuccessModal(message);
    }
}

// Appeler la fonction pour vérifier le paramètre 'success'
checkSuccessParam();


    </script>
</body>
</html>
