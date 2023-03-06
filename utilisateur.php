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
    $servername = "localhost";
    $usernameDB = "root";
    $passwordDB = "root";
    $dbname = "projet_intra";

    // Create connection
    $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM usagers";
    $result = $conn->query($sql);
    if (!isset($_SESSION["connexion"]) or $_SESSION["connexion"] != true) { ?>

      <h1>Il faut être connecté pour voir cette page.</h1>

      <a class="btn btn-primary" href="index.php"> Connectez-vous</a>
      <?php
    }
  elseif ($result->num_rows > 0) {
    ?>
    <header>
        <div class="container-fluid">
          <div class="row">
            <div class="d-flex justify-content-end">
              <p class="m-4"><?php echo $_SESSION['matricule']; ?> (<?php echo ($_SESSION["type"] == 1 ? "Professeur" : "Étudiant"); ?>)</p>
              <a href="index.php" class="btn btn-secondary m-3">Périodiques</a>
              <a href="retour.php?other=false" class="btn btn-secondary m-3">Retour d'emprunts</a>
              <a href="deconnexion.php" class="btn btn-danger m-3">Déconnexion</a>
            </div>
          </div>
        </div>
      </header>
      <div class="container">
        <div class="row">
            <div>
                <a href="ajouter.php?ajouter=utilisateur" class="btn btn-primary m-3"><i class="fa-solid fa-circle-plus"></i> Ajouter</a>
            </div>
            <table class="table table-dark">
            <thead>
                <tr>
                <th scope="col">id</th>
                <th scope="col">Matricule</th>
                <th scope="col">Adresse courriel</th>
                <th scope="col">Type</th>
                <th scope="col">Nombre d'emprunts</th>
                <th scope="col">Emprunter/Retourner</th>
                <th scope="col">Modifier</th>
                <th scope="col">Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["matricule"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo ($row["type"] == 1 ? "Professeur" : "Étudiant"); ?></td>
                    <td><?php echo $row["nbr_emprunt"]; ?></td>
                    <td>
                        <?php if ($_SESSION["matricule"] != $row['matricule']) { ?>
                            <a href="index.php?other=true&idUser=<?php echo $row["id"]; ?>" class="btn btn-primary  btn-sm"><i class="fa-solid fa-circle-plus"></i></a>
                        <?php } ?>
                    </td>
                    <td><a href="modifier.php?id=<?php echo $row["id"];?>&modifier=utilisateur" class="btn btn-success btn-sm"><i class="fa-solid fa-gear"></i></a></td>
                    <td>
                        <?php if ($_SESSION["matricule"] != $row['matricule']) { ?>
                            <a href="supprimer.php?id=<?php echo $row["id"];?>&emprunt=<?php echo $row["nbr_emprunt"];?>&supprimer=utilisateur" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
            </table>
        </div>
      </div>
      <?php
      }
  
    ?>   
    <script src="https://kit.fontawesome.com/2cfafb1177.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>