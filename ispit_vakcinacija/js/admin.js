$(document).ready(function(){

    $("[id*='odobri']").click(function(){
        let buttonId = $(this).attr('id');
        let id = buttonId.replace('odobri', '');
        
        if(!confirm("Da li ste sigurni da želite da odobrite termin?")) return false;
        
        $.post("ajax/ajax_admin.php?funkcija=odobri", {nekiId: id}, function(response){
            if(response=="Uspešno odobren termin.")
            {
                document.location.assign("admin.php");
                alert(response);
            }
            else $("#odgovor1").html("Sta se desava?"); 
        })
    });

    $("[id*='obrisi']").click(function(){
        let buttonId = $(this).attr('id');
        let id = buttonId.replace('obrisi', '');
        
        if(!confirm("Da li ste sigurni da želite da izbrišete termin?")) return false;
        
        $.post("ajax/ajax_admin.php?funkcija=obrisi", {nekiId: id}, function(response){
            if(response=="Uspešno izbrisan termin.")
            {
                document.location.assign("admin.php");
                alert(response);
                ocistiTermin();
            }
            else $("#odgovor1").html(response); 
        })
    });

    $("#log").change(function(){
        let fajl=$(this).val();
        if(fajl=="0")
        {
            $("#divlogovi").html("");
            return false;
        }
        $.post("ajax/ajax_admin.php?funkcija=log",{fajl:fajl}, function(response){
            $("#divlogovi").html(response);
        })
    })
})

function ocistiTermin(){
    $("#termin").val("");
}