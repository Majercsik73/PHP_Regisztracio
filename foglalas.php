<?php 
    include("dbconnect.php");

    session_start();
    $nev = '';
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
                    //session_destroy();

                    echo "Az alábbi adatokkal léptél be: <br />";
                    echo "Session = <br />";
                    echo "<pre>";   //<br />Azonosító: ".$azon.
                    print_r($_SESSION); //"<br />Felhasználónév: ".$nev.
                    echo "</pre>";   //"<br />Jelszó: ".$hashpw."<br />";
                }
            }
    }        

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {      
        if(isset($_POST["szemelydb"]) && !empty($_POST["szemelydb"]) &&
        isset($_POST["datum"]) && !empty($_POST["datum"]) &&
        isset($_POST["idopont"]) && !empty($_POST["idopont"]))
            {
                //$nev2 = $_POST["nev2"];
                $azon2 = $_POST["azon2"];
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
                    echo "<script>alert('Az Ön által megadott időpontra már nem lehetséges foglalás!')</script>";
                    header("foglalas.php");
                }

                //Ha minden rendben, az új foglalást felvesszük a db-be
                else{   
                    $sql4 = "INSERT INTO foglalas (fazon, azon, szemelydb, datum, idopont, ido, megjelent) VALUES
                    (null, '$azon2', '$szemelydb', '$datum', '$idopont', now(), 0); ";       
                    $request4 = $db->query($sql4);
                    echo "<script>alert('Köszönjük a foglalást!')</script>";
                    header("Refresh:0");  // Ne ragadjonak be az adatok!!!!
                    echo "<script>location.href='index.php'</script>";
                }
            }
    }   
    session_destroy();
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
        <h1> Üdvözöljük <?php echo $nev ?> !</h1>
        <h2> Foglalásához kérjük adja meg a személyek számát, a dátumot és az időpontot!</h2>
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
                    <!--<td><input type="text" name="nev2" value="<?php echo $nev ?>"></td>-->
                    <td><input type="text" name="azon2" value="<?php echo $azon ?>" ></td>
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
                    <td><button type="submit">Foglalás!</button></td>
                </tr>
            </table>
        </form>
        <form action="index.php">
            <button type="submit">Főoldal</button>
        </form>
        
    </body>
</html>