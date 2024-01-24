<?php
// On récupère la session courante
session_start();
error_log('Session started');
error_log(print_r($_SESSION, 1));

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
error_log('Config file included');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['rdid']) == 0) {
    // On le redirige vers la page de login
    header('location:index.php');
} else {
    // Sinon on peut continuer. Après soumission du formulaire de profil
    if (TRUE === isset($_POST['submit'])) {
// On recupere l'id du lecteur (cle secondaire)
$readerId = isset($_SESSION['rdid']) ? $_SESSION['rdid'] : null;


// On recupere le nom complet du lecteur
$fullName = $_POST['fullname'];
// On recupere le numero de portable
$mobileNumber = $_POST['mobilenumber'];
// On update la table tblreaders avec ces valeurs
$sqlUpdate = "UPDATE tblreaders SET FullName = :fullname, MobileNumber = :mobilenumber WHERE ReaderId = :readerid";
$queryUpdate = $dbh->prepare($sqlUpdate);
$queryUpdate->bindParam(':fullname', $fullName, PDO::PARAM_STR);
$queryUpdate->bindParam(':mobilenumber', $mobileNumber, PDO::PARAM_STR);
$queryUpdate->bindParam(':readerid', $readerId, PDO::PARAM_STR);
// On execute la requête
$queryUpdate->execute();
error_log('Query Update executed');

// On informe l'utilisateur du resultat de l'operation
// On informe l'utilisateur du resultat de l'operation
if ($queryUpdate->rowCount() > 0) {
    echo "<script>alert('Profil mis à jour avec succès')</script>";
} else {
    echo "<script>alert('Erreur lors de la mise à jour du profil')</script>";
}
    }
// On souhaite voir la fiche de lecteur courant.
// On recupere l'id de session dans $_SESSION
$readerId = isset($_SESSION['rdid']) ? $_SESSION['rdid'] : null;
error_log(print_r($readerId, 1));
 // On prepare la requete permettant d'obtenir les informations du lecteur
$sql = "SELECT ReaderId, EmailId, FullName, MobileNumber, RegDate, UpdateDate, Status
        FROM tblreaders 
        WHERE ReaderId = :readerid";
$query = $dbh->prepare($sql);
$query->bindParam(':readerid', $readerId, PDO::PARAM_STR);
// On stocke le résultat de recherche dans une variable $result
// Exécutez la requête
$query->execute();
error_log('query executed');
// On stocke le résultat de recherche dans une variable $result
$result = $query->fetch(PDO::FETCH_ASSOC);
error_log(print_r($result, 1));
 
}

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Mon Compte</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                <form method="post" action="my-profile.php">
                    <!-- On affiche l'identifiant - non editable -->
                    <div class="form-group">
    <label>Identifiant : <?php echo isset($result['ReaderId']) ? $result['ReaderId'] : ''; ?> </label>

</div>
<!-- On affiche la date d'enregistrement - non editable -->
<div class="form-group">
    <label>Date d'enregistrement</label>
    <input type="text" value="<?php echo isset($result['RegDate']) ? $result['RegDate'] : ''; ?>" required readonly>
</div>
<!-- On affiche la date de dernière mise à jour - non editable -->
<div class="form-group">
    <label>Date de dernière mise à jour</label>
    <input type="text" value="<?php echo isset($result['UpdateDate']) ? $result['UpdateDate'] : ''; ?>" required readonly>
</div>
<!-- On affiche la statut du lecteur - non editable -->
<div class="form-group">
    <label>Statut</label>
    <input type="text" value="<?php echo isset($result['Status']) ? $result['Status'] : ''; ?>" required readonly>
</div>
<!-- On affiche le nom complet - editable -->
<div class="form-group">
    <label>Nom complet</label>
    <input type="text" name="fullname" value="<?php echo isset($result['FullName']) ? $result['FullName'] : ''; ?>" required>
</div>
<!-- On affiche le numéro de portable- editable -->
<div class="form-group">
    <label>Numéro de portable</label>
    <input type="text" name="mobilenumber" value="<?php echo isset($result['MobileNumber']) ? $result['MobileNumber'] : ''; ?>" required>
</div>
<!-- On affiche l'email- editable -->
<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" value="<?php echo isset($result['EmailId']) ? $result['EmailId'] : ''; ?>" required>
</div>



                    <button type="submit" name="submit" class="btn btn-info">Mettre à jour le profil</button>
                </form>
            </div>
        </div>
    </div>


    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>