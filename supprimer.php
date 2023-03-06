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

  <?php } elseif(($_GET['supprimer']=="periodique")  && ($_SESSION['type'] == 1)) {
    if (!empty($_GET['id'])) {
      $u = $_GET['id'];
      $servername = "localhost";
      $usernameBD = "root";
      $passwordBD = "root";
      $dbname = "projet_intra";
      $conn = new mysqli($servername, $usernameBD, $passwordBD, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "DELETE FROM periodiques WHERE id=$u";

      if ($conn->query($sql) === TRUE) {
        echo "<div>Supression réussite</div>";
        header("Location: index.php?delete=ok");
      } else {
        echo "erreur: " . $sql . "<br>" . mysqli_error($conn);
      }
      mysqli_close($conn);
    } else {
      header("Location: index.php?");
      mysqli_close($conn);
    }
  } elseif (($_GET['supprimer']=="utilisateur")  && ($_SESSION['type'] == 1)) {
    if (!empty($_GET['id'])) {
      $u = $_GET['id'];
      $k = $_GET['emprunt'];
      $servername = "localhost";
      $usernameBD = "root";
      $passwordBD = "root";
      $dbname = "projet_intra";
      $conn = new mysqli($servername, $usernameBD, $passwordBD, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      if($k > 0) {
        $sql2 = "UPDATE periodiques p INNER JOIN emprunts e ON p.id = e.id_periodique SET p.emprunte=0 WHERE e.id_usager=$u";
        $sql = "DELETE FROM usagers WHERE id=$u";
        $sql1 = "DELETE FROM emprunts WHERE id_usager=$u";
              
        if (($conn->query($sql2) === TRUE) && ($conn->query($sql) === TRUE) && ($conn->query($sql1) === TRUE)) {
          echo "<div>Supression réussite</div>";
          header("Location: utilisateur.php?delete=ok");
        } else {
          echo "erreur: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);
      }
      else {
        $sql = "DELETE FROM usagers WHERE id=$u";
      }
      
      if (($conn->query($sql) === TRUE)) {
        echo "<div>Supression réussite</div>";
        header("Location: utilisateur.php?delete=ok");
      } else {
        echo "erreur: " . $sql . "<br>" . mysqli_error($conn);
      }
      mysqli_close($conn);
    } else {
      header("Location: utilisateur.php?");
      mysqli_close($conn);
    }
  }

  ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>