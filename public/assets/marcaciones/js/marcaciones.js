/**
 * Created by Ernesto on 9/04/15.
 */
$(document).ready(function() {
    $('#sel2').change(function(){
        Rellenar_meses();}
    )

    if($('td.alert-warning')){
        var p = document.createElement("p");
        var t=document.createTextNode("Ausencia");
        p.appendChild(t);
        $('td.alert-warning').appendChild(p);
    }
    function Rellenar_meses(){
        var meses=new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        var a=document.getElementById("meses").value.split("/");
        var encontro=0;

        for(var i=0; i< a.length && encontro==0;i++){
            var a1=a[i].split("-");

            if(a1[0]==document.getElementById("sel2").value){
                encontro=1;

                var Select = document.createElement("select");



                var nodo=document.getElementById("sel1");
                var padre=nodo.parentNode;
                Select.id='sel1';
                Select.name='_mes';
                Select.className=document.getElementById("sel1").className;
                var Noption = "";
                var Ntexto="";
                padre.removeChild(nodo);
                padre.appendChild(Select);

                for(var i=a1[1]-1; i< a1[2];i++){
                    Ntexto=document.createTextNode(meses[i]);
                    Noption=document.createElement('option');
                    Noption.appendChild(Ntexto);
                    Noption.value = i+1;
                    if(i+1<10)
                    {
                        Noption.value = '0'.concat(i+1);
                    }
                    document.getElementById("sel1").appendChild(Noption);

                }


            }
        }
    }
});