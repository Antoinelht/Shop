<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de données
include('includes/config.php');

// Si l'utilisateur est déconnecté
// L'utilisateur est renvoyé vers la page de login : index.php
if (!isset($_SESSION['alogin']) || $_SESSION['alogin'] != 'admin') {
    // Redirect to login page
    header('Location:../index.php');
    exit;
}

// On recupere l'identifiant de la catégorie a supprimer
$id = isset($_GET['id']) ? intval($_GET['id']) : 0 ;

// On prépare la requête de mise à jour
$stmt = $dbh->prepare("UPDATE tblcategory SET status = Status WHERE id = :id");

// On lie l'identifiant à la requête préparée
$stmt->bindParam(':id', $id , PDO::PARAM_INT); 

// On exécute la requête
if($stmt->execute())

// On récupère toutes les catégories
$stmt = $dbh->prepare("SELECT * FROM tblcategory");
$stmt->execute();
$categories = $stmt->fetchAll();



// On redirige l'utilisateur vers la page manage-categories.php
//header('Location: manage-categories.php');
//exit;

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    
<?php include('includes/header.php'); ?>

    <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-11 offset-md-1 col-xl-10 offset-xl-2">
            <br>
    <h3 class="title-container-login">Gestion des catégories</h3>
          </div>
      </div>
    <br>
<?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['msg'] ?>
        <?php unset($_SESSION['msg']); ?>
    </div> 
<?php endif; ?>

    <table class="table">
        <thead>
        <tr>
            <th scope="col" class="table-bordered border-dark">Numéro d'ordre</th>
            <th scope="col" class="table-bordered border-dark">Nom</th>
            <th scope="col" class="table-bordered border-dark">Statut</th>
            <th scope="col" class="table-bordered border-dark">Date de création</th>
            <th scope="col" class="table-bordered border-dark">Date de mise à jour</th>
            <th scope="col" class="table-bordered border-dark">Actions</th>
        </tr>
    </thead>
          <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <th scope="row" class="table-secondary table-bordered bordered-dark"></th>
                <td class="table-secondary table-bordered bordered-dark"><?= $category['id'] ?></td>
                <td class="table-secondary table-bordered bordered-dark"><?= $category['CategoryName'] ?></td>
                <td class="table-secondary table-bordered bordered-dark <?= $category['Status'] == 1 ? 'btn btn-success' : 'btn btn-danger' ?>">
                    <?= $category['Status'] == 1 ? 'Actif' : 'Inactif' ?>
                </td>
                <td class="table-secondary table-bordered bordered-dark"><?= $category['CreationDate'] ?></td>
                <td class="table-secondary table-bordered bordered-dark"><?= $category['UpdationDate'] ?></td>
                <td>
                    <a class="btn btn-primary" href="edit-category.php?id=<?= $category['id'] ?>">Éditer</a>
                    <a class="btn btn-danger" href="manage-categories.php?id=<?= $category['id'] ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>

