<?php
    
    include("dbconnect.php");
    
    /*
    $conn = new mysqli('localhost','root','','burgeretterem');
    if($conn -> connect_errno){
        echo $conn -> connect_error;
        die();
    }
    */
    //Lekérdezés

    $sql1 = "SELECT * FROM felhasznalo";
    $request = $db->query($sql1);

?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="reg.css"> 
        <title>Burgeretterem-Felhasználók</title>
    </head>
    <body>  
        <table>
            <thead style="font-weight:bold">
                <tr>
                    <td>Azonosító</td>
                    <td>Név</td>
                    <td>Lakhely</td>
                    <td>Telefonszám</td>
                    <td>E-mail cím</td>
                    <td>Jogosultság</td>
                    <td>Jelszó</td>
                </tr>
            </thead>
            <?php
                while ($sor = $request->fetch_assoc())
                    echo "  <tr>
                                <td>".$sor["azon"]."</td>
                                <td>".$sor["nev"]."</td>
                                <td>".$sor["lak"]."</td>
                                <td>".$sor["tel"]."</td>
                                <td>".$sor["email"]."</td>
                                <td>".$sor["jog"]."</td>
                                <td>".$sor["pw"]."</td>
                            </tr>";
            ?>
        </table>    
        <br />
        <form action="regisztracio.php">
            <button type="submit">Regisztráció</button>
        </form>
        <br />
        <form action="belepes.php">
            <button type="submit">Belépés</button>
        </form>
    </body>
</html>