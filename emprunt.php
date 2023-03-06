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
    if ((!empty($_GET['id'])) && ($_GET['other'] ==  "false")) {
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
    if (($nbrEmprunt < 3) && ($_SESSION['emprunte'] == 0)) {
        $sql = "INSERT INTO emprunts (id_periodique, id_usager)
        VALUES ('$u', '$idUser')";
        $sqlEmprunt = "UPDATE usagers SET nbr_emprunt=('$nbrEmprunt' + 1) WHERE id=$idUser";
        $sqlPerioEmprun = "UPDATE periodiques SET emprunte=1 WHERE id=$u";
    }
    else {
        header("Location: index.php?emprunt=failed");
        mysqli_close($conn);
    }
      if (($conn->query($sql) === TRUE) && ($conn->query($sqlEmprunt) === TRUE) && ($conn->query($sqlPerioEmprun) === TRUE)) {
        echo "<div>Emprunt réussit</div>";
        header("Location: index.php?emprunt=ok");
        $_SESSION['nbrEmprunt'] = $_SESSION['nbrEmprunt'] + 1;

      } else {
        echo "erreur: " . $sql . "<br>" . mysqli_error($conn);
      }
      mysqli_close($conn);
    } elseif ((!empty($_GET['id'])) && ($_GET['other'] ==  "true")){
      $servername = "localhost";
      $usernameBD = "root";
      $passwordBD = "root";
      $dbname = "projet_intra";

      $idUser = $_SESSION['idUser'];

      $conn = new mysqli($servername, $usernameBD, $passwordBD, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sqlUser = "SELECT * FROM usagers WHERE id = $idUser";

      $result = $conn->query($sqlUser);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $_SESSION['nbrEmpruntUser'] = $row['nbr_emprunt'];
      $_SESSION['userMatricule'] = $row['matricule'];
      $_SESSION['userType'] = $row['Type'];
    }

      $u = $_GET['id'];
      $k = $_GET['emprunt'];
      $_SESSION['emprunte'] = $k;
      $nbrEmpruntUser = $_SESSION['nbrEmpruntUser'];
      
      
    if (($nbrEmpruntUser < 3) && ($_SESSION['emprunte'] == 0)) {
        $sql = "INSERT INTO emprunts (id_periodique, id_usager)
        VALUES ('$u', '$idUser')";
        $sqlEmprunt = "UPDATE usagers SET nbr_emprunt=('$nbrEmpruntUser' + 1) WHERE id=$idUser";
        $sqlPerioEmprun = "UPDATE periodiques SET emprunte=1 WHERE id=$u";
    }
    else {
        header("Location: index.php?other=true&idUser=$idUser");
        mysqli_close($conn);
    }
    if (($conn->query($sql) === TRUE) && ($conn->query($sqlEmprunt) === TRUE) && ($conn->query($sqlPerioEmprun) === TRUE)) {
      echo "<div>Emprunt réussit</div>";
      header("Location: index.php?other=true&idUser=$idUser");
      $_SESSION['nbrEmpruntUser'] = $_SESSION['nbrEmpruntUser'] + 1;

    } else {
      echo "erreur: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
    }
    else {
      header("Location: index.php?emprunt=failed");
      mysqli_close($conn);
    }
  }
  ?>
<script src="https://kit.fontawesome.com/2cfafb1177.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>