<?php

    class base{
        public static function conect()
        {
            try {
                $con=new PDO('mysql:localhost=host; dbname=app_padel', 'root', 'administrador');
                return $con;
            } catch (PDOException $error1) {
                echo 'Error' .$error1->getMessage();
            } catch (Exception $error2) {
                echo 'Generic error' .$error2->getMessage();
            }
        }

        public static function Selectdata()
        {
            $data=array();
            $p=base::conect()->prepare('SELECT * FROM Usuario');
            $p->execute();
            $data=$p->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }

        public static function SelectPlayerData()
        {
            $data=array();
            $p=base::conect()->prepare('SELECT * FROM Jugador');
            $p->execute();
            $data=$p->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }

        public static function delete($id)
        {
            $p=base::conect()->prepare('DELETE FROM Usuario WHERE ID=:id');
            $p->bindValue(':id',$id);
            $p->execute();
        }

        public static function getUserDataById($id) {
            $userData = array();
    
            try {
                $pdo = self::conect();
                $stmt = $pdo->prepare('SELECT * FROM Usuario WHERE ID = :id');
                $stmt->bindValue(':id', $id);
                $stmt->execute();
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
    
            return $userData;
        }

    }


?>