<?php
    include("dbconnect.php");
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        if(isset($_POST["email"]) && !empty($_POST["email"]) &&
            isset($_POST["pw1"]) && !empty($_POST["pw1"]))
            {
                $email = $_POST["email"];
                $pw1 = $_POST["pw1"];
                $hashpw = md5($pw1);
                
                //Felhasználónév lekérés ellenőrzéshez
                $sql2 = "SELECT * FROM felhasznalo WHERE email = '$email'";
                $result2 = $db->query($sql2);
                
                //belépési jelszó lekérés ellenőrzéshez
                $sql3 = "SELECT * FROM felhasznalo WHERE pw = '$hashpw'";
                $result3 = $db->query($sql3);

                //Itt megyünk végig a tényleges ellenőrzéseken
                if($result2->num_rows < 1){
                    echo "<script>alert('A megadott email címmel nincs regisztráció!')</script>";
                    echo "<script>location.href = 'belepes.php'</script>";;
                }

                elseif($result3->num_rows < 1){
                    echo "<script>alert('A megadott jelszó nem megfelelő!')</script>";
                    echo "<script>location.href = 'belepes.php'</script>";
                }

                else{   //Ha minden rendben, beléptetjük
                    echo "<script>alert('Köszöntjük weboldalunkon!')</script><br />";
                    //Azonosítószám és név kinyerése db-ből
                    $sql1 = "SELECT azon, nev, pw FROM felhasznalo WHERE email = '$email' AND pw = '$hashpw'";
                    $result1 = $db->query($sql1);

                    if ($result1->num_rows > 0){
                        $row = $result1->fetch_assoc();
                        $azon = $row['azon'];
                        $nev = $row['nev'];
                        $_SESSION['Azonosito'] = $azon;
                        $_SESSION['Felhasznalonev'] = $nev;
                        //$_SESSION['Jelszo'] = $hashpw;
                    }
                }
                echo "<script>location.href = 'foglalas.php'</script>";
            }
    }        
    //Lekérdezés

    $sql1 = "SELECT * FROM felhasznalo ORDER BY azon DESC LIMIT 4 ";
    $request = $db->query($sql1);
    
?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="reg.css"> 
        <title>Burgeretterem-Belépés</title>
    </head>
    <body>  
        
        <br />
        <form method = "POST" action="">
            <table class = "ujfelhasznalo">
                <tr><td><strong>Belépés:</strong></td></tr>
                <tr>
                    <td>Email cím:</td>
                    <td><input type="text" name="email" style="width: 240px;"></td>
                </tr>
                <tr>
                    <td>Jelszó:</td>
                    <td><input type="password" name="pw1" style="width: 240px;"></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Belépek"></td>
                </tr>
            </table>
        </form>
    </body>
</html>