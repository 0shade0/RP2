
$( document ).ready(function() {

// izaberi profilnu sliku / prikaži slike
    var count = 0;
    $("button.pick_Image").click(function() {
        count++;
        count % 2 ? $firstFunction() : $secondFunction();

    function $firstFunction() {
        $("button.pick_Image").css('background-color', 'var(--orange)');
        $("button.pick_Image").css('border-width', '10');

        $("table.pick_Image").css('width','560px');
        $("table.pick_Image").css('height','180px');
        $("table.pick_Image").css('bottom','20px');
        $("table.pick_Image").css('left','400px');
    }

    function $secondFunction() {
        $("button.pick_Image").css('background-color', '');
        $("button.pick_Image").css('border-width', '');

        $("table.pick_Image").css('width','0px');
        $("table.pick_Image").css('height','0px');
        $("table.pick_Image").css('bottom','70px');
        $("table.pick_Image").css('left','380px');
    }
})

// preview malih slika na veliku
    var trenutna = $(".user_Image").attr("src")

    $(".pick_Image input").mouseenter(function() {
        var slika = $(this).css('background-image');
        slika = slika.replace('url(','').replace(')','').replace(/\"/gi, "");
        $(".user_Image").attr("src", slika)
    })

    $(".pick_Image input").mouseleave(function() {
        $(".user_Image").attr("src", trenutna)
        $(this).css('filter','')
    })

// bojanje kategorija
    var stringToColour = function(str) {
        var hash = 0;
        for (var i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 4) - hash);
        }
        var colour = '#';
        for (var i = 0; i < 3; i++) {
        var value = (hash >> (i * 8)) & 0xFF;
        colour += ('00' + value.toString(16)).substr(-2);
        }
        return colour;
    }

    $("table.chores td.chore_category").each( function() {
        $(this).css('background-color', `${stringToColour($(this).text())}B3`)
        $(this).siblings().css('border-top', `2px solid ${stringToColour($(this).text())}`)
    })

// ako je zadatak označen onda se nešto dogodi
    $('.chores td.chore_confirm input[type="checkbox"]').click(function(){
        if($(this).prop("checked") == true){
            $(this).parent().parent().css('filter','brightness(75%)')
        }
        else if($(this).prop("checked") == false){
            $(this).parent().parent().css('filter','brightness(100%)')
        }
    });
    

});
