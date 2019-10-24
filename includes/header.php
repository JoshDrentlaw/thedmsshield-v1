<?php
    require_once __DIR__.'/../vendor/autoload.php';

    function pretty($thing) {
        $dump = var_dump($thing);
        echo "<pre>{$dump}</pre>";
    }

    $uri = $_SERVER['REQUEST_URI'];
    $path = explode('/', $uri);
    $count = count($path) - 2;
    $end = $path[$count];
    $name = implode(' ', explode('-', $end));
    $title = ucwords($name);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <?php
        if (file_exists('./index.css')) {
            echo '<link rel="stylesheet" type="text/css" href="./index.css">';
        }

        function console_log( $data ){
            echo '<script>';
            echo 'console.log('. json_encode( $data ) .')';
            echo '</script>';
        }
    ?>
</head>
<body>