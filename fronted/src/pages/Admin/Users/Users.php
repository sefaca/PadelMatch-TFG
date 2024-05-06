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

    <h2>Usuarios:</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Skill Level</th>
                <th>Photo</th>
                <th>Delete</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
        <?php
        require('../../../../../backend/utils/conection.php');
            $p=base::Selectdata();
            if (isset($_GET['id'])) {
                $id=$_GET['id'];
                $e=base::delete($id);
            }
            if (count( $p)>0) {
                for ($i=0; $i < count( $p); $i++) {
                    echo '<tr>';
                    foreach ( $p[$i] as $key => $value) {
                    if ($key!='id') {
                        echo '<td>'.$value.'</td>';
                    }
                    }
                    ?>
                    <td><a href="Users.php?id=<?php echo $p[$i]['ID'] ?>">Delete</a></td>
                    <td><a href=".././Update/Update.php?id=<?php echo $p[$i]['ID'] ?>">Edit</a></td>

                    <?php


                    echo '</tr>';
                }
            }
    ?>
    </table>
    <h2>Jugadores:</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Playing position</th>
                <th>Dominant Hand</th>
                <th>Profile Picture</th>
                <th>Match ID</th>
                <th>Delete</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $p=base::SelectPlayerData();
            if (isset($_GET['id'])) {
                $id=$_GET['id'];
                $e=base::delete($id);
            }
            if (count( $p)>0) {
                for ($i=0; $i < count( $p); $i++) {
                    echo '<tr>';
                    foreach ( $p[$i] as $key => $value) {
                    if ($key!='id') {
                        echo '<td>'.$value.'</td>';
                    }
                    }
                    ?>
                    <td><a href="Users.php?id=<?php echo $p[$i]['ID'] ?>">Delete</a></td>
                    <td><a href=".././Update/Update.php?id=<?php echo $p[$i]['ID'] ?>">Edit</a></td>

                    <?php


                    echo '</tr>';
                }
            }
    ?>
    </table>
   

</body>
</html>