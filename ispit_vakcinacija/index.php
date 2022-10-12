<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Početna</title>
    <script src='js/jquery-3.6.0.js'></script>
    <script src='js/index.js'></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
session_start();
require_once("funkcije.php");
require_once("klase/classBaza.php");
require_once("klase/classLog.php");
$db=new Baza();
if(!$db->connect())
{
    echo "Greška prilikom konekcije na bazu!!!<br>".$db->error();
    exit();
}
if(login() and isset($_GET['odjava']))
{
    Log::upisiLog("logovi/logovanje.txt", "Odjava korisnika '{$_SESSION['ime']}'");
    odjaviKorisnika();
}
if(login())
    prikaziPodatke();
else
{
    ?>
    <div class='podaciPrijava'>
        <input type="text" name='korime' id='korime' placeholder="Unesite korisničko ime"/> 
        <input type="text" name='lozinka' id='lozinka' placeholder="Unesite lozinku"/> 
        <button type='button' id='dugmeZaPrijavu'>Prijavite se</button><br>
        <div id="odgovor"></div>
    </div>
    <?php
}
?>
<hr>
<h1>VISER vakcinacija</h1>
<?php
//if(login() and ($_SESSION['status']=='korisnik' or $_SESSION['status']=='admin')){
$upit="SELECT * FROM vakcine";
$rez=$db->query($upit);
if($db->num_rows($rez)>0)
{
    while($red=$db->fetch_object($rez))
    {
        echo "<div class='vakcine'>";
        echo "<h4>$red->naziv</h4>";
        echo "$red->komentar";
        echo "</div>";
    }
}
else
    echo "Nema ni jedna vakcina u bazi!";
?>
<hr>
<input type="text" name="podaci" id="podaci" placeholder="Unesite Vase podatke:"><br><br>
<select name="vakcine" id="vakcine">
    <option value="0">--Izaberite vakcinu--</option>
    <?php
        $upit="SELECT * FROM vakcine";
        $rez=$db->query($upit);
        while($red=$db->fetch_object($rez))
        {
            echo "<option value='$red->id'>$red->naziv</option>";
        }
    ?>
</select><br><br>
<input type="date" name="datum" id="datum"><br><br>
<textarea name="komentar" id="komentar" cols="30" rows="10" placeholder="Unesite komentar:"></textarea><br><br>
<button type="button" name="dugme1" id="dugme1">Snimi!</button>
<div name="odgovor1" id="odgovor1"></div>
<hr>
<h2>Rezervisani termini:</h2>
<?php
//$upit="SELECT termini.id, termini.ime, vakcine.naziv, termini.datum, termini.komentar, termini.odobren FROM termini INNER JOIN vakcine ON termini.idVakcine=vakcine.id";
$upit="SELECT t.id, t.ime, v.naziv, t.datum, t.komentar, t.odobren FROM termini t, vakcine v WHERE t.idVakcine=v.id";
$rez=$db->query($upit);
while($red=$db->fetch_object($rez))
        {
            if($red->odobren)
            echo "$red->id: $red->ime / $red->datum ($red->naziv) / $red->komentar<br><br>";
        }
?>
</body>
</html>


