<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <link rel="stylesheet" href="style css/style.css"> <!-- Lien vers le fichier CSS -->
    <!-- Inclure Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Connexion Administrateur</h1>

        <?php
        session_start();
        if (isset($_SESSION['login_message'])) {
            echo '<div id="errorModal" class="modal">';
            echo '<div class="modal-content">';
            echo '<span class="close">&times;</span>';
            echo '<p>' . htmlspecialchars($_SESSION['login_message']) . '</p>';
            echo '</div>';
            echo '</div>';
            unset($_SESSION['login_message']); // Supprime le message après l'avoir affiché
        }
        ?>

        <form action="login.php" method="POST" class="form-block">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Votre Email" required>

            <label for="password">Mot de passe:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                <i id="togglePassword" class="fas fa-eye"></i>
            </div>

            <button type="submit">Se connecter</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', () => {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle the eye icon
                togglePassword.classList.toggle('fa-eye-slash');
            });

            // Gestion du modal d'erreur
            const modal = document.getElementById('errorModal');
            const closeModal = document.querySelector('.modal .close');

            if (modal) {
                modal.style.display = 'block'; // Afficher le modal si présent

                closeModal.addEventListener('click', () => {
                    modal.style.display = 'none';
                });

                window.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>
