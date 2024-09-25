<?php
session_start();
if (!empty($_SESSION['active'])) {
    header('location: src/');
} else {
    if (!empty($_POST)) {
        $alert = '';
        if (empty($_POST['correo']) || empty($_POST['pass'])) {
            $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Ingrese correo y contraseña
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        } else {
            require_once "conexion.php";
            $user = mysqli_real_escape_string($conexion, $_POST['correo']);
            $pass = md5(mysqli_real_escape_string($conexion, $_POST['pass']));
            $query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo = '$user' AND pass = '$pass'");
            mysqli_close($conexion);
            $resultado = mysqli_num_rows($query);
            if ($resultado > 0) {
                $dato = mysqli_fetch_array($query);
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $dato['id'];
                $_SESSION['nombre'] = $dato['nombre'];
                $_SESSION['rol'] = $dato['rol'];
                header('Location: src/dashboard.php');
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Contraseña incorrecta
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                session_destroy();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">

  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Rest</b>Bar</a>
  </div>
  <style>
  body {
    background-image: url('assets/img/fondo login.png');
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;
  }

  .card {
    background-color: rgba(255, 255, 255, 0); /* Transparente */
    border: 2px solid black; /* Borde negro */
  }

  .login-card-body {
    background-color: rgba(255, 255, 255, 0.5); /* Semi-transparente */
  }

  .form-control {
    border: 2px solid black; /* Borde negro para los campos de entrada */
    background-color: rgba(255, 255, 255, 0.8); /* Fondo ligeramente transparente */
  }

  .input-group-text {
    border: 2px solid black; /* Borde negro para el ícono */
    background-color: rgba(255, 255, 255, 0.8); /* Fondo ligeramente transparente */
  }

  /* Estilo para cambiar el color, tamaño y negrita de "RestBar" */
  .login-logo a {
    color: black !important; /* Cambia "RestBar" a negro */
    font-size: 2.5rem; /* Incrementa el tamaño del texto */
    font-weight: bold; /* Aplica negrita */
  }

  .login-box-msg {
    color: black; /* Cambia "Inicio de sesión" a negro */
  }
</style>



  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inicio de sesión</p>

      <form action="" method="post" autocomplete="off">
      <?php echo (isset($alert)) ? $alert : '' ; ?>  
      <div class="input-group mb-3">
          <input type="email" class="form-control" name="correo" placeholder="correo">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="pass" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">

          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>

        </div>
      </form>
    </div>

  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>

<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="assets/dist/js/adminlte.min.js"></script>
</body>
</html>
