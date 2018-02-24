 <!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <script type="text/javascript" src="jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="bootstrap.min.js"></script>
        <link rel="stylesheet" href="bootstrap.css"/>
        <title></title>
    </head>
    <body>                                                     
        <div class="container-fluid">
            <div class="row">
                <h2>Generador de modelos </h2>
                <label for="txtClassName">Nombre de la tabla: </label><input id="txtClassName" type="text" class="form-control"/>
                <label for="txtAttr">Atributos buscados(separados por coma): </label><textarea id="txtAttr" class="form-control"></textarea>
                <label for="sqlAction">Acción: </label><select id="sqlAction" class="form-control"><option value="1">SELECT</option><option value="2">INSERT</option><option value="3">UPDATE</option><option value="4">DELETE</option></select>
                <input type="button" id="btnGenerate" value="Generar" class="btn btn-success btn-lg col-xs-offset-3 col-xs-6" style="margin-top: 1em"/>
            </div>
            <div class="row center-block" >
                <div class="alert alert-success" id="ale" style="display: none;">
                    <strong>Exito!</strong> La query ha sido copiada.
                </div>
                <h2>Resultado</h2>
                <textarea id="txtResult"  class="form-control"></textarea>
            </div>

        </div>
        <script type="text/javascript">
            function select(a) {
                var sql = "";
                sql += "SELECT ";
                a.forEach(function (e) {
                    sql += e + ",";
                });
                return sql;
            }



            $(document).on("click", "#btnGenerate", function () {
                var str = "";
                var txtClassName = $("#txtClassName").val();
                var attr = $("#txtAttr").val().trim().split(",");
                switch ($("#sqlAction").val()) {
                    case '1' :
                        str += select(trimearArray(attr));
                        break;
                    case '2' :
                        break;
                    case '3' :
                        break;

                }

/*
                var className = firtsToUpper(txtClassName);
                str += "class " + className + "{\n";
                attr.forEach(function (e) {
                    str += "private $" + firstToLower(e.trim()) + ";\n";
                });
                str += createConstruct(trimearArray(attr));
                str += createGetters(trimearArray(attr));
                str += createSetters(trimearArray(attr));
                str += "\n}\n?>";
                */
                $("#txtResult").val(str);
                $("#txtResult").focus();
                $("#txtResult").select();
                var copied = document.execCommand("copy");
                if (copied) {
                    $("#ale").show();
                }
                $("#txtResult").height($(document).height() - 120);
                $("#txtResult").width($(document).width() - 60);

                $('html, body').animate({
                    scrollTop: $("#ale").offset().top
                }, 1000);

            });



            function firstToLower(string) {
                return  string[0].toLowerCase() + string.substring(1, string.length);
            }
            function firtsToUpper(string) {
                return  string[0].toUpperCase() + string.substring(1, string.length);
            }

            function createConstruct(attr) {
                var str = "";
                str += "function __construct($array){\n";

                attr.forEach(function (e) {
                    str += "if(isset($array['" + firstToLower(e) + "'])){\n$this->" + firstToLower(e) + " = $array['" + firstToLower(e) + "'];\n}\n";
                });

                str += "\n}";
                return str;
            }

            function createGetters(attr) {
                var getters = "";
                attr.forEach(function (e) {
                    getters += "function get" + firtsToUpper(e) + "(){\n return $this->" + firstToLower(e) + ";\n }\n ";
                });
                return getters;
            }

            function createSetters(attr) {
                var setters = "";
                attr.forEach(function (e) {
                    setters += "function set" + firtsToUpper(e) + "(){\n return $this->" + firstToLower(e) + ";\n }\n ";
                });
                return setters;
            }

            function trimearArray(a) {
                var n = new Array;
                a.forEach(function (e) {
                    n.push(e.trim());
                });
                return n;
            }


        </script>
    </body>
</html>
