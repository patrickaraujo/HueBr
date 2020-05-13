function myFunction(i) {
    var campo;
    if(i == 1)
        campo = "senha";
    if(i == 2)
        campo = "cPsw";
    var x = document.getElementById(campo);
    if (x.type === "password")
        x.type = "text";
    else
        x.type = "password";
}