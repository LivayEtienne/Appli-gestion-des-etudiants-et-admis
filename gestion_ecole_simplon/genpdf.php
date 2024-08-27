<?php
require_once "../dompdf-3.0.0/dompdf/autoload.inc.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Initialisation de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true); // Optionnel, si vous avez besoin d'exécuter du PHP dans HTML
$dompdf = new Dompdf($options);

// Données (remplacez avec vos propres données si nécessaire)
$nom = 'Dupont';
$prenom = 'Jean';
$matricule = '12345';
$module1 = 12;
$module2 = 15;
$module3 = 14;
$module4 = 13;
$moyenne = ($module1 + $module2 + $module3 + $module4) / 4;

// Création du contenu HTML
$html = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin de Notes</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .bulletin-container { width: 100%; max-width: 800px; margin: auto; }
        .bulletin-header { text-align: center; margin-bottom: 20px; }
        .student-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        .moyenne { font-weight: bold; }
    </style>
</head>
<body>
    <div class="bulletin-container">
        <div class="bulletin-header">
            <h1>Bulletin de Notes</h1>
            <p>Université Cheikh Anta Diop</p>
            <p>Année Académique: 2023-2024</p>
        </div>
        <div class="student-info">
            <p><strong>Nom :</strong> ' . htmlspecialchars($nom) . '</p>
            <p><strong>Prénom :</strong> ' . htmlspecialchars($prenom) . '</p>
            <p><strong>Matricule :</strong> ' . htmlspecialchars($matricule) . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Module 1</td>
                    <td>' . htmlspecialchars($module1) . '</td>
                </tr>
                <tr>
                    <td>Module 2</td>
                    <td>' . htmlspecialchars($module2) . '</td>
                </tr>
                <tr>
                    <td>Module 3</td>
                    <td>' . htmlspecialchars($module3) . '</td>
                </tr>
                <tr>
                    <td>Module 4</td>
                    <td>' . htmlspecialchars($module4) . '</td>
                </tr>
            </tbody>
        </table>
        <p class="moyenne">Moyenne Générale: ' . number_format($moyenne, 2) . '</p>
    </div>
</body>
</html>
';

// Charger le contenu HTML dans Dompdf
$dompdf->loadHtml($html);

// Configurer la taille du papier et l'orientation
$dompdf->setPaper('A4', 'portrait');

// Générer le PDF
$dompdf->render();

// Envoyer le PDF au navigateur
$dompdf->stream('bulletin.pdf', array('Attachment' => 1));
