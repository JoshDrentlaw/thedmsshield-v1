<?php

use function PHPSTORM_META\type;

include '../header.php';
    
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'thedmsshield';

    // Set DSN
    $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;

    // Create PDO instance
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    # PDO QUERY
    $stmt = $pdo->query('SELECT * FROM posts');

    // Named Params
    $sql = 'SELECT * FROM thedmsshield.raimica_markers';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $markers = $stmt->fetchAll();

    console_log($markers);
?>

<main class="container">
    <section class="row align-items-end">
        <h1 class="mx-4">The Raimica Region</h1>
        <label class="font-weight-bold">Show markers: </label>
        <label class="switch ml-2">
            <input type="checkbox" id="toggle-markers" checked>
            <span class="slider"></span>
        </label>
        <label class="ml-4 font-weight-bold">Add marker: </label>
        <label class="switch ml-2">
            <input type="checkbox" id="toggle-markers">
            <span class="slider"></span>
        </label>
    </section>
    <section id="map-container">
        <?php
            foreach ($markers as $marker) {
                echo '
                    <button
                        id="marker' . $marker->id . '"
                        class="marker btn show-marker"
                        data-index="' . $marker->id . '"
                        data-container="body"
                        data-toggle="popover"
                        data-placement="top"
                        data-content="' . $marker->note_body . '"
                        style="top: ' . $marker->x . 'px; left: ' . $marker->y . 'px;"
                    ></button>
                ';
            }
        ?>
        <img id="raimica-map" class="d-block" src="https://res.cloudinary.com/josh-drentlaw-web-development/image/upload/v1570429648/thedmsshield.com/Rimica_Region.jpg" />
    </section>
</main>

<?php include '../footer.php'; ?>