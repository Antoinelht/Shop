<?php
session_start();

error_log('Session started add-category.php');
error_log("add-category" . print_r($_SESSION, 1));



include('includes/config.php');
error_log('config included');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    
// if (!isset($_SESSION['alogin']) || $_SESSION['alogin'] != 'admin') {
    $_SESSION['error'] = "Something went wrong. Please try again";

    // On le redirige vers la page de login
    header('Location:../index.php');
   

// Sinon on peut continuer. Après soumission du formulaire de creation
} 

error_log(print_r($_POST, 1));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On recupere le nom et le statut de la categorie
    $categoryName = $_POST['categoryName'];
    error_log(gettype( $categoryName));
    
    //$categoryStatus = array_key_exists('Status', $_POST) ? $_POST['Status']: '0';
    $categoryStatus = $_POST['categoryStatus'];
    error_log(gettype( $categoryStatus));

    // On prepare la requete d'insertion dans la table tblcategory
    $sql = "INSERT INTO tblcategory (CategoryName, Status) VALUES (:CategoryName, :Status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':CategoryName', $categoryName, PDO::PARAM_STR);
    $query->bindParam(':Status', $categoryStatus, PDO::PARAM_STR);
    error_log(gettype( $categoryStatus));

    // On execute la requête
    $query->execute();
    error_log('Query executed');


} 

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Ajouter une catégories</h4>
            </div>
        </div>
    <!-- Form for category creation -->
    <div class="form-group">
    <form method="post" action="add-category.php">
        <label for="categoryName">Nom de la catégorie:</label>
        <input type="text" id="categoryName" name="categoryName" required>
    </div>
    <div class="form-group">
    <label for="categoryStatus">Statut:</label>
    <br>
    <label>
        <input type="radio" name="categoryStatus" value="1" checked> Active
    </label>
    <label>
        <input type="radio" name="categoryStatus" value="0"> Inactive
    </label>

        
    </div>
    <div class="form-group">
        <input type="submit" value="Ajouter la catégorie" class="btn btn-primary">
    </div>
   </div>
    </form>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>


</html>
