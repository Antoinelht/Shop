<?php
// On recupere la session courante
session_start();
error_log('Session started');

// On inclue le fichier de configuration et de connexion a la base de donn es
include('includes/config.php');
error_log('Config file included');

if (strlen($_SESSION['rdid']) == 0) {
     // Si l'utilisateur est déconnecté
     // L'utilisateur est renvoyé vers la page de login : index.php

     header('location:index.php');
     error_log('User is not logged in, redirected to index.php');
} else {
     // On récupère l'identifiant du lecteur dans le tableau $_SESSION
     $readerId = $_SESSION['rdid'];
     error_log(print_r($readerId, 1));


     // On veut savoir combien de livres ce lecteur a emprunte
     // On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
     $issuedBooksQuery = "SELECT COUNT(*) as total_emprunts FROM tblissuedbookdetails WHERE ReaderId = :readerId";
     $issuedBooksStmt = $dbh->prepare($issuedBooksQuery);
     $issuedBooksStmt->bindParam(':readerId', $readerId, PDO::PARAM_STR);
     $issuedBooksStmt->execute();

     $issuedBooksResult = $issuedBooksStmt->fetch(PDO::FETCH_ASSOC);
     $totalEmprunts = $issuedBooksResult['total_emprunts'];
     error_log('Total emprunts retrieved: ' . $totalEmprunts);

     // On stocke le résultat dans une variable
     $unreturnedBooksQuery = "SELECT COUNT(*) as total_non_rendus FROM tblissuedbookdetails WHERE ReaderId = :readerId AND ReturnStatus = 0";
     // On veut savoir combien de livres ce lecteur n'a pas rendu
     $unreturnedBooksStmt = $dbh->prepare($unreturnedBooksQuery);
     // On construit la requete qui permet de compter combien de livres sont associ s   ce lecteur avec le ReturnStatus   0 

     // On stocke le résultat dans une variable
     
     $unreturnedBooksStmt->bindParam(':readerId', $readerId, PDO::PARAM_STR);
     $unreturnedBooksStmt->execute();
     error_log(print_r($unreturnedBooksStmt, 1));

     $unreturnedBooksResult = $unreturnedBooksStmt->fetch(PDO::FETCH_ASSOC);
     $totalNonRendus = $unreturnedBooksResult['total_non_rendus'];
     error_log('Total non rendus retrieved: ' . $totalNonRendus);
}
?>

     <!DOCTYPE html>
     <html lang="FR">

     <head>
          <meta charset="utf-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
          <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
          <!-- BOOTSTRAP CORE STYLE  -->
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
          <!-- FONT AWESOME STYLE  -->
          <link href="assets/css/font-awesome.css" rel="stylesheet" />
          <!-- CUSTOM STYLE  -->
          <link href="assets/css/style.css" rel="stylesheet" />
     </head>

     <body>
          <!--On inclue ici le menu de navigation includes/header.php-->
          <?php include('includes/header.php'); ?>
          <!-- On affiche le titre de la page : Tableau de bord utilisateur-->
          <div class="container 50px-">
               <h2>Tableau de bord utilisateur</h2>

        <!-- On affiche la quantité de livres empruntés -->
               <div class="card 20px- 1pxsolidccc- 15px- ">
                    <div class="card-body">
                         <h4 class="card-title 007bff-">Livres empruntés</h4>
                         <p class="ccard-text fs-18px">Vous avez emprunté <?php echo $totalEmprunts; ?> livres.</p>
                         <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" fill="currentColor" class="bi bi-cart-check-fill" viewBox="0 0 16 16">
  <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m-1.646-7.646-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L8 8.293l2.646-2.647a.5.5 0 0 1 .708.708"/>
</svg>
                    </div>
               </div>

        <!-- On affiche la quantité de livres non rendus -->
               <div class="card 20px- 1pxsolidccc- 15px- ">
                    <div class="card-body">
                         <h4 class="card-title 007bff-">Livres non rendus</h4>
                         <p class="card-text fs-18px">Vous avez <?php echo $totalNonRendus; ?> livres non rendus.</p>
                         <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" fill="currentColor" class="bi bi-cart-x-fill" viewBox="0 0 16 16">
  <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M7.354 5.646 8.5 6.793l1.146-1.147a.5.5 0 0 1 .708.708L9.207 7.5l1.147 1.146a.5.5 0 0 1-.708.708L8.5 8.207 7.354 9.354a.5.5 0 1 1-.708-.708L7.793 7.5 6.646 6.354a.5.5 0 1 1 .708-.708"/>
</svg>
                    </div>
               </div>
          </div> 

          <?php include('includes/footer.php'); ?>
          <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     </body>

     </html>
