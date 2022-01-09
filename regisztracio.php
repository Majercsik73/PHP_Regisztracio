
<?php
    include("dbconnect.php");
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        if(isset($_POST["nev"]) && !empty($_POST["nev"]) &&
            isset($_POST["lak"]) && !empty($_POST["lak"]) &&
            isset($_POST["tel"]) && !empty($_POST["tel"]) &&
            isset($_POST["email"]) && !empty($_POST["email"]) &&
            isset($_POST["pw1"]) && !empty($_POST["pw1"]) &&
            isset($_POST["pw2"]) && !empty($_POST["pw2"]))
            {
                
                $nev = $_POST["nev"];
                $lak = $_POST["lak"];
                $tel = $_POST["tel"];
                $email = $_POST["email"];
                $pw1 = $_POST["pw1"];
                $pw2 = $_POST["pw2"];
                
                //Felhasználó név lekérés ellenőrzéshez
                $sql2 = "SELECT * FROM felhasznalo WHERE nev = '$nev'";
                $result2 = $db->query($sql2);
                
                //Email cím lekérés ellenőrzéshez
                $sql3 = "SELECT * FROM felhasznalo WHERE email = '$email'";
                $result3 = $db->query($sql3);

                //Ellenőrzés Regex paranccsal: a jelszó tartalmaz-e kisbetűt, nagybetűt, számokat --> lentebb használjuk az 61.sorban
                preg_match('/[0-9]+/', $pw1, $matches1);
                preg_match('/[a-z]+/', $pw1, $matches2);
                preg_match('/[A-Z]+/', $pw1, $matches3);

                //Itt megyünk végig a tényleges ellenőrzéseken
                if($result2->num_rows > 0){
                    echo "<script>alert('A felhasználónév már létezik, adj meg egy másikat!')</script>";
                    header("regisztracio.php");

                }
                //elseif
                elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<script>alert('A megadott email-cím nem helyes!')</script>";
                    header("regisztracio.php");
                }

                elseif($result3->num_rows > 0){
                    echo "<script>alert('Ezzel az e-mail címmel már regisztráltak, adj meg egy másikat!')</script>";
                    header("regisztracio.php");
                }

                elseif($pw1 != $pw2){  //A bekért két jelszómező ellenőrzése
                    echo "<script>alert('A megadott két jelszó nem egyezik!')</script>";
                    header("regisztracio.php");
                }

                elseif(strlen($pw1) < 6){  //A jelszó hosszának ellenőrzése
                    echo "<script>alert('A jelszónak minimum 6 karakter hosszúságúnak kell lennie!')</script>";
                    header("regisztracio.php");
                }

                elseif(sizeof($matches1) == 0 || sizeof($matches2) == 0 || sizeof($matches3) == 0){  //Fenntről a Regex-es ellenőrzése
                    echo "<script>alert('A jelszónak tartalmaznia kell kisbetűt, nagybetűt és számot!')</script>";
                    header("regisztracio.php");
                }

                else{   //Ha minden rendben, az új felhasználót felvesszük a db-be
                    //Jelszó md5 hash
                    $hashpw = md5($pw1);
                    // Új felhasználó regisztálása
                    $sql4 = "INSERT INTO felhasznalo (azon, nev, lak, tel, email, jog, pw) VALUES
                    (null, '$nev','$lak','$tel','$email', 0 ,'$hashpw'); ";
                    
                    $request = $db->query($sql4);
                    
                    echo "<script>alert('Köszönjük a regisztrációt!')</script>";

                    header("Refresh:0");  //header("index.php");  // Ne ragadjonak be az adatok!!!!
                    echo "<script>location.href='index.php'</script>";
                }  
            }
    }
    
    //Lekérdezés

    $sql1 = "SELECT * FROM felhasznalo ORDER BY azon DESC LIMIT 4 ";
    $request = $db->query($sql1);
?>

<script>
    function formEllenorzes() {
        // Név ellenőrzése
        let a = document.forms["regist"]["nev"].value;
        if (a == "" || a == null) {
            alert("Név megadása kötelező!");
            return false;
        }
        /*if (a.length > 0){
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                ;
            }
            xhttp.open("GET", "nevellenorzes.php?q="+a);
            xhttp.send();
        }*/
        // Lakcím ellenőrzése
        let b = document.forms["regist"]["lak"].value;
        else if (b == "" || b == null) {
            alert("Lakcím megadása kötelező!");
            return false;
        }
        // Telefonszám ellenőrzés
        let c = document.forms["regist"]["tel"].value;
        else if (c == "" || c == null) {
            alert("Telefonszám megadása kötelező!");
            return false;
        }

        else if (c.length < 10) {
            alert("Telefonszám túl rövid! Kérem használjon előhívó tagot és körzetszámot is!");
            return false;
        }

        let tel1 = c.match(/[A-z]/g);
        else if (tel1.length > 0 ) {
            alert("Telefonszám csak '+' előtagot és számokat tartalmazhat!");
            return false;
        }

        // Email cím ellenőrzés
        let d = document.forms["regist"]["email"].value;
        else if (d == "" || d == null) {
            alert("Email cím megadása kötelező!");
            return false;
        }
        let email = String(d);
        alert(email);
        // Jelszómezők ellenőrzése
        // Első mező
        let e = document.forms["regist"]["pw1"].value;
        else if (e == "" || e == null) {
            alert("Az első jelszómező nincs kitöltve");
            return false;
        }
        alert(e);
        //Második mező
        let f = document.forms["regist"]["pw2"].value;
        else if(f == "" || f == null) {
            alert("A második jelszómező nincs kitöltve");
            return false;
        }
        alert(f);
        //Jelszavak összehasonlítás
        else if (e!=f) {
            alert("A két beírt jelszó nem egyezik meg! JS");
            return false;
        }
        let jelszo1 = e.match(/[a-z]/g);
        let jelszo2 = e.match(/[0-9]/g);
        else (jelszo1.length == 0 || jelszo2.length == 0) {
            alert("A jelszónak tartalmaznia kell kisbetűt, nagybetűt és számot!  JS");
            return false;
        }
    }
</script>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="reg.css"> 
        <title>Burgeretterem-Regisztráció</title>
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
        <form name="regist" method = "POST" action="" onsubmit="return formEllenorzes()">
            <table class = "ujfelhasznalo">
                <tr><td><strong>Regisztráció:</strong></td></tr>
                <!--<tr class="hidden">
                    <td>Azonosító:</td>
                    <td><input type="text" name="azon" value="55" ></td>
                </tr>-->
                <tr>
                    <td>Név:</td>
                    <td><input type="text" name="nev" id="nev" style="width: 240px;" placeholder="teljes név"></td>
                </tr>
                <tr>
                    <td>Lakhely:</td>
                    <td><input type="text" name="lak" id="lak" style="width: 240px;" placeholder="teljes lakcím"></td>
                </tr>
                <tr>
                    <td>Telefonszám:</td>
                    <td><input type="text" name="tel" id="tel" style="width: 240px;" placeholder="pl.:+36801111111"></td>
                </tr>
                <tr>
                    <td>E-mail cím:</td>
                    <td><input type="email" name="email" id="email" style="width: 240px;" placeholder="valami@valami.com"></td>
                </tr>
                <tr>
                    <td>Jelszó:</td>
                    <td><input type="password" name="pw1" id="pw1" style="width: 240px;"placeholder="min. 6 karakter; kibetű, nagybetű, szám"></td>
                </tr>
                <tr>
                    <td>Jelszó újra:</td>
                    <td><input type="password" name="pw2" id="pw2" style="width: 240px;" placeholder="jelszó megerősítése"></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Regisztrálok"></td>
                </tr>
            </table>
        </form>
    </body>
</html>