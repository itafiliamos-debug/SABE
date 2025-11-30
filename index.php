<!DOCTYPE html>
<html lang="en">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>S.A.B.E.</title>
</head>
<body>
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to S.A.B.E.</h1>
            <p class="lead">S.A.B.E. is a web application designed to help you manage your tasks efficiently.</p>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class=""><h1>Lista de estudiantes</h1></div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-12 col-md-offset-1">
                    <table class="table align-middle">
                        <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-success">Ingresar</button>
                        <button type="button" class="btn btn-default float-right">Imprimir</button>
                        <hr>
                    <div class="modal" id="myModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Ingresar nuevo estudiante</h4>
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body" style="color:#FFFFFFF; font-size:80%;">
                                    A continiacion ingrese los datos del nuevo estudiante:
                                    <form>
                                        <div class="row" >
                                            <div class="col">
                                                <label for="tipo" class="mr-sm-2">Tipo:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" style="font-size:90%;" id="tipo" placeholder="Ingrese el tipo" name="tipo">
                                            </div>
                                            <div class="col">
                                                <label for="documento" class="mr-sm-2">Documento:</label>
                                                <input type="number" class="form-control mb-2 mr-sm-2" id="documento" placeholder="Ingrese el documento" name="documento">
                                            </div>
                                            <div class="col">
                                                <label for="nombres" class="mr-sm-2">Nombres:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="nombres" placeholder="Ingrese los nombres" name="nombres">
                                            </div>
                                            <div class="col">
                                                <label for="apellidos" class="mr-sm-2">Apellidos:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="apellidos" placeholder="Ingrese los apellidos" name="apellidos">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label for="fecha_nac" class="mr-sm-2">Fecha de nacimiento:</label>
                                                <input type="date" class="form-control mb-2 mr-sm-2" id="fecha_nac" placeholder="Fecha de nacimiento" name="fecha_nac">
                                            </div>
                                            <div class="col">
                                                <label for="edad" class="mr-sm-2">Edad:</label>
                                                <input type="number" class="form-control mb-2 mr-sm-2" id="edad" placeholder="Ingrese la edad" name="edad">
                                            </div>
                                            <div class="col">
                                                <label for="eps" class="mr-sm-2">EPS:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="eps" placeholder="Ingrese la EPS" name="eps">
                                            </div>
                                            <div class="col">
                                                <label for="rh" class="mr-sm-2">RH:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="rh" placeholder="Ingrese el RH" name="rh">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label for="Telefono1" class="mr-sm-2">Telefono 1:</label>
                                                <input type="number" class="form-control mb-2 mr-sm-2" id="Telefono1" placeholder="Ingrese el telefono 1" name="Telefono1">
                                            </div>
                                            <div class="col">
                                                <label for="Telefono2" class="mr-sm-2">Telefono 2:</label>
                                                <input type="number" class="form-control mb-2 mr-sm-2" id="Telefono2" placeholder="Ingrese el telefono 2" name="Telefono2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label for="almuerzo" class="mr-sm-2">Almuerzo:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="almuerzo" placeholder="Recibe almuerzo" name="almuerzo">
                                            </div>
                                            <div class="col">
                                                <label for="jornada" class="mr-sm-2">Jornada:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="jornada" placeholder="Ingrese la jornada" name="jornada">
                                            </div>
                                            <div class="col">
                                                <label for="grado" class="mr-sm-2">Grado:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="grado" placeholder="Grado" name="grado">
                                            </div>
                                            <div class="col">
                                                <label for="docente" class="mr-sm-2">Docente:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="docente" placeholder="Nombre del docente" name="docente">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <label for="matricula" class="mr-sm-2">Matricula:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="matricula" placeholder="Estado matricula" name="matricula">
                                            </div>
                                            <div class="col">
                                                <label for="est_pension" class="mr-sm-2">Pension:</label>
                                                <input type="text" class="form-control mb-2 mr-sm-2" id="est_pension" placeholder="Estado pension" name="est_pension">
                                            </div>
                                            <div class="col">
                                                <label for="val_pension" class="mr-sm-2">Valor pension:</label>
                                                <input type="number" class="form-control mb-2 mr-sm-2" id="val_pension" placeholder="Ingrese el valor de la pension" name="val_pension">
                                            </div>
                                            <div class="col">
                                                <label for="fecha_reg" class="mr-sm-2">Fecha de registro:</label>
                                                <input type="date" class="form-control mb-2 mr-sm-2" id="fecha_reg" placeholder="Ingrese la fecha de registro" name="fecha_reg">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <button type="submit" class="btn btn-primary mr-sm-2 mb-2">Ingresar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    <table class="table table-bordered" style="color:#456789; font-size:80%; font-style:normal;"> 
                        <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">id</th>
                                <th scope="col">nombres</th>
                                <th scope="col">apellidos</th>
                                <th scope="col">edad</th>
                                <th scope="col">grado</th>
                                <th scope="col">docente</th>
                                <th scope="col">almuerzo</th>
                                <th scope="col">matricula</th>
                                <th scope="col">pension</th>
                                <th scope="col">jornada</th>
                                <th scope="col">fecha_act</th>
                                <th scope="col">Boton1</th>
                                <th scope="col">Boton2</th>
                                </tr>
                            </thead>
                                <tr>
                                <th scope="row">2</th>
                                <td>12345</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                                <td>@mdo</td>
                                <td>@mdo</td>
                                <td>@mdo</td>
                                <td>@mdo</td>
                                <td>@mdo</td>
                                <td>@mdo</td>
                                <td>@mdo</td>
                                <td><a href="" class="btn btn-success" style="font-size:80%">Editar</td>
                                <td><a href="" class="btn btn-danger" style="font-size:80%">Eliminar</td>
                                </tr>
                            </tbody>
                        </table>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>