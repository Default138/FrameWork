<?php
if(isset($_GET["erro"])){
    switch ($_GET["erro"]){
        case 0:
            echo "<div class='erro'>Apenas arquivos SQL são permitidos.</div>";
            break;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>Framework MVC</title>
    <link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
<h1> Framework MVC</h1> <h2>Converta seu arquivo SQL em um sistema MVC completo</h2>
<form action="LeitorSQL.php" method="post" enctype="multipart/form-data">
    <label for="arquivo">Envie seu arquivo SQL</label>
    <input type="file" name="arquivo" required><br>
    <button type="submit">Enviar</button>

</form>
</body>
</html>