<?php 
    include("dbconnect.php");
    session_start();

    echo "Az alábbi adatokkal vagy itt: <br />";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    //Lekérdezés
    $azon = $_SESSION['Azonosito'];
    echo $azon;
    $sql = "SELECT * FROM foglalas WHERE azon = '$azon'";
    $request = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="reg.css"> 
        <title>Burger-Foglalásaim Listája</title>
    </head>
    <body>
        <br />
        <table>
            <thead style="font-weight:bold">
                <tr>
                    <td>Azonosítóm</td>
                    <td>Személyek száma</td>
                    <td>Dátum</td>
                    <td>Időpont</td>
                    <td>Foglalás ideje</td>
                </tr>
            </thead>
            <?php
                while ($sor = $request->fetch_assoc())
                    echo
                    "<tr>
                        <td>".$sor["azon"]."</td>
                        <td>".$sor["szemelydb"]."</td>
                        <td>".$sor["datum"]."</td>
                        <td>".$sor["idopont"]."</td>
                        <td>".$sor["ido"]."</td>
                    </tr>";
            ?>
        </table>
        <br />
        <form action="foglalas.php">
            <tr class="ujfelhasznalo"><td><button type="submit">Vissza a foglaláshoz</button></td></tr>
        </form>
        
    </body>
</html>