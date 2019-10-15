<?php

include '/header.php';
    
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'thedmsshield';

    // Set DSN
    $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;

    // Create PDO instance
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    // Named Params
    $sql = 'SELECT * FROM thedmsshield.raimica_markers';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $markers = $stmt->fetchAll();

    if (isset($_POST['title'])) {
        console_log($_POST);
        $sql = 'UPDATE raimica_markers SET note_title = :note_title, note_body = :note_body WHERE id = :index';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'index' => $_POST['index'],
            'note_title' => $_POST['title'],
            'note_body' => $_POST['note_body']
        ]);
        print('updated');
    }
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
                        data-title="' . $marker->note_title . '"
                        data-content="
                            <p class=\'note-body\'>' . $marker->note_body . '</p>
                            <button class=\'edit d-block ml-auto btn btn-primary btn-sm\'>Edit</button>
                            <button class=\'save d-block ml-auto btn btn-primary btn-sm invisible\'>Save</button>
                        "
                        style="top: ' . $marker->top . '%; left: ' . $marker->left . '%;"
                    ></button>
                ';
            }
        ?>
        <img id="raimica-map" src="https://res.cloudinary.com/josh-drentlaw-web-development/image/upload/v1570429648/thedmsshield.com/Rimica_Region.jpg" />
    </section>
</main>

<?php include '/footer.php'; ?>