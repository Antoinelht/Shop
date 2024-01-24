<?php

// Inclusion du fichier de configuration et de connexion à la base de données
require_once("includes/config.php");

// Récupération de l'email soumis par l'utilisateur depuis $_GET
$emailid = $_GET['email'];

// Enregistrement de l'email dans les logs d'erreur
error_log($emailid);

// Vérification de la validité de l'email (utilisation de la fonction filter_var)
error_log($emailid);

// Vérification de la validité de l'email
if (filter_var($emailid, FILTER_VALIDATE_EMAIL)) {
	
    try {
        // Préparation de la requête pour rechercher la présence de l'email dans la table tblreaders
        $query = "SELECT * FROM tblreaders WHERE emailid = :email";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':email', $emailid, PDO::PARAM_STR);

        // Exécution de la requête et gestion des erreurs
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si le résultat n'est pas vide, l'email existe déjà
            if ($result) {
                echo '{"response": "ok"}'; // "This email already exists. Please choose another one.";
                
            } else {
                // Sinon, l'email est disponible
                echo '{"response": "fail"}'; // "This email is available. You can proceed.";
                
            }
        } else {
            // Gestion des erreurs d'exécution de la requête
            echo '{"response": "error", "message": "Database error"}';
        }
    } catch (PDOException $e) {
        // Gestion des exceptions PDO
        echo '{"response": "error", "message": "'.$e->getMessage().'"}';
    }
} else {
    // Si l'email n'est pas valide, affichage d'un message d'erreur
    echo '{"response": "fail", "message": "'.$emailid.' is not a valid email address"}';
}

// Fonction pour vérifier la disponibilité de l'email
// ... (Votre code PHP existant)


?>
