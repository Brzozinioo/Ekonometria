<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kalkulator Ekonometryczny - Brzoza Krystian</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
      crossorigin="anonymous"
    ></script>
    <script
      type="text/javascript"
      src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"
    ></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap"
      rel="stylesheet"
    />
    <style>
      * {
        font-family: "Open Sans", sans-serif;
      }
    </style>
    <script src="script.js"></script>
  </head>
  <body>
    <div class="containter-fluid ">
      <div class="row justify-content-center align-items-middle mt-5">
        <div class="col-5">
        <form id="fileUploadForm" enctype="multipart/form-data">
          
            <div class="mb-3">
              <label for="formFile" class="form-label"
                ><h3>Zaimportuj plik do obliczeń</h3></label
              >
              <input class="form-control" type="file" id="formFile" accept=".txt" />
            </div>
          
          <div class="col-1 justify-content-around">
            <button class="btn btn-primary" id="oblicz">Oblicz</button>
          </div>
        </form>
      </div>
      </div>
      <div class="row justify-content-around align-items-middle d-none" id="calc">
        <div class="col-4">
          <div class="col-12 text-center">Dane</div>
          <table class="table table-bordered border-primary">
            <thead>
              <tr>
                <th scope="col">Y</th>
                <th scope="col">X1</th>
                <th scope="col">X2</th>
                <th scope="col">X3</th>
                <th scope="col">X4</th>
              </tr>
            </thead>
            <tbody id="zmienne">
            
              
            </tbody>
          </table>
        </div>
        <div class="col-6">
          
            <div class="row justify-content-center text-center">
              <div class="col-6 ">
                <div class="row justify-content-center">
                  <div class="col-12 text-center">R0</div>
                  <div class="col-6">

                  <table class=" table text-center table-bordered border-primary ">
                    <thead>
                      <tr>
                        <th scope="col">Y</th>
                      </tr>
                    </thead>
                    <tbody id='r0'>
                      
                    </tbody>
                  </table>
                </div>
                </div>
              </div>
              <div class="col-6">
                <div class="row justify-content-center">
                  <div class="col-12 text-center">R</div>
                  <div class="col-12">

                <table class="table table-bordered border-primary text-center">
                  <thead>
                    <tr>
                      <th scope="col"> </th>
                      <th scope="col">X1</th>
                      <th scope="col">X2</th>
                      <th scope="col">X3</th>
                      <th scope="col">X4</th>
                    </tr>
                  </thead>
                  <tbody id="r">
                    
                  </tbody>
                </table>
                </div>
                </div>
              </div>
              
              <div class="col-6">
                <div class="row justify-content-center">
                  <div class="col-12 ">Tablica Grafów</div>
                  <div class="col-9">
                <table class="table table-bordered border-primary text-center">
                  <thead>
                    <tr>
                      <th scope="col"> </th>
                      <th scope="col">X1</th>
                      <th scope="col">X2</th>
                      <th scope="col">X3</th>
                      <th scope="col">X4</th>
                    </tr>
                  </thead>
                  <tbody id="tabGraf">
                    
                  </tbody>
                </table>
                </div>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex" style="height: 300px" id="graph"></div>
              </div>
              <div class="row text-bold">

              <div class="col-12" id="rownanie"></div>
              <div class="col-12" id="bledy"></div>
              <div class="col-12" id="wariancja"></div>
              <div class="col-12" id="odchylenie">
                
              </div>
              
              <div class="col-12" id="wspolcznynnik_zm"></div>
              <div class="col-12" id="det"></div>
            </div>
              
            </div>
          
        </div>
      </div>
    </div>
  </body>
</html>
