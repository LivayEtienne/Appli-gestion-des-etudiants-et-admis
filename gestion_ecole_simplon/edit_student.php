<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Étudiants</title>
    <link rel="stylesheet" href="style css/edit_student.css">
</head>
<body>
    <div class="containerx">
        <h1>Modification Étudiant</h1>
        <?php
        require_once "config.php";
        session_start();

        // Vérifiez si l'utilisateur est connecté
        // if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        //     header("Location: login.html");
        //     exit();
        // }

        // Récupérer l'ID de l'étudiant à modifier
        if (isset($_POST['id']) && is_numeric($_POST['id'])) {
            $id = $_POST['id'];

            // Préparer la requête de sélection
            $sql = "SELECT * FROM etudiants WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $id);

                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        ?>

                        <form id="studentForm" action="update_student.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                            <label for="nom">Nom:</label>
                            <input type="text" id="nom" name="nom" placeholder="Nom de l'étudiant" pattern="[A-Za-z\s]+" title="Lettres uniquement" value="<?php echo htmlspecialchars($row['nom']); ?>" required>
                            <span id="nomError" style="color:red;display:none;">Le nom ne doit pas commencer par un espace.</span>

                            <label for="prenom">Prénom:</label>
                            <input type="text" id="prenom" name="prenom" placeholder="Prénom de l'étudiant" required pattern="[A-Za-z\s]+" title="Lettres uniquement" value="<?php echo htmlspecialchars($row['prenom']); ?>">
                            <span id="prenomError" style="color:red;display:none;">Le prénom ne doit pas commencer par un espace.</span>

                            <label for="date_naissance">Date de naissance:</label>
                            <input type="date" id="date_naissance" name="date_naissance" required value="<?php echo htmlspecialchars($row['date_naissance']); ?>" required max="2008-12-31"> 

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="Email de l'étudiant" required value="<?php echo htmlspecialchars($row['email']); ?>">

                            <label for="telephone">Téléphone:</label>
                            <input type="tel" id="telephone" name="telephone" placeholder="Téléphone de l'étudiant" required pattern="[0-9]+" title="Chiffres uniquement" value="<?php echo htmlspecialchars($row['telephone']); ?>">

                            <label for="niveau">Niveau:</label>
                            <select id="niveau" name="niveau" required>
                                <option value="L1" <?php echo ($row['niveau'] == 'L1') ? 'selected' : ''; ?>>L1</option>
                                <option value="L2" <?php echo ($row['niveau'] == 'L2') ? 'selected' : ''; ?>>L2</option>
                                <option value="L3" <?php echo ($row['niveau'] == 'L3') ? 'selected' : ''; ?>>L3</option>
                                <option value="M1" <?php echo ($row['niveau'] == 'M1') ? 'selected' : ''; ?>>M1</option>
                                <option value="M2" <?php echo ($row['niveau'] == 'M2') ? 'selected' : ''; ?>>M2</option>
                            </select>

                            <button type="submit">Mettre à jour</button>
                        </form>

                        <?php
                    } else {
                        echo "<p>Étudiant non trouvé.</p>";
                    }
                } else {
                    echo "<p>Erreur lors de la récupération des données : " . mysqli_error($link) . "</p>";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "<p>Erreur de préparation de la requête : " . mysqli_error($link) . "</p>";
            }
        } else {
            echo "<p>ID non spécifié ou invalide.</p>";
        }

        mysqli_close($link);
        ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérification en temps réel pour le champ "nom"
            document.getElementById('nom').addEventListener('input', function() {
                const nomError = document.getElementById('nomError');
                if (this.value.startsWith(' ')) {
                    nomError.style.display = 'inline';
                    this.style.borderColor = 'red';
                } else {
                    nomError.style.display = 'none';
                    this.style.borderColor = '';
                }
            });

            // Vérification en temps réel pour le champ "prénom"
            document.getElementById('prenom').addEventListener('input', function() {
                const prenomError = document.getElementById('prenomError');
                if (this.value.startsWith(' ')) {
                    prenomError.style.display = 'inline';
                    this.style.borderColor = 'red';
                } else {
                    prenomError.style.display = 'none';
                    this.style.borderColor = '';
                }
            });

            // Validation lors de la soumission du formulaire
            document.getElementById('studentForm').addEventListener('submit', function(event) {
                const nom = document.getElementById('nom').value;
                const prenom = document.getElementById('prenom').value;
                const emailField = document.getElementById('email').value;
                const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                // Vérifier s'il y a des espaces multiples dans le nom ou le prénom
                const multipleSpacesRegex = /\s{2,}/;

                if (multipleSpacesRegex.test(nom) || multipleSpacesRegex.test(prenom)) {
                    alert("Les champs 'Nom' et 'Prénom' ne doivent pas contenir plusieurs espaces consécutifs.");
                    event.preventDefault(); // Empêche la soumission du formulaire
                }

                // Vérifier si l'année de naissance est supérieure à 2008
                var dob = document.getElementById('date_naissance').value;
                if (dob) {
                    var year = new Date(dob).getFullYear();
                    if (year > 2008) {
                        alert("L'année de naissance doit être inférieure ou égale à 2008.");
                        event.preventDefault(); // Empêche l'envoi du formulaire
                        return;
                    }
                }

                // Validation de l'email
                if (!emailRegex.test(emailField)) {
                    alert("L'adresse email n'est pas valide.");
                    document.getElementById('email').focus();
                    event.preventDefault(); // Empêche la soumission du formulaire si l'email est invalide
                    return;
                }

                // Validation des espaces pour "nom" et "prenom" lors de la soumission
                if (nom.startsWith(' ') || prenom.startsWith(' ')) {
                    alert("Le nom et le prénom ne doivent pas commencer par un espace.");
                    event.preventDefault(); // Empêche l'envoi du formulaire
                }
            });
        });
    </script>
</body>
</html>
