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
      <?php } elseif ($_GET['other'] == "false") { ?>
  <header>
      <div class="container-fluid">
          <div class="row">
              <div class="d-flex justify-content-end">
                <p class="m-4"><?php echo $_SESSION['matricule']; ?> (<?php echo ($_SESSION["type"] == 1 ? "Professeur" : "Étudiant"); ?>)</p>
                  <a href="index.php" class="btn btn-secondary m-3">Périodiques</a>
                  <?php echo ($_SESSION['type'] == 1 ? "<a href=\"utilisateur.php\" class=\"btn btn-secondary m-3\">Utilisateurs</a>" : "" ) ?>
                  <a href="deconnexion.php" class="btn btn-danger m-3">Déconnexion</a>
              </div>  
          </div>
      </div> 
  </header>
    
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

    $idUser = $_SESSION['id'];
    $sql = "SELECT * FROM periodiques p INNER JOIN emprunts e ON p.id = e.id_periodique WHERE id_usager='$idUser'";
    $result = $conn->query($sql);
    
    if (($result->num_rows > 0)) {
    ?>
      <div class="container d-flex flex-wrap">
        <?php
        while ($row = $result->fetch_assoc()) {
          if($row["emprunte"] == 1) {
        ?>
          <div class="m-3 carte">
              <a class="linkCard" href="retourner.php?id=<?php echo $row["id_periodique"]; ?>&emprunt=<?php echo $row["emprunte"]; ?>&other=false">
                <div class="carteBg" style="width: 18rem;">
                  <img src="<?php echo $row["img"]; ?>" class="card-img-top">
                  <div class="card-body p-2">
                    <h5 class="card-title"><?php echo $row["nom"]; ?></h5>
                    <p class="card-text"><?php echo $row["titre"]; ?></p>
                  </div>
                  <ul class="list-group list-group-flush carteBg">
                    <li class="list-group-item carteBg">#  <?php echo $row["numero"]; ?></li>
                    <li class="list-group-item carteBg"><?php echo $row["mois"] . " " . $row["annee"]; ?></li>  
                  </ul>
                </div>
              </a>
         </div>
        <?php
          }
        }
        ?>
        </tbody>
        </table>
      </div>
      <?php
      $conn->close();
    }
  }
  elseif ($_GET['other'] == "true") { ?>
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="d-flex justify-content-end">
                  <p class="m-4"><?php echo $_SESSION['matricule']; ?> (<?php echo ($_SESSION["type"] == 1 ? "Professeur" : "Étudiant"); ?>)</p>
                  <a href="index.php?other=true&idUser=<?php echo $_SESSION['idUser']; ?>" class="btn btn-secondary m-3">Périodiques</a>
                  <?php echo ($_SESSION['type'] == 1 ? "<a href=\"utilisateur.php\" class=\"btn btn-secondary m-3\">Utilisateurs</a>" : "" ) ?>
                  <a href="deconnexion.php" class="btn btn-danger m-3">Déconnexion</a>
                </div>  
            </div>
        </div> 
    </header>
    <h4 class="m-4">Retour de périodiques pour l'utilisateur : <?php echo $_SESSION['userMatricule']; ?> (<?php echo ($_SESSION["userType"] == 1 ? "Professeur" : "Étudiant"); ?>)</h4>
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
  
      $idUser = $_SESSION['idUser'];
      $sql = "SELECT * FROM periodiques p INNER JOIN emprunts e ON p.id = e.id_periodique WHERE id_usager='$idUser'";
      $result = $conn->query($sql);
      
      if (($result->num_rows > 0)) {
      ?>
        <div class="container d-flex flex-wrap">
          <?php
          while ($row = $result->fetch_assoc()) {
            if($row["emprunte"] == 1) {
          ?>
            <div class="m-3 carte">
                <a class="linkCard" href="retourner.php?id=<?php echo $row["id_periodique"]; ?>&emprunt=<?php echo $row["emprunte"]; ?>&other=true">
                  <div class="carteBg" style="width: 18rem;">
                    <img src="<?php echo $row["img"]; ?>" class="card-img-top">
                    <div class="card-body p-2">
                      <h5 class="card-title"><?php echo $row["nom"]; ?></h5>
                      <p class="card-text"><?php echo $row["titre"]; ?></p>
                    </div>
                    <ul class="list-group list-group-flush carteBg">
                      <li class="list-group-item carteBg">#  <?php echo $row["numero"]; ?></li>
                      <li class="list-group-item carteBg"><?php echo $row["mois"] . " " . $row["annee"]; ?></li>  
                    </ul>
                  </div>
                </a>
           </div>
          <?php
            }
          }
          ?>
          </tbody>
          </table>
        </div>
        <?php
        $conn->close();
      }
    }
    ?>
<script src="https://kit.fontawesome.com/2cfafb1177.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>