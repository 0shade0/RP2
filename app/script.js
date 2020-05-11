var clicker, id;

function SetTop(top) {
    if(clicker) document.getElementById(id).style.top = top;
    else document.getElementById(id).style.top = "";
}

function SetHeight(height) {
    if(clicker) document.getElementById(id).style.height = height;
    else document.getElementById(id).style.height = "";
}

function SetBackgroundColor(color) {
    if(clicker) document.getElementById(id).style.backgroundColor = color;
    else document.getElementById(id).style.backgroundColor = "";
}

function headerWrapper(top, height, color) {
    clicker = clicker_header;
    id = "header_wrapper";
    
    SetTop(top);
    SetHeight(height);
    SetBackgroundColor(color);

    clicker_header = !clicker_header;
}