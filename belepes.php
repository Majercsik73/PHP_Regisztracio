<?php
    include("dbconnect.php");
    /*
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        if(isset($_POST["nev"]) && !empty($_POST["nev"]) &&
            isset($_POST["pw1"]) && !empty($_POST["pw1"]))
            {
                
                $nev = $_POST["nev"];
                $pw1 = $_POST["pw1"];

                $hashpw = md5($pw1);
                
                //Felhasználónév lekérés ellenőrzéshez
                $sql2 = "SELECT * FROM felhasznalo WHERE nev = '$nev'";
                $result2 = $db->query($sql2);
                
                //belépési jelszó lekérés ellenőrzéshez
                $sql3 = "SELECT * FROM felhasznalo WHERE pw = '$hashpw'";
                $result3 = $db->query($sql3);

                //Itt megyünk végig a tényleges ellenőrzéseken
                if($result2->num_rows < 1){
                    echo "<script>alert('A megadott felhasználónévvel nincs regisztráció!')</script>";
                    header("belepes.php");
                }

                elseif($result3->num_rows < 1){
                    echo "<script>alert('A megadott jelszó nem megfelelő!')</script>";
                    header("belepes.php");
                }

                else{   //Ha minden rendben, beléptetjük
                    echo "<script>alert('Köszöntjük weboldalunkon!')</script>";
                    echo "<script>location.href = 'foglalas.php'</script>";
                    //header("foglalas.php");  //header("index.php");  // Ne ragadjonak be az adatok!!!!
                }  
            }
    }
    */
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
        <form method = "POST" action="foglalas.php">
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