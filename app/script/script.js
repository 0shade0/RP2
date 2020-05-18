
$( document ).ready(function() {

// izaberi profilnu sliku / prikaži slike
    var count2 = 0;
    $("button.pick_Image").click(function() {
        count2++;
        count2 % 2 ? $firstFunction() : $secondFunction();

    function $firstFunction() {
        $("button.pick_Image").css('background-color', 'var(--orange)');
        $("button.pick_Image").css('border-width', '10');

        $("table.pick_Image").css('width','560px');
        $("table.pick_Image").css('border','2px solid var(--blue)');
        $(".pick_Image input").css('display','');
    }

    function $secondFunction() {
        $("button.pick_Image").css('background-color', '');
        $("button.pick_Image").css('border-width', '');

        $("table.pick_Image").css('width','0px');
        $("table.pick_Image").css('border','none');
        $(".pick_Image input").css('display','none');
    }
})

// preview slika na veliku
    var trenutna = $(".user_Image").attr("src")

    $(".pick_Image input").mouseenter(function() {
        var slika = $(this).css('background-image');
        slika = slika.replace('url(','').replace(')','').replace(/\"/gi, "");
        $(".user_Image").attr("src", slika)
    })

    $(".pick_Image input").mouseleave(function() {
        $(".user_Image").attr("src", trenutna)
    })

});
