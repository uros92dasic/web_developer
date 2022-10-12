var start = new Date().getTime();

    function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');

        var color = '#';

        for (var i = 0; i < 6; i++ ){
            color += letters[Math.floor(Math.random()*16)];
        }

        return color;
    }

    function makeShapeApear() {
        var top = Math.random()*400;
        var left = Math.random()*400;

        var width = (Math.random()*200)+50;
        var height = (Math.random()*200)+50;

        if(Math.random()>0.5) {
            document.getElementById("shape").style.borderRadius = "50%";
        }
        else{
            
            document.getElementById("shape").style.borderRadius = "0%";
        }

        document.getElementById("shape").style.backgroundColor = getRandomColor();

        document.getElementById("shape").style.top = top + "px";
        document.getElementById("shape").style.left = left + "px";

        document.getElementById("shape").style.width = width + "px";
        document.getElementById("shape").style.height = height + "px";

        document.getElementById("shape").style.display = "block";
    
        start = new Date().getTime();
    }

    function appearAfterDelay(){
        setTimeout(makeShapeApear, Math.random()*1000);
    }

    appearAfterDelay();

    document.getElementById("shape").onclick = function() {

        document.getElementById("shape").style.display = "none";

        var end = new Date().getTime();

        var timeTaken = (end - start)/1000;

        document.getElementById("timeTaken").innerHTML = timeTaken;

        appearAfterDelay();
    }