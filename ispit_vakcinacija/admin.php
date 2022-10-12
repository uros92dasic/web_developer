<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Početna</title>
    <script src='js/jquery-3.6.0.js'></script>
    <script src='js/admin.js'></script>
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
if(login() and $_SESSION['status']=='admin')
    prikaziPodatke();
else
{
    echo "Morate biti prijavljeni kao admin da biste videli ovu stranicu<br><a href='index.php'>Prijavite se</a>";
    exit();
}
?>
<h1>Odobravanje/Brisanje termina</h1>
<?php
$upit="SELECT termini.id, termini.ime, vakcine.naziv, termini.datum, termini.komentar, termini.odobren FROM termini INNER JOIN vakcine ON termini.idVakcine=vakcine.id";
$rez=$db->query($upit);
if($db->num_rows($rez)>0)
{
    while($red=$db->fetch_object($rez))
    {
        
        if(!$red->odobren){
            echo "<div class='termini' name='termin' id='termin'>";
            echo "<input type='hidden' name='idTermina' id='idTermina' value=$red->id>";
            echo "Korisnik: $red->ime<br>";
            echo "Vakcina: $red->naziv<br>";
            echo "Datum: $red->datum<br>";
            echo "Dodatne informacije: $red->komentar<br>";
            echo "<button type='button' name='odobri' id='odobri".$red->id."'>Odobri</button> | <button type='button' name='obrisi' id='obrisi".$red->id."'>Obrisi</button>";
            echo "</div>";
        }
    }
}
else
    echo "Nema ni jedna vakcina u bazi!";
?>
<div id="odgovor1"></div>
<hr>
<h2>Logovi</h2>
<select name="log" id="log">
    <option value="0">--Izaberite Log--</option>
    <option value="logovanje.txt">Logovanja</option>
    <option value="odobreni.txt">Odobrenja</option>
    <option value="termini.txt">Zakazivanja</option>
</select>
<div name="divlogovi" id="divlogovi"></div>

<hr>
<form action="admin.php" method="post" enctype="multipart/form-data"> 
    <input type="text" name="imeSlike" id="imeSlike" placeholder="Unesite ime"> <br><br>
    <input type="file" name="dat" id="dat"> <br><br>
        
    <button name="dugmeSlika">Snimi podatke</button>
</form>
<?php
if(isset($_POST['dugmeSlika']))
{
    uploadSlike();
}
else{
    echo "<br>Mozete uploadovati sliku!";
}
?>
<hr>
    <iframe src="index.php" frameboarder="0"></iframe>
<hr>
    <video width="320" height="240" controls>
        <source src="video.mp4" type="video/mp4">
        Molimo Vas da unapredite Vas pretrazivac.
    </video>
<hr>
    <audio controls>
        <source src="audio.mp3" type="audio/mpeg">
        Molimo Vas da unapredite Vas pretrazivac.
    </audio>
</body>
</html>


