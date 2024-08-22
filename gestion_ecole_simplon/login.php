<?php
require_once "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparer la requête pour récupérer l'utilisateur par email
    $sql = "SELECT id, nom, prenom, email, mot_de_passe, role FROM administrateurs WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_array($resultat, MYSQLI_ASSOC)) {
            $idUser = $row['id'];
            $nomUser = $row['nom'];
            $prenomUser = $row['prenom'];
            $emailUser = $row['email'];
            $passwordUser = $row['mot_de_passe'];
            $roleUser = $row['role'];

            // Vérifier le mot de passe
            if (password_verify($password, $passwordUser)) {
                // Stocker les informations utilisateur dans la session
                $_SESSION['id'] = $idUser;
                $_SESSION['role'] = $roleUser;
                $_SESSION['nom'] = $nomUser;
                $_SESSION['prenom'] = $prenomUser;
                $_SESSION['email'] = $emailUser;

                // Rediriger en fonction du rôle
                if ($roleUser === 'superadmin') {
                    header('Location: dashboard.php');
                    exit();
                } elseif ($roleUser === 'admin') {
                    header('Location: dashboard_simple.html');
                    exit();
                } else {
                    echo "<p>Le rôle de l'utilisateur est inconnu.</p>";
                }
            } else {
                echo "<p>Email ou mot de passe incorrect.</p>";
                echo "<p><a href='login.html'>Retourner au formulaire de connexion</a></p>";
            }
        } else {
            echo "<p>Email ou mot de passe incorrect.</p>";
            echo "<p><a href='login.html'>Retourner au formulaire de connexion</a></p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur de préparation de la requête.";
    }

    mysqli_close($link);
} else {
    header("Location: login.html");
    exit();
}
