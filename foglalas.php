<?php 
    include("dbconnect.php");

    session_start();
    
    //echo "Az alábbi adatokkal léptél be: <br />";
    //echo "<pre>";
    //print_r($_SESSION);
    //echo "</pre>";

    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {      
        if(isset($_POST["szemelydb"]) && !empty($_POST["szemelydb"]) &&
        isset($_POST["datum"]) && !empty($_POST["datum"]) &&
        isset($_POST["idopont"]) && !empty($_POST["idopont"]))
            {
                $azon = $_SESSION['Azonosito'];
                $nev = $_SESSION['Felhasznalonev'];
                $azon2 = $_POST['azon2'];
                $szemelydb = $_POST["szemelydb"];
                $datum = $_POST["datum"];
                $idopont = $_POST["idopont"];
                
                //Személy darabszám ellenőrzés
                /*$min = 1;
                $max = 8;
                if (filter_var($szemelydb, FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min, "max_range"=>$max))) === false)
                    {
                        echo "<script>alert('A megadható személyek száma 1 és 8 közötti lehet!')</script>";
                        header("foglalas.php");
                    }*/

                //dátum és időpont lekérése ellenőrzéshez
                $sql5 = "SELECT idopont FROM foglalas WHERE datum = '$datum' AND idopont = '$idopont'";
                $result5 = $db->query($sql5);

                //dátum és időpont tényleges ellenőrzése
                if($result5 -> num_rows > 6){
                    echo "<script>alert('Az Ön által megadott időpontra már nem lehetséges foglalás!        Kérjük adjon meg egy másik időpontot!')</script>";
                    echo "<script>location.href='foglalas.php'</script>";
                }

                //Ha minden rendben, az új foglalást felvesszük a db-be
                else{   
                    $sql4 = "INSERT INTO foglalas (fazon, azon, szemelydb, datum, idopont, ido, megjelent) VALUES
                    (null, '$azon2', '$szemelydb', '$datum', '$idopont', now(), 0); ";       
                    $request4 = $db->query($sql4);
                    echo "<script>alert('Köszönjük a foglalást!')</script>";
                    header("Refresh:0");  // Ne ragadjonak be az adatok!!!!
                    echo "<script>location.href='foglalas.php'</script>";
                }
            }
    }   
    //Lekérdezés

    $sql = "SELECT * FROM foglalas ORDER BY fazon DESC limit 6";
    $request = $db->query($sql);
    
?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="reg.css"> 
        <title>Burgeretterem-Foglalás</title>
    </head>
    <body>
        <div class="udv">
            <h1> Üdvözöljük <?php echo " ".$_SESSION['Felhasznalonev']//."  ".$_SESSION['Azonosito'] ?> !</h1>
            <h2> Foglalásához kérjük adja meg a személyek számát, a dátumot és az időpontot!</h2>
        </div>
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
        <form method="POST" action= "">
            <table class="ujfelhasznalo">
                <tr class="hidden">
                    <td><input type="text" name="azon2" value="<?php echo $_SESSION['Azonosito'] ?>" ></td>
                </tr>
                <tr>
                    <td><label>Személyek száma: </label></td>
                    <td><select name="szemelydb">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                        </select>
                        <label> fő </label>
                    </td>
                </tr>
                <tr>
                    <td><label>Dátum: </label></td>
                    <td><input type="date" name="datum"></td>
                </tr>
                <tr>
                    <td><label>Időpont:</label></td>
                    <td><select name="idopont">
                            <option>16:00:00</option>
                            <option>17:00:00</option>
                            <option>18:00:00</option>
                            <option>19:00:00</option>
                            <option>20:00:00</option>
                            <option>21:00:00</option>
                        </select>
                    </td>
                </tr>
                <!--<tr>
                    <td><label>Időpont: </label></td>
                    <td><input type="time" name="idopont"></td>
                </tr>-->
                <tr>
                    <td><button type="submit">Foglalom!</button></td>
                </tr>
            </table>
        </form>
        <br />
        <form action="foglalasaim.php">
            <table class="ujfelhasznalo">
                <tr><td><button type="submit">Előző foglalásaim</button></td><td></td></tr>
            </table>
        </form>
        <br />
        <form action="index.php">
            <table class="ujfelhasznalo">
                <tr><td><button type="submit">Kilépés</button></td><td></td></tr>
            </table>
        </form>
        
    </body>
</html>