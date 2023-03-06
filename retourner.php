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
    if (!isset($_SESSION["connexion"]) or $_SESSION["connexion"] != true) { ?>

        <h1>Il faut être connecté pour voir cette page.</h1>

        <a class="btn btn-primary" href="index.php"> Connectez-vous</a>
    <?php 
    } 
    else {
    if (!empty($_GET['id']) && ($_GET['other'] == "false")) {
      $u = $_GET['id'];
      $k = $_GET['emprunt'];
      $_SESSION['emprunte'] = $k;
      $idUser = $_SESSION['id'];
      $nbrEmprunt = $_SESSION['nbrEmprunt'];
      $servername = "localhost";
      $usernameBD = "root";
      $passwordBD = "root";
      $dbname = "projet_intra";
      $conn = new mysqli($servername, $usernameBD, $passwordBD, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
    if (($nbrEmprunt > 0) && ($_SESSION['emprunte'] == 1)) {
        $sql = "DELETE FROM emprunts WHERE id_periodique='$u'";
        $sqlRetour = "UPDATE usagers SET nbr_emprunt=('$nbrEmprunt' - 1) WHERE id=$idUser";
        $sqlPerioRetour = "UPDATE periodiques SET emprunte=0 WHERE id=$u";
    }
    else {
        header("Location: index.php?other=false");
        mysqli_close($conn);
    }
      if (($conn->query($sql) === TRUE) && ($conn->query($sqlRetour) === TRUE) && ($conn->query($sqlPerioRetour) === TRUE)) {
        echo "<div>Retour réussi</div>";
        header("Location: retour.php?other=false");
        $_SESSION['nbrEmprunt'] = $_SESSION['nbrEmprunt'] - 1;
      } else {
        echo "erreur: " . $sql . "<br>" . mysqli_error($conn);
      }
      mysqli_close($conn);
    }
    elseif (!empty($_GET['id']) && ($_GET['other'] ==  "true")) {
      $u = $_GET['id'];
      $k = $_GET['emprunt'];
      $_SESSION['emprunte'] = $k;
      $idUser = $_SESSION['idUser'];
      $nbrEmpruntUser = $_SESSION['nbrEmpruntUser'];
      $servername = "localhost";
      $usernameBD = "root";
      $passwordBD = "root";
      $dbname = "projet_intra";
      $conn = new mysqli($servername, $usernameBD, $passwordBD, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
    if (($nbrEmpruntUser > 0) && ($_SESSION['emprunte'])) {
        $sql2 = "DELETE FROM emprunts WHERE id_periodique='$u'";
        $sqlRetour2 = "UPDATE usagers SET nbr_emprunt=('$nbrEmpruntUser' - 1) WHERE id=$idUser";
        $sqlPerioRetour2 = "UPDATE periodiques SET emprunte=0 WHERE id=$u";
    }
    else {
        header("Location: index.php?retour=failed");
        mysqli_close($conn);
    }
      if (($conn->query($sql2) === TRUE) && ($conn->query($sqlRetour2) === TRUE) && ($conn->query($sqlPerioRetour2) === TRUE)) {
        echo "<div>Retour réussi</div>";
        header("Location: retour.php?other=true&idUser=$idUser");
        $_SESSION['nbrEmpruntUser'] = ($_SESSION['nbrEmpruntUser'] - 1);
      } else {
        echo "erreur: " . $sql . "<br>" . mysqli_error($conn);
      }
      mysqli_close($conn);
      }
    else {
      header("Location: index.php?other=true");
      mysqli_close($conn);
    }
  }
  ?>
<script src="https://kit.fontawesome.com/2cfafb1177.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>