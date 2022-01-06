<?php 
    include("dbconnect.php");


    //Lekérdezés

    $sql = "SELECT * FROM foglalas ORDER BY fazon DESC";
    $request = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="reg.css"> 
        <title>Burger-Foglalások Listája</title>
    </head>
    <body>
        <br />
        <table>
            <thead style="font-weight:bold">
                <tr>
                    <td>fazon</td>
                    <td>azon</td>
                    <td>szemelydb</td>
                    <td>datum</td>
                    <td>idopont</td>
                    <td>ido</td>
                    <td>megjelent</td>
                </tr>
            </thead>
            <?php
                while ($sor = $request->fetch_assoc())
                    echo
                    "<tr>
                        <td>".$sor["fazon"]."</td>
                        <td>".$sor["azon"]."</td>
                        <td>".$sor["szemelydb"]."</td>
                        <td>".$sor["datum"]."</td>
                        <td>".$sor["idopont"]."</td>
                        <td>".$sor["ido"]."</td>
                        <td>".$sor["megjelent"]."</td>
                    </tr>";
            ?>
        </table>
        <br />
        <div class="udv">
            <form action="index.php">
                <button type="submit">Főoldal</button>
            </form>
        </div>
    </body>
</html>