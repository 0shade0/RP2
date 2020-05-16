
$( document ).ready(function() {

    var count = 0;
    $(".header_wrapper").click(function() {
    count++;
    count % 2 ? $firstFunction() : $secondFunction();

    function $firstFunction() {
        $('#header_wrapper').css({
            'top':'0',
            'height':'225px',
            'background-color':'#4651e9'
        })
    }

    function $secondFunction() {
        $('#header_wrapper').css({
            'top':'-210px',
            'height':'270px',
            'background-color':'#60e8ef'
        })
    }

    });


});
