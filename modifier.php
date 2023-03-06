<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    if(!empty($_GET['modifier'])) {
        $_SESSION['modifier'] = $_GET['modifier'];  
      }
    if (!isset($_SESSION["connexion"]) or $_SESSION["connexion"] != true) { ?>

        <h1>Il faut être connecté pour voir cette page.</h1>

        <a class="btn btn-primary" href="index.php"> Connectez-vous</a>

        <?php } elseif(($_SESSION['type'] == 1) && ($_SESSION['modifier'] == "periodique")) {
        function protection($data)
        {
            $data = trim($data);
            $data = addslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $valide = "";
        $nom = "";
        $errNom = "";
        $titre = "";
        $errTitre = "";
        $annee = "";
        $errAnnee = "";
        $mois = "";
        $errMois = "";
        $numero = "";
        $errNumero = "";
        $img = "";
        $errImg = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $valide = true;

            if (empty($_POST['nom'])) {
                $valide = false;
                $errNom = "Le nom est obligatoire";
            } else {
                $nom = $_POST['nom'];
                $nom = protection($nom);
                if (strlen($nom) < 5) {
                    $valide = false;
                    $errNom = "Le nom doit contenir au moins 5 charactères";
                }
            }
            if (empty($_POST['titre'])) {
                $valide = false;
                $errTitre = "Le titre est obligatoire";
            } else {
                $titre = $_POST['titre'];
                $titre = protection($titre);
                if (strlen($titre) < 10) {
                    $valide = false;
                    $errTitre = "Le titre doit contenir au moins 10 charactères";
                }
            }
            if (empty($_POST['annee'])) {
                $valide = false;
                $errAnnee = "L'année est obligatoire";
            } else {
                $annee = $_POST['annee'];
                $annee = protection($annee);
                if (strlen($annee) < 4) {
                    $valide = false;
                    $errAnnee = "L'année  doit contenir au moins 4 chiffres";
                }
            }
            if (empty($_POST['mois'])) {
              $valide = false;
              $errMois = "Le mois est obligatoire";
          } else {
              $mois = $_POST['mois'];
              $mois = protection($mois);
              if (strlen($mois) < 3) {
                  $valide = false;
                  $errMois = "Le mois doit contenir au moins 3 charactères";
              }
          }
          if (empty($_POST['numero'])) {
            $valide = false;
            $errNumero = "Le numéro est obligatoire";
        } else {
            $numero = $_POST['numero'];
            $numero = protection($numero);
            if (strlen($numero) < 1) {
                $valide = false;
                $errTitre = "Le numéro doit contenir au moins 1 chiffre";
            }
        }
            if (empty($_POST['img'])) {
                $valide = false;
                $errImg = "L'image est obligatoire";
            } else {
                $img = $_POST['img'];
                if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $img)) {
                    $valide = false;
                    $errImg = "Lien invalide";
                }
            }
        }
        $servername = "localhost";
        $usernameBD = "root";
        $passwordBD = "root";
        $dbname = "projet_intra";
        $id = $_GET['id'];

        $conn = new mysqli($servername, $usernameBD, $passwordBD, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM periodiques WHERE id = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        ?>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>" method="POST">
                            <?php
                            while ($row = $result->fetch_assoc()) {
                            ?>
                                <div class="mb-3">
                                    <label for="nom">Nom: </label><br>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $row["nom"]; ?>" required>
                                    <div id="errNom" class="form-text text-danger"><?php echo $errNom; ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="titre">Titre: </label><br>
                                    <input type="text" class="form-control" id="titre" name="titre" value="<?php echo $row["titre"]; ?>" required>
                                    <div id="errTitre" class="form-text text-danger"><?php echo $errTitre; ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="annee">Année: </label><br>
                                    <input type="number" class="form-control" id="annee" name="annee" min="1990" max="2100" value="<?php echo $row["annee"]; ?>" required>
                                    <div id="errAnnee" class="form-text text-danger"><?php echo $errAnnee; ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="mois">Mois: </label><br>
                                    <input type="text" class="form-control" id="mois" name="mois" value="<?php echo $row["mois"]; ?>" required>
                                    <div id="errMois" class="form-text text-danger"><?php echo $errMois; ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="numero">Numéro: </label><br>
                                    <input type="text" class="form-control" id="numero" name="numero" value="<?php echo $row["numero"]; ?>" required>
                                    <div id="errNumero" class="form-text text-danger"><?php echo $errNumero; ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="img">Liens vers l'image: </label><br>
                                    <input type="text" class="form-control" id="img" name="img" value="<?php echo $row["img"]; ?>" required>
                                    <div id="errImg" class="form-text text-danger"><?php echo $errImg; ?></div>
                                </div>
                                <button type="submit" class="btn btn-success">Modifier</button>
                                <a href="index.php" class="btn btn-danger">Annuler</a>
                            <?php
                            }
                            ?>
                        </form><br>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $id; ?>" />
            </div>
    <?php
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && $valide) {
            $sql = "UPDATE periodiques SET nom='$nom', titre='$titre', annee='$annee', mois='$mois', numero='$numero', img='$img' WHERE id=$id";

            if (mysqli_query($conn, $sql)) {
                echo "<div>enregistrement réussi</div>";
                header("Location: index.php?insert=ok");
            } else {
                echo "erreur: " . $sql . "<br>" . mysqli_error($conn);
            }
            $conn->close();
        }
    }
    elseif (($_SESSION['type'] == 1) && ($_SESSION['modifier'] == "utilisateur")) {
        function protection($data)
        {
            $data = trim($data);
            $data = addslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $valide = "";
        $matricule = "";
        $errMatricule= "";
        $password = "";
        $errPassword = "";
        $email = "";
        $errEmail = "";
        $type = "";
        $errType = "";
        $mat = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $valide = true;

            if (empty($_POST['matricule'])) {
                $valide = false;
                $errMatricule= "Le matricule est obligatoire";
            } else {
                $matricule = $_POST['matricule'];
                $matricule = protection($matricule);
                if (strlen($matricule) < 5) {
                    $valide = false;
                    $errMatricule = "Le matricule doit contenir au moins 5 charactères";
                }
                elseif (strlen($matricule) > 15) {
                    $valide = false;
                    $errMatricule = "Le matricule doit contenir au plus 15 charactères";
                }
            }
          
            if (empty($_POST['password'])) {
                $valide = false;
                $errPassword = "Le mot de passe est obligatoire";
            } else {
                $password = $_POST['password'];
                $password = protection($password);
                if (strlen($password) < 8) {
                    $valide = false;
                    $errPassword = "Le mot de passe doit contenir au moins 8 charactères";
                }
                else {
                    $password = sha1($password, false);
                }
            }
            if (empty($_POST['email'])) {
                $valide = false;
                $errEmail = "L'adresse courriel est obligatoire";
            } else {
                $email = $_POST['email'];
                $email = protection($email);
                if (strlen($email) < 5) {
                    $valide = false;
                    $errEmail = "L'année  doit contenir au moins 5 charactères";
                }
            }
            if (empty($_POST['type'])) {
              $valide = false;
              $errType = "Le type est obligatoire";
            } 
            else {
                $type = $_POST['type'];
            }
        }    
        
        $servername = "localhost";
        $usernameBD = "root";
        $passwordBD = "root";
        $dbname = "projet_intra";
        $id = $_GET['id'];

        $conn = new mysqli($servername, $usernameBD, $passwordBD, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM usagers WHERE id = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        ?>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>" method="POST">
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                $mat = $row["matricule"];
                            ?>
                                <div class="mb-3">
                                    <label for="matricule">Matricule: </label><br>
                                    <input type="text" class="form-control" id="matricule" name="matricule" value="<?php echo $row["matricule"]; ?>" required>
                                    <div id="errMatricule" class="form-text text-danger"><?php echo $errMatricule; ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="password">Mot de passe: </label><br>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div id="errPassword" class="form-text text-danger"><?php echo $errPassword; ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="email">Adresse courriel: </label><br>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $row["email"]; ?>" required>
                                    <div id="errEmail" class="form-text text-danger"><?php echo $errEmail; ?></div>
                                </div>
                                <?php
                                if ($_SESSION['matricule'] != $row["matricule"]) {
                                ?>
                                <div class="mb-3">
                                    <p>Type: </p>
                                    <input type="radio" id="professeur" name="type" value="true" <?php echo ($row["type"] == 1 ? "checked" : "" ); ?> >
                                    <label for="Professeur">Professeur</label><br>
                                    <input type="radio" id="etudiant" name="type" value="false" <?php echo ($row["type"] == 0 ? "checked" : ""); ?> >
                                    <label for="etudiant">Étudiant</label><br>
                                    <div id="errType" class="form-text text-danger"><?php echo $errType; ?></div>
                                </div>
                                <?php
                                }
                                ?>
                                <button type="submit" class="btn btn-success">Modifier</button>
                                <a href="utilisateur.php" class="btn btn-danger">Annuler</a>
                            <?php
                            }
                            ?>
                        </form><br>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $id; ?>" />
            </div>
    <?php
    
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && $valide) {
            if ($_SESSION["matricule"] == $mat) {
                $sql = "UPDATE usagers SET matricule='$matricule', password='$password', email='$email' WHERE id=$id";
            }
            else {
                $sql = "UPDATE usagers SET matricule='$matricule', password='$password', email='$email', type=$type WHERE id=$id";
            }
            
            if (mysqli_query($conn, $sql)) {
                echo "<div>Modification réussi</div>";
                header("Location: utilisateur.php?insert=ok");
            } else {
                echo "erreur: " . $sql . "<br>" . mysqli_error($conn);
            }
            $conn->close();
        }
    }
    ?>
    <script src="https://kit.fontawesome.com/2cfafb1177.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>