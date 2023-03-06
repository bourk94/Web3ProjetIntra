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
  if(!empty($_GET['other'])) {
    $_SESSION['other'] = $_GET['other'];  
  }
  else {
    $_SESSION['other'] = false;
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function protection($data)
    {
      $data = trim($data);
      $data = addslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    $matricule = $_POST['matricule'];
    $matricule = protection($matricule);
    $password = $_POST['password'];
    $password = protection($password);
    $password = sha1($password, false);

    //Vérifier si l'usager est dans la BD, activer la session!
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

    $sql = "SELECT * FROM usagers where matricule='$matricule' and password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $_SESSION["connexion"] = true;
      $_SESSION['type'] = $row['type'];
      $_SESSION['id'] = $row['id'];
      $_SESSION['nbrEmprunt'] = $row['nbr_emprunt'];
      $_SESSION['matricule'] = $row['matricule'];
    } else {
      echo "<h2>Nom d'usager ou mot de passe invalide</h2>";
      //compter le nombre d'échecs
    }
    $conn->close();
  }
  ?>
  <?php if (!isset($_SESSION["connexion"]) or $_SESSION["connexion"] != true) { ?>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <form action="index.php" method="POST">
            <div class="mb-3">
              <label for="user" class="form-label">Matricule</label>
              <input type="text" class="form-control" id="matricule" name="matricule" required>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
              <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Connexion</button>
          </form>
        </div>
      </div>
    </div>
    <?php } else {
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
    $sql = "SELECT * FROM periodiques";
    $result = $conn->query($sql);

    if (($result->num_rows > 0) && ($_SESSION['type'] == '0')) {
    ?>
      <header>
        <div class="container-fluid">
          <div class="row">
            <div class="d-flex justify-content-end">
              <p class="m-4"><?php echo $_SESSION['matricule']; ?> (<?php echo ($_SESSION["type"] == 1 ? "Professeur" : "Étudiant"); ?>)</p>
              <a href="retour.php?other=false" class="btn btn-secondary m-3">Retour d'emprunts</a>
              <a href="deconnexion.php" class="btn btn-danger m-3">Déconnexion</a>
            </div>
          </div>
        </div>
      </header>
      <div class="container d-flex flex-wrap">
        <?php
        while ($row = $result->fetch_assoc()) {
          if ($row["emprunte"] == 0) {
        ?>
            <div class="m-3 carte">
              <a class="linkCard" href="emprunt.php?id=<?php echo $row["id"]; ?>&emprunt=<?php echo $row["emprunte"]; ?>&other=false">
                <div class="carteBg" style="width: 18rem;">
                  <img src="<?php echo $row["img"]; ?>" class="card-img-top" alt="...">
                  <div class="card-body p-2">
                    <h5 class="card-title"><?php echo $row["nom"]; ?></h5>
                    <p class="card-text"><?php echo $row["titre"]; ?></p>
                  </div>
                  <ul class="list-group list-group-flush carteBg">
                    <li class="list-group-item carteBg"># <?php echo $row["numero"]; ?></li>
                    <li class="list-group-item carteBg"><?php echo $row["mois"] . " " . $row["annee"]; ?></li>
                  </ul>
                </div>
              </a>
            </div>
          <?php
          } elseif ($row["emprunte"] == 1) {
          ?>
            <div class="m-3 emprunte">
              <div class="carteBg" style="width: 18rem;">
                <img src="<?php echo $row["img"]; ?>" class="card-img-top" alt="...">
                <div class="card-body p-2">
                  <h5 class="card-title"><?php echo $row["nom"]; ?></h5>
                  <p class="card-text"><?php echo $row["titre"]; ?></p>
                </div>
                <ul class="list-group list-group-flush carteBg">
                  <li class="list-group-item carteBg"># <?php echo $row["numero"]; ?></li>
                  <li class="list-group-item carteBg"><?php echo $row["mois"] . " " . $row["annee"]; ?></li>
                </ul>
              </div>
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
    } elseif (($result->num_rows > 0) && ($_SESSION['type'] == '1') && ($_SESSION['other'] == "true")) {
      if (!empty($_GET['idUser'])) {
        $_SESSION['idUser'] = $_GET['idUser'];
        $idUser = $_GET['idUser'];
      }
      $sql1 = "SELECT * FROM usagers WHERE id = $idUser";

      $result1 = $conn->query($sql1);

    if ($result1->num_rows > 0) {
      $row1 = $result1->fetch_assoc();
      $_SESSION['nbrEmpruntUser'] = $row1['nbr_emprunt'];
      $_SESSION['userMatricule'] = $row1['matricule'];
      $_SESSION['userType'] = $row1['type'];
    }
    ?>
      <header>
        <div class="container-fluid">
          <div class="row">
            <div class="d-flex justify-content-end">
              <p class="m-4"><?php echo $_SESSION['matricule']; ?> (<?php echo ($_SESSION["type"] == 1 ? "Professeur" : "Étudiant"); ?>)</p>
              <a href="utilisateur.php" class="btn btn-secondary m-3">Utilisateurs</a>
              <a href="retour.php?other=true" class="btn btn-secondary m-3">Retour d'emprunts</a>
              <a href="deconnexion.php" class="btn btn-danger m-3">Déconnexion</a>
            </div>
          </div>
        </div>
      </header>

      <h4 class="m-4">Emprunt de périodiques pour l'utilisateur : <?php echo $_SESSION['userMatricule']; ?> (<?php echo ($_SESSION["userType"] == 1 ? "Professeur" : "Étudiant"); ?>)</h4>

      <div class="container d-flex flex-wrap">
          <?php
          while ($row = $result->fetch_assoc()) {
            if ($row["emprunte"] == 0) {
          ?>
              <div class="m-3 carte">
                <a class="linkCard" href="emprunt.php?id=<?php echo $row["id"]; ?>&emprunt=<?php echo $row["emprunte"]; ?>&other=true">
                  <div class="carteBg" style="width: 18rem;">
                    <img src="<?php echo $row["img"]; ?>" class="card-img-top">
                    <div class="card-body p-2">
                      <h5 class="card-title"><?php echo $row["nom"]; ?></h5>
                      <p class="card-text"><?php echo $row["titre"]; ?></p>
                    </div>
                    <ul class="list-group list-group-flush carteBg">
                      <li class="list-group-item carteBg"># <?php echo $row["numero"]; ?></li>
                      <li class="list-group-item carteBg"><?php echo $row["mois"] . " " . $row["annee"]; ?></li>
                    </ul>
                  </div>
                </a>
              </div>
          <?php
          } elseif ($row["emprunte"] == 1) {
          ?>
            <div class="m-3 emprunte">
              <div class="carteBg" style="width: 18rem;">
                <img src="<?php echo $row["img"]; ?>" class="card-img-top" alt="...">
                <div class="card-body p-2">
                  <h5 class="card-title"><?php echo $row["nom"]; ?></h5>
                  <p class="card-text"><?php echo $row["titre"]; ?></p>
                </div>
                <ul class="list-group list-group-flush carteBg">
                  <li class="list-group-item carteBg"># <?php echo $row["numero"]; ?></li>
                  <li class="list-group-item carteBg"><?php echo $row["mois"] . " " . $row["annee"]; ?></li>
                  </li>
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
    }
    elseif (($result->num_rows > 0) && ($_SESSION['type'] == '1')) {
    ?>
      <header>
        <div class="container-fluid">
          <div class="row">
            <div class="d-flex justify-content-end">
              <p class="m-4"><?php echo $_SESSION['matricule']; ?> (<?php echo ($_SESSION["type"] == 1 ? "Professeur" : "Étudiant"); ?>)</p>
              <a href="utilisateur.php" class="btn btn-secondary m-3">Utilisateurs</a>
              <a href="retour.php?other=false" class="btn btn-secondary m-3">Retour d'emprunts</a>
              <a href="deconnexion.php" class="btn btn-danger m-3">Déconnexion</a>
            </div>
          </div>
        </div>
      </header>
        <div>
          <a href="ajouter.php?ajouter=periodique" class="btn btn-primary m-3"><i class="fa-solid fa-circle-plus"></i> Ajouter</a>
        </div>
      <div class="container d-flex flex-wrap">
          <?php
          while ($row = $result->fetch_assoc()) {
            if ($row["emprunte"] == 0) {
          ?>
              <div class="m-3 carte">
                <a class="linkCard" href="emprunt.php?id=<?php echo $row["id"]; ?>&emprunt=<?php echo $row["emprunte"]; ?>&other=false">
                  <div class="carteBg" style="width: 18rem;">
                    <img src="<?php echo $row["img"]; ?>" class="card-img-top">
                    <div class="card-body p-2">
                      <h5 class="card-title"><?php echo $row["nom"]; ?></h5>
                      <p class="card-text"><?php echo $row["titre"]; ?></p>
                    </div>
                    <ul class="list-group list-group-flush carteBg">
                      <li class="list-group-item carteBg"># <?php echo $row["numero"]; ?></li>
                      <li class="list-group-item carteBg"><?php echo $row["mois"] . " " . $row["annee"]; ?></li>
                      <li class="list-group-item carteBg">
                        <a href="modifier.php?id=<?php echo $row["id"];?>&modifier=periodique" class="btn btn-sm btn-success"><i class="fa-solid fa-gear"></i> Modifier</a>
                        <?php
                        if ($row["emprunte"] == 0) {
                        ?>
                          <a href="supprimer.php?id=<?php echo $row["id"]; ?>&supprimer=periodique" class="btn btn-sm  btn-danger"><i class="fa-solid fa-trash-can"></i> Supprimer</a>
                        <?php
                        }
                        ?>
                      </li>
                    </ul>
                  </div>
                </a>
              </div>
          <?php
          } elseif ($row["emprunte"] == 1) {
          ?>
            <div class="m-3 emprunte">
              <div class="carteBg" style="width: 18rem;">
                <img src="<?php echo $row["img"]; ?>" class="card-img-top" alt="...">
                <div class="card-body p-2">
                  <h5 class="card-title"><?php echo $row["nom"]; ?></h5>
                  <p class="card-text"><?php echo $row["titre"]; ?></p>
                </div>
                <ul class="list-group list-group-flush carteBg">
                  <li class="list-group-item carteBg"># <?php echo $row["numero"]; ?></li>
                  <li class="list-group-item carteBg"><?php echo $row["mois"] . " " . $row["annee"]; ?></li>
                  <li class="list-group-item carteBg">
                    <a href="modifier.php?id=<?php echo $row["id"]; ?>" class="btn btn-sm btn-success"><i class="fa-solid fa-gear"></i> Modifier</a>
                  </li>
                </ul>
              </div>
              </a>
            </div>
    <?php
          }
        }
      }
    }
    ?>
    </tbody>
    </table>
      </div>
      <script src="https://kit.fontawesome.com/2cfafb1177.js" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>