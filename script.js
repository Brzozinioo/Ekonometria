$(document).ready(function() { 

  $("form#fileUploadForm").submit(function(e) {
    e.preventDefault();    
    var tabZmienne = document.getElementById("zmienne");
    var tabZmienneR0 = document.getElementById("r0");
    var tabZmienneR = document.getElementById("r");
    var tabGraf = document.getElementById("tabGraf");
    var rownanie = document.getElementById("rownanie");
    var wariancja = document.getElementById('wariancja');
    var odchylenie = document.getElementById('odchylenie');
    var bledy = document.getElementById('bledy');
    var wspolcznynnik_zm = document.getElementById('wspolcznynnik_zm');
    var det = document.getElementById('det');
    tabZmienne.innerHTML='';
    tabZmienneR0.innerHTML='';
    tabZmienneR.innerHTML='';
    tabGraf.innerHTML='';
    var file_data = $('#formFile').prop('files')[0];
    var formData = new FormData(this);
    formData.append('file',file_data);

    $.ajax({
        url: "oblicz.php",
        type: 'POST',
        data: formData,
        success: function (data) {
           var response = JSON.parse((data));  
            for (var i = 0; i<10;i++){
            tabZmienne.innerHTML +=('<tr><td>'+response.zmienne[0][i]+'</td><td>'+response.zmienne[1][i]+'</td><td>'+response.zmienne[2][i]+'</td><td>'+response.zmienne[3][i]+'</td><td>'+response.zmienne[4][i]+'</td></tr>');
           }          
           console.log(response);
           var tab = [];
           for(var i=0;i<4;i++){
            tabGraf.innerHTML +=('<tr><td><b>X'+(i+1)+'</b></td><td>'+response.tablica_grafu[0][i]+'</td><td>'+response.tablica_grafu[1][i]+'</td><td>'+response.tablica_grafu[2][i]+'</td><td>'+response.tablica_grafu[3][i]+'</td></tr>');
            tabZmienneR0.innerHTML +=('<tr><td>'+response.r0[i].toFixed(4)+'</td></tr>');
            tabZmienneR.innerHTML +=('<tr><td><b>X'+(i+1)+'</b></td><td>'+response.r[0][i].toFixed(4)+'</td><td>'+response.r[1][i].toFixed(4)+'</td><td>'+response.r[2][i].toFixed(4)+'</td><td>'+response.r[3][i].toFixed(4)+'</td></tr>');
             for(var j=i;j<4;j++){
               if(j>i)
              if(response.tablica_grafu[i][j] == 1){
                tab.push({from: i+1, to: j+1 });
              }
             }
           }

           rownanie.innerHTML= 'Rownanie modelu: <b>'+response.rownanie+'</b>';
           bledy.innerHTML = 'Błędy średnie szacunku parametrów: <b>';
           var x = response.sr_blad.length;
           for(var i=0; i<response.sr_blad.length;i++){
             bledy.innerHTML+= '<b>(a'+(x-1)+': '+response.sr_blad[i].toFixed(4) +') </b>';
             x--;
           }
           wariancja.innerHTML = 'Wariancja składnika rzesztowego: <b>'+response.su2.toFixed(2)+'</b>';
           odchylenie.innerHTML = 'Odchylenie standardowe składnika resztowego: <b>'+ response.su.toFixed(4)+'</b>';

           wspolcznynnik_zm.innerHTML = 'Współcznynnik zmienności losowej: <b>'+response.wsp_zm_los.toFixed(2)+'%</b>';
           det.innerHTML= 'Współczynnik determinacji: <b>'+response.wsp_det+'</b>';

           

            var nodes = new vis.DataSet([
              { id: 1, label: "X1" },
              { id: 2, label: "X2" },
              { id: 3, label: "X3" },
              { id: 4, label: "X4" }
            ]);
          
            // create an array with edges
            var edges = new vis.DataSet(tab);
            console.log(tab);
          
            // create a network
            var container = document.getElementById("graph");
            var data = {
              nodes: nodes,
              edges: edges
            };
            var options = {};
            var network = new vis.Network(container, data, options);
            var display = document.getElementById("calc");
            display.classList.remove("d-none");
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

    
    

});