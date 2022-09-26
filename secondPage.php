<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вот мы залогинились</title>

    <link rel="stylesheet" href="files/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="files/jquery-3.6.1.js"></script>
</head>

<body>
    <h1>
        <?php
        session_start();

        $name = $_SESSION["user"]["name"];
        echo "Hello " . $name;

        ?>
    </h1>

    <input class="btn btn-primary my-2" type="button" onclick="logOutFunc()" value="Выйти">
</body>
<script>
    function logOutFunc() {
        console.log("ВЫходим не стесняемся")
        $.ajax({
            url: 'php/logout.php',
            method: 'post',
            success() {
                window.location.href = "index.html";
            }
        });
    }
</script>

</html>