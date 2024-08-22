<?php
require_once "config.php";
session_start();

// Vérifier si l'ID de l'étudiant est fourni
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $etudiant_id = $_POST['id'];

    // Récupérer les notes actuelles de l'étudiant
    $sql_notes = "SELECT module1, module2, module3, module4 FROM notes WHERE etudiant_id = ?";
    if ($stmt = mysqli_prepare($link, $sql_notes)) {
        mysqli_stmt_bind_param($stmt, "i", $etudiant_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $module1, $module2, $module3, $module4);

        if (mysqli_stmt_fetch($stmt)) {
            // Afficher les notes dans un formulaire pour les modifier
            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Modifier les Notes de l'Étudiant</title>
                <link rel="stylesheet" href="style css/edit_note.css">
                <script>
                    // Validation JavaScript pour n'accepter que des nombres positifs
                    function validateForm() {
                        const inputs = document.querySelectorAll('input[type="text"]');
                        const regex = /^[0-9]+(\.[0-9]{1,2})?$/;

                        for (let i = 0; i < inputs.length; i++) {
                            const value = inputs[i].value.trim();
                            if (value !== "" && !regex.test(value)) {
                                alert("Veuillez entrer un nombre positif valide pour " + inputs[i].name);
                                return false;
                            }
                        }
                        return true;
                    }
                </script>
            </head>
            <body>
                <div class="container">
                    <h2>Modifier les Notes de l'Étudiant</h2>
                    <form action="save_notes.php" method="POST" onsubmit="return validateForm();">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($etudiant_id); ?>">
                        <label for="module1">Module 1:</label>
                        <input type="text" name="module1" value="<?php echo htmlspecialchars($module1); ?>"><br>
                        <label for="module2">Module 2:</label>
                        <input type="text" name="module2" value="<?php echo htmlspecialchars($module2); ?>"><br>
                        <label for="module3">Module 3:</label>
                        <input type="text" name="module3" value="<?php echo htmlspecialchars($module3); ?>"><br>
                        <label for="module4">Module 4:</label>
                        <input type="text" name="module4" value="<?php echo htmlspecialchars($module4); ?>"><br>
                        <button type="submit">Enregistrer</button>
                    </form>
                    <!-- Votre script de gestion du temps d'inactivité ici -->
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "<p class='error-message'>Erreur : impossible de récupérer les notes de l'étudiant.</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p class='error-message'>Erreur : " . mysqli_error($link) . "</p>";
    }
} else {
    echo "<p class='error-message'>ID d'étudiant non fourni.</p>";
}

mysqli_close($link);
?>
