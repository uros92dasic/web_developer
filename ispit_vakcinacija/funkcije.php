<?php
function login(){
    if(isset($_SESSION['id']) AND isset($_SESSION['ime']) AND isset($_SESSION['email']) AND isset($_SESSION['status']) )
        return true;
    else
        return false;
}
function odjaviKorisnika(){
    session_unset();
    session_destroy();
}

function prikaziPodatke(){
    echo "<div>";
    echo "Prijavljeni ste kao <b>{$_SESSION['ime']}</b> (<b>{$_SESSION['email']}</b>)<br>";
    echo "<a href='index.php'>Početna</a> | ";
    if($_SESSION['status']=='admin') echo "<a href='admin.php'>Stranica administracije</a> | ";
    echo "<a href='index.php?odjava'>Odjava</a>";
    echo "</div>";
}

function uploadSlike(){
    $folder="uploads/";
    if(isset($_POST['imeSlike']))$ime=$_POST['imeSlike'];
    $imeDat=$folder.$ime."_"/*.date("ymd_His", time())."_"*/.$_FILES['dat']['name'];
    if($_FILES['dat']['size']<1000000)
    {
        $tmpNiz=explode(".", $_FILES['dat']['name']);
        if($tmpNiz[count($tmpNiz)-1]=="jpg" or $tmpNiz[count($tmpNiz)-1]=="jpeg" or $tmpNiz[count($tmpNiz)-1]=="png")
        {
            if(!move_uploaded_file($_FILES['dat']["tmp_name"], $imeDat))
            {
                echo "Neuspelo prebacivanje datoteke na server!";
            }
            else
            {
                echo "<br><b>Uspesno ste prebacili datoteku na server!</b>";
            }
        }
        else
        {
            echo "<br>Pogresan tip datoteke.";
        }
    }
    else{
        echo "<br>Datoteka je prevelika!";
    }
}
?>