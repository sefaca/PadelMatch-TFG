<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../components/Header/Header.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <header>
        <nav class="links">
            <a href="#">Enlace 1</a>
            <a href="#">Enlace 2</a>
            <a href="#">Enlace 3</a>
            <a class="icon-nav-profile" href="../Profile/Profile.php">
                <i class="fa-solid fa-user"></i>
            </a>
            <i class="fas fa-bars" style="padding:12px; margin:0;"></i>
        </nav>
    </header>
    <div>
        <h1>Enter your information</h1>
    </div>
    <div>
        <form class="form" action="" method="post">
            <div class="test">
            <label for="username">Username:</label>
            <input class="checkbox" type="text" id="username" name="username" required>
            </div>

            <div class="test">
            <label for="email">Email:</label>
            <input class="checkbox" type="email" id="email" name="email" required>
            </div>

            <div class="test">
            <label for="skill_level">Skill Level (1-5):</label>
            <input class="checkbox" type="number" id="skill_level" name="skill_level" min="1" max="5" required>
            </div>

            <div class="test">
            <label for="password">Password:</label>
            <input class="checkbox" type="password" id="password" name="password" required>
            </div>

            <div class="test">
            <label for="confirm_password">Confirm Password:</label>
            <input class="checkbox" type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="process">
                <?php
                    require('../../../../../backend/utils/conection.php');
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                    } else {
                        echo "No user ID provided!";
                        exit;
                    }

                    if (isset($_POST['submit_button'])) {
                        $name=$_POST['username'];
                        $email=$_POST['email'];
                        $password=$_POST['password'];
                        $confirm_password=$_POST['confirm_password'];
                        $skill_level=$_POST['skill_level'];
                        if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['skill_level'])) {
                            if ($password== $confirm_password) {
                            $p=base::conect()->prepare('UPDATE Usuario SET Nombre=:n, CorreoElectronico=:e, Contrasena=:p, NivelHabilidad=:s WHERE ID=:d');
                            $p->bindValue(':n', $name);
                            $p->bindValue(':e', $email);
                            $p->bindValue(':p', $password);
                            $p->bindValue(':s', $skill_level);
                            $p->bindValue(':d', $id);
                            $p->execute();
                            header('Location: ../Users/Users.php');
                            exit();
                        } else {
                            echo 'Password doesnt match';
                        }
                    }
                    }
                ?>
            </div>

            <input class="submit" type="submit" value="Update" name="submit_button">
        </form>
    </div>
    
</body>
</html>