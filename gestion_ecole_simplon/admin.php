<?php
require "config.php";
session_start();

// Vérifiez si l'utilisateur est connecté
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header("Location: login.html");
//     exit();
// }

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    // Définir les valeurs autorisées pour le rôle
    $roles_autorises = ['superadmin', 'admin'];
      // Debugging : voir la valeur de $role
      var_dump($role);

    // Vérifier si le rôle est valide
    if (!in_array($role, $roles_autorises)) {
        die("<p>Erreur : rôle non valide.</p>");
    }

    // Hacher le mot de passe
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Préparer la requête d'insertion
    $sql = "INSERT INTO administrateurs (nom, prenom, email, mot_de_passe, role, date_inscription) VALUES (?, ?, ?, ?, ?, NOW())";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Lier les variables à la requête
        mysqli_stmt_bind_param($stmt, "sssss", $nom, $prenom, $email, $hashed_password, $role);

        // Exécuter la requête
        if (mysqli_stmt_execute($stmt)) {
            // Redirection après succès
            header("Location: todo_admin.php");
            exit();
        } else {
            echo "<p>Erreur lors de l'exécution : " . mysqli_stmt_error($stmt) . "</p>";
        }

        // Fermer la déclaration
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Erreur lors de la préparation de la requête : " . mysqli_error($link) . "</p>";
    }

    // Fermer la connexion
    mysqli_close($link);
} else {
    header("Location: admin.html");
    exit();
}
?>