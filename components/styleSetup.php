<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<?php
$tema = $_GET['tema'] ?? 'claro';
if ($tema === 'oscuro') {
    $bgColor = '#292929';
    $textColor = '#eee';
} else {
    $bgColor = '#f5f5f5';
    $textColor = '#000';
}
?>

<style>
    body {
        background-color: <?= $bgColor ?>;
        color: <?= $textColor ?>;
    }

    header {
        background-color: #141414;
        color: white;
        text-align: center;
        font-size: 20px;
        padding: 15px;
    }

    header nav a {
        color: white;
        margin: 10px;
        font-weight: 600;
        text-decoration: none;
    }

    header nav a:hover {
        text-decoration: underline;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    #cardsContainer {
        margin-top: 20px;
    }

    .conteiner2 {
        max-width: 420px;
        margin: 0 auto;
    }

    .formulario {
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.08);
        border-radius: 10px;
    }

    .oscuro .btn-primary {
        background-color: #444;
        border-color: #666;
    }

    .claro .btn-primary {
        background-color: #0d6efd;
    }

    footer {
        background-color: #141414;
        color: white;
        text-align: center;
        padding: 15px;
        font-size: 18px;
    }
</style>