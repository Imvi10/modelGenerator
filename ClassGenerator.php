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
                <label for="txtClassName">Nombre de la clase: </label><input id="txtClassName" type="text" class="form-control"/>
                <label for="txtAttr">Atributos (separados por coma): </label><textarea id="txtAttr" class="form-control"></textarea>
                <!--<label for ="slcConstructor">Constructor : </label><select id="slcConstructor" class="form-control"><option value="1">Si</option><option value="2">No</option></select>
                <label for="slcGetters">Getters: </label><select id="slcGetters" class="form-control"><option value="1">Si</option><option value="2">No</option></select>
                <label for="slcSetters">Getters: </label><select id="slcSetters" class="form-control"><option value="1">Si</option><option value="2">No</option></select>
                --> <input type="button" id="btnGenerate" value="Generar" class="btn btn-success btn-lg col-xs-offset-3 col-xs-6" style="margin-top: 1em"/>
            </div>
            <div class="row center-block" >
                <div class="alert alert-success" id="ale" style="display: none;">
                    <strong>Exito!</strong> La clase ha sido copiada.
                </div>
                <h2>Resultado</h2>
                <textarea id="txtResult"  class="form-control"></textarea>
            </div>

        </div>
        <script type="text/javascript">
            $(document).on("click", "#btnGenerate", function () {
                var str = "";
                str += "<?" + "php\n";
                var txtClassName = $("#txtClassName").val();
                var attr = $("#txtAttr").val().trim().split(",");

                var className = firtsToUpper(txtClassName);
                str += "class " + className + "{\n";
                attr.forEach(function (e) {
                    str += "private $" + firstToLower(e.trim()) + ";\n";
                });
                str += createConstruct(trimearArray(attr));
                str += createGetters(trimearArray(attr));
                str += createSetters(trimearArray(attr));
                str += createCRUD();
                str += "\n}\n?>";
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
                    setters += "function set" + firtsToUpper(e) + "(){\n $this->" + firstToLower(e) + ";\n }\n ";
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

            function getInsertFields(a) {
                var n = "";
                var m = "VALUES (";
                a.forEach(function (e, i) {
                    if (i <= a.length - 2) {
                        n += a[i] + ",";
                        m += " ? , ";
                    } else {
                        n += a[i] + ")";
                        m += " ? ); ";
                    }
                });
                return n + m;
            }

            function getSelectAll(a) {
                var s = "";
                a.forEach(function (e, i) {
                    if (i <= a.length - 2) {
                        s += a[i] + ", ";
                    } else {
                        s += a[i];
                    }
                });
                return  s;
            }

            function getUpdateAll(a) {
                var u = "";
                a.forEach(function (e, i) {
                    if (i <= a.length - 2) {
                        u += a[i] + " = ? ,";
                    } else {
                        u += a[i] + " = ?  WHERE " + a[0] + " = ?";
                    }
                });
                return u;
            }

            function createCRUD() {
                var crud = "";
                crud += c();
                crud += r();
                crud += rgb();
                crud += u();
                crud += d();
                return crud;
            }

            function getArrayReplaceQuestion(a) {
                var q = "";
                a.forEach(function (e, i) {
                    if (i <= a.length - 2) {
                        q += " ':" + a[i] + "' => $this->get" + firtsToUpper(a[i]) + "() , ";
                    } else {
                        q += " ':" + a[i] + "' => $this->get" + firtsToUpper(a[i    ]) + "() ";
                    }
                });
                return q;
            }


            function c() {
                var txtClassName = $("#txtClassName").val();
                var attr = $("#txtAttr").val().trim().split(",");
                var className = firtsToUpper(txtClassName);
                var query = "function add(){ \n  ";
                query += "try{ \n  Connection::getInstance()->beginTransaction(); \n $sql = 'INSERT INTO " + className + "(";
                query += getInsertFields(attr) + "';";
                query += "\n $statement = Connection::getInstance()->prepare($sql);";
                query += "\n $statement->execute(array(" + getArrayReplaceQuestion(attr) + "));";
                query += "\n Connection::getInstance()->commit();";
                query += " \n return TRUE;";
                query += " \n }catch(Exception $e){ \n $e->getMessage(); \n   Connection::getInstance()->rollback();   \n return FALSE; \n} \n }";
                return query;
            }

            function r() {
                var txtClassName = $("#txtClassName").val();
                var attr = $("#txtAttr").val().trim().split(",");
                var className = firtsToUpper(txtClassName);
                var query = "\n function getAll(){ ";
                query += "\n $sql = 'SELECT ";
                query += getSelectAll(attr);
                query += " FROM " + className + "';";
                query += "\n $statement = Connection::getInstance()->prepare($sql);  ";
                query += "\n $statement->execute();";
                query += "\n return $statement->fetchAll();";
                query += "\n } \n";
                return query;
            }

            function rgb() {
                var txtClassName = $("#txtClassName").val();
                var attr = $("#txtAttr").val().trim().split(",");
                var className = firtsToUpper(txtClassName);
                var query = "\n function get(){ ";
                query += "\n $sql = 'SELECT ";
                query += getSelectAll(attr);
                query += " FROM " + className + " WHERE " + attr[0] + " = $this->get" + firtsToUpper(attr[0]) + "()';";
                query += "\n $statement = Connection::getInstance()->prepare($sql);";
                query += "\n $statement->execute();";
                query += "\n return $statement->fetchAll();";
                query += "\n } \n";
                return query;
            }


            function u() {
                var txtClassName = $("#txtClassName").val();
                var attr = $("#txtAttr").val().trim().split(",");
                var className = firtsToUpper(txtClassName);
                var query = "function update(){ \n";
                query += "try{ \n  Connection::getInstance()->beginTransaction(); \n $sql = 'UPDATE " + className + " SET ";
                query += getUpdateAll(attr) + "';";
                query += "\n $statement = Connection::getInstance()->prepare($sql); ";
                query += "\n $statement->execute(array(" + getArrayReplaceQuestion(attr) + "));";
                query += "\n Connection::getInstance()->commit(); \n ";
                query += " return TRUE;";
                query += " \n }catch(Exception $e){ \n $e->getMessage(); \n  Connection::getInstance()->rollback();  \n return FALSE; \n} \n } \n";
                return query;
            }

            function d() {
                var txtClassName = $("#txtClassName").val();
                var attr = $("#txtAttr").val().trim().split(",");
                var className = firtsToUpper(txtClassName);
                var query = "function delete(){ \n ";
                query += "try{ \n  Connection::getInstance()->beginTransaction();";
                query += " \n $sql = 'UPDATE " + className + " SET  " + attr[0] + " = 0 where " + attr[0] + " = :" + attr[0] + " '; ";
                query += "\n $statement = Connection::getInstance()->prepare($sql); ";
                query += "\n $statement->bindParam(:" + attr[0] + " , " + attr[0] + " )";
                query += "\n $statement->execute();";
                query += "\n Connection::getInstance()->commit(); \n ";
                query += " return TRUE;";
                query += " \n }catch(Exception $e){ \n $e->getMessage(); \n  Connection::getInstance()->rollback();  \n return FALSE; \n} \n }";
                return query;
            }

        </script>
    </body>
</html>
