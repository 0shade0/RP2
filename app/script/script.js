
$( document ).ready(function() {
// pulldown header
    var count1 = 0;
    $(".user_footer").click(function() {
    count1++;
    count1 % 2 ? $firstFunction() : $secondFunction();

    function $firstFunction() {
        $('#user_footer').css({
            'height':'200%',
            'background-color':'#4651e9',
            'cursor':'default'
        })
    }

    function $secondFunction() {
        $('#user_footer').css({
            'height':'270px',
            'background-color':'',
            'cursor':'pointer'
        })
    }

    });

// izaberi profilnu sliku / prikaži slike
    var count2 = 0;
    $("table.pick_Image").hide();
    $("button.pick_Image").click(function() {
        count2++;
        count2 % 2 ? $firstFunction() : $secondFunction();

    function $firstFunction() {
        $("button.pick_Image").css('background-color', 'var(--orange)');
        $("button.pick_Image").css('border-width', '10');
        $("table.pick_Image").show();
        $("table.pick_Image").width(600);
    }

    function $secondFunction() {
        $("button.pick_Image").css('background-color', '');
        $("button.pick_Image").css('border-width', '');
        $("table.pick_Image").fadeOut(500);
        $("table.pick_Image").width(0);
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
