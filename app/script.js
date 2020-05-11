var clicker_header, id;

function SetTop(tFirst, tSecond, clicker, id) {
    if(clicker) document.getElementById(id).style.top = tFirst;
    else document.getElementById(id).style.top = tSecond;
}

function SetHeight(hFirst, hSecond, clicker) {
    if(clicker) document.getElementById(id).style.height = hFirst;
    else document.getElementById(id).style.height = hSecond;
}

function headerWrapper(hFirst, hSecond, tFirst, tSecond, id) {
    SetTop(tFirst, tSecond, clicker_header, id);
    SetHeight(hFirst, hSecond, clicker_header, id);

    clicker_header = !clicker_header;
}



// Brisanje podataka is formova nakon što se stranica učita
$(document).ready(function(){
    $("form").submit(function(){
      event.preventDefault();
    });
  });