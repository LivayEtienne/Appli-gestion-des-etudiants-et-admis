<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config.php";
session_start();

// Initialiser les variables pour les compteurs
$archived_students = 0;
$non_archived_students = 0;
$administrators = 0;

// Requête SQL pour récupérer le nombre d'étudiants archivés
$sql_archived_students = "SELECT COUNT(*) as total FROM etudiants WHERE archive = 1";
$result_archived_students = mysqli_query($link, $sql_archived_students);
if ($result_archived_students) {
    $archived_students = mysqli_fetch_assoc($result_archived_students)['total'];
} else {
    echo "Erreur SQL pour les étudiants archivés: " . mysqli_error($link);
}

// Requête SQL pour récupérer le nombre d'étudiants non archivés
$sql_non_archived_students = "SELECT COUNT(*) as total FROM etudiants WHERE archive = 0";
$result_non_archived_students = mysqli_query($link, $sql_non_archived_students);
if ($result_non_archived_students) {
    $non_archived_students = mysqli_fetch_assoc($result_non_archived_students)['total'];
} else {
    echo "Erreur SQL pour les étudiants non archivés: " . mysqli_error($link);
}

// Requête SQL pour récupérer le nombre d'administrateurs
$sql_administrators = "SELECT COUNT(*) as total FROM administrateurs";
$result_administrators = mysqli_query($link, $sql_administrators);
if ($result_administrators) {
    $administrators = mysqli_fetch_assoc($result_administrators)['total'];
} else {
    echo "Erreur SQL pour les administrateurs: " . mysqli_error($link);
}

// Fermer la connexion
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style css/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <!-- Barre latérale -->
        <aside class="sidebar">
            <div class="logo">
                <h2>AdminFusion </h2>
            </div>
            <nav class="menu-lateral">
                <ul>
                    <li><a href="etudiant_archiver.php"><i class="fas fa-archive"></i> Étudiants Archivés</a></li>
                    <li><a href="todo.php"><i class="fas fa-list"></i> Étudiants Non Archivés</a></li>
                    <li><a href="add-student.html"><i class="fas fa-user-plus"></i> Ajouter Étudiant</a></li>
                    <li><a href="admin.html"><i class="fas fa-user-shield"></i> Ajouter Admin</a></li>
                    <li><a href="todo_admin.php"><i class="fas fa-users"></i> Liste des Admins</a></li>
                </ul>
            </nav>
            <div class="logout">
                <button><a href="loggin.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></button>
            </div>
        </aside>

        <!-- Contenu principal -->
        <main class="main-content">
            <header class="header">
            <div class="greeting">
                <h2>Bonjour!</h2>
                <p>Merci de vous connecter. Nous avons préparé un tableau de bord pour vous aider à gérer vos tâches avec facilité.</p>
            </div>

            </header>

            <section class="cards">
                <div class="card">
                    <div class="card-icon"><i class="fas fa-users"></i></div>
                    <div class="card-info">
                        <p>Étudiants Archivés</p>
                        <h3><?php echo $archived_students; ?></h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon"><i class="fas fa-users"></i></div>
                    <div class="card-info">
                        <p>Étudiants Non Archivés</p>
                        <h3><?php echo $non_archived_students; ?></h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon"><i class="fas fa-user-shield"></i></div>
                    <div class="card-info">
                        <p>Administrateurs</p>
                        <h3><?php echo $administrators; ?></h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-icon"><i class="fas fa-book"></i></div>
                    <div class="card-info">
                        <p>Total Étudiants</p>
                        <h3><?php echo $archived_students + $non_archived_students; ?></h3>
                    </div>
                </div>
            </section>

            <section class="summary">
                
            </section>
        </main>
    </div>

    <style>
        
    </style>
</body>
</html>