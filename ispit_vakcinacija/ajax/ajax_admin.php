<?php
session_start();
require_once("../klase/classBaza.php");
require_once("../klase/classLog.php");
$db=new Baza();
if(!$db->connect())
{
    echo "Baza trenutno nije dostupna!";
    exit();
}
$funkcija=$_GET['funkcija'];
$kwak=0;

if($funkcija=="odobri")
{
    if(isset($_POST['nekiId'])){
        $id=$_POST['nekiId'];
        $upit="UPDATE termini SET odobren=1 WHERE id=$id";
        $rez=$db->query($upit);
        $upit1="SELECT * FROM termini WHERE id=$id";
        $rez1=$db->query($upit1);
        $red=$db->fetch_object($rez1);
        $kwak=1;
    }
    if($kwak==1){
        Log::upisiLog("../logovi/odobreni.txt", "Odobren termin za korisnika: {$red->ime}, datuma: {$red->datum}, vakcina: {$red->idVakcine}.");
        echo "Uspešno odobren termin.";
        $kwak=0;
    }
    else
        echo "Greška prilikom dodavanja termina:<br>".$db->error();
}

if($funkcija=="obrisi")
{
    $id=$_POST['nekiId'];
    $upit="DELETE FROM termini WHERE id=$id";
    $db->query($upit);
    if($db->affected_rows()==1)
        echo "Uspešno izbrisan termin.";  
    else
        echo "Greška prilikom brisanja termina:<br>".$db->error();
}

if($funkcija=="log")
{
    $fajl=$_POST['fajl'];
    if(file_exists("../logovi/".$fajl))
    {
        $odgovor=file_get_contents("../logovi/".$fajl);
        $odgovor=str_replace("\r\n", "<br>", $odgovor);
        echo $odgovor;
    }
    else
        echo "Fajl ne postoji!!!!";
}
?>