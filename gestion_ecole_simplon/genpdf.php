<?php
use Dompdf\Dompdf;
require_once "dompdf-3.0.0/autoload.inc.php";

$dompdf = new Dompdf();

$dompdf->loadHtml('Brouette');

$dompdf->render();
$dompdf->stream();