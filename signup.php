<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Aaprès la soumission du formulaire de compte (plus bas dans ce fichier)
if (true === isset($_POST["login"])) {
// On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire

// $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)

//On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur cree.
$ressourceLue = file('readerid.txt');
// On incrémente de 1 la valeur lue
$ressourceIncr = ++$ressourceLue[0];
// On ouvre le fichier readerid.txt en écriture
$ressource = fopen('readerid.txt', 'c+b');
// On écrit dans ce fichier la nouvelle valeur
fwrite($ressource, $ressourceIncr);
// On referme le fichier
fclose($ressource);
// On récupère le nom saisi par le lecteur
$nomComplet = $_SESSION['fullname'] = $_POST['fullname'];
// On récupère le numéro de portable
$_SESSION['tel'] = $_POST['tel'];
$numeroPortable = $_POST['tel'];
// On récupère l'email
$_SESSION['emailid'] = $_POST['emailid'];
$email = $_POST['emailid'];
// On récupère le mot de passe
$motDePasse = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';

// On fixe le statut du lecteur à 1 par défaut (actif)
$status = 1;
// On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders
// On éxecute la requete

// On récupère le dernier id inséré en bd (fonction lastInsertId)

// On prépare la requête d'insertion en base de données de toutes ces valeurs dans la table tblreaders
$sqlInsert = "INSERT INTO tblreaders (ReaderId, FullName, MobileNumber, EmailId, Password, Status) 
                VALUES (:readerid, :fullname, :tel, :emailid, :password, :status)";
//VALUES ('$ressourceIncr', '$nomComplet', $numeroPortable, '$email', '$motDePasse', $status)";
error_log($sqlInsert);
$queryInsert = $dbh->prepare($sqlInsert);

$queryInsert->bindParam(':readerid', $ressourceIncr, PDO::PARAM_STR);
$queryInsert->bindParam(':fullname', $nomComplet, PDO::PARAM_STR);
$queryInsert->bindParam(':tel', $numeroPortable, PDO::PARAM_STR);
$queryInsert->bindParam(':emailid', $email, PDO::PARAM_STR);
$queryInsert->bindParam(':password', $motDePasse, PDO::PARAM_STR);
$queryInsert->bindParam(':status', $status, PDO::PARAM_INT);
error_log($ressourceIncr);
$queryInsert->execute();
$lastInsertedId = $dbh->lastInsertId();
error_log($lastInsertedId);
// Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée,
// et on affiche l'identifiant lecteur (valeur de $hit[0] après incrémentation)

  // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée,
        // et on affiche l'identifiant lecteur (valeur de $lastInsertId après incrémentation)
        if ($lastInsertedId !== 0) {
            echo "<script>alert('Compte créé avec succès. Votre identifiant est : " . $ressourceIncr . "')</script>";
        } else {
            // Sinon on affiche qu'il y a eu un problème
            echo "<script>alert('Erreur lors de la création du compte')</script>";
        } 
    } 
// Sinon on affiche qu'il y a eu un problème
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Gestion de bibliotheque en ligne | Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <!-- link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' / -->
    <script type="text/javascript">
        // On cree une fonction valid() sans paramètre qui renvoie 
        // TRUE si les mots de passe saisis dans le formulaire sont identiques
        // FALSE sinon

    function valid() {
    let test = document.getElementById("test");
    let password = document.getElementById("password");
    let checkPassword = document.getElementById("checkPassword");
    console.log(checkPassword.value);
    console.log(password.value);
    
    if (password.value === checkPassword.value) {
        test.style.color = "green"; // Changement de la couleur à vert
        return true;
    } else {
        test.style.color = "red";
        return false;
    }
}
 
        // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email
        // Cette fonction effectue un appel fetch vers check_availability.php
        // Le mail est passé dans l'url
        async function checkAvailability(emailid) {
    try {
        // Use encodeURIComponent to properly encode the emailid
        let encodedEmail = encodeURIComponent(emailid);
        let response = await fetch(`check_availability.php?email=${encodedEmail}`);
        let data = await response.json();

        if (data.response === 'fail') {
            // Handle failure if needed
            console.log("Email is available");
        } else {
            // Handle success if needed
            console.log("Email is already in use");
           alert('Votre compte à été bloqué');
          
        }
    } catch (error) {
        console.error('Une erreur s\'est produite lors de la vérification de disponibilité :', error);
        alert("Une erreur s'est produite lors de la vérification de disponibilité. Veuillez réessayer.");
    }
}
        
    </script>
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <!--On affiche le titre de la page : CREER UN COMPTE-->
<!-- On insere le titre de la page (LOGIN UTILISATEUR) -->
<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-7 offset-md-3 form-control">
				<h3>CREATE ACCOUNT</h3>
			</div>
		</div>
		<div class="row">
			<!--On insere le formulaire de login-->
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-7 offset-md-3 form-control"> 
            <form method ="post" action="signup.php" onsubmit="return valid()">
					<div class="form-group">
						<label>Entrez votre nom complet</label>
						<input type="text" name="fullname" required>
					</div>
                     
					<div class="form-group">
						<label>Entrez votre email</label>
						<input type="text" name="emailid" onblur="checkAvailability(this.value)" id="emailid" required>
					</div>

					<div class="form-group">
						<label>Entrez votre mot numéro de téléphone</label>
						<input type="tel" name="tel" required>
					</div>
                    <div class="form-group">
						<label>Entrez votre mot de passe</label>
						<input type="password" name="password" id="password" required >
            
                        <div class="form-group">
						<label id="test">Confirmez votre mot de passe</label>
						<input type="checkPassword" name="checkPassword" onkeyup="valid()" id="checkPassword" required>
                        </div>
					<!--A la suite de la zone de saisie du captcha, on insere l'image cree par captcha.php : <img src="captcha.php">  -->
					<div class="form-group">
						<label>Code de vérification</label>
						<input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
					</div>

					<button type="submit" name="login" class="btn btn-info">REGISTER</button>&nbsp;&nbsp;&nbsp;
				</form>
			</div>
		</div>
    </div>
</div>
    <!--On affiche le formulaire de creation de compte-->
    
    <!--A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->

    <!-- On appelle la fonction valid() dans la balise <form> onSubmit="return valid(); -->
   
    <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->
    <?php include('includes/footer.php');?>
 
    
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>