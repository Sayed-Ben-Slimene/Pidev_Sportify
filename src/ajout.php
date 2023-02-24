<?php
if (isset($_POST['user']) && isset($_POST['email']) && isset($_POST['commentaire'])) {
    $mysqli = new mysqli("localhost", "root", "", "match");
    $mysqli->set_charset("utf8");
    $requete = "INSERT INTO avis VALUES('" . $_POST['name'] . "', '" . $_POST['email'] . "', '" . $_POST['commentaire'] . "')";
    $resultat = $mysqli->query($requete);
    if ($resultat)
        echo "<p>Le commentaire a été ajouté</p>";
    else
        echo "<p>Erreur</p>";
}
