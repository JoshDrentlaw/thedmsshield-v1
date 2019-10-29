<?php
    include '../includes/header.php';
    use Symfony\Component\Dotenv\Dotenv;

    $dotenv = new Dotenv();
    $dotenv->load('../.env');    
    
    $host = $_ENV['HOST'];
    $user = $_ENV['USER'];
    $password = $_ENV['PASSWORD'];
    $dbname = $_ENV['DB'];

    // Set DSN
    $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;

    // Create PDO instance
    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }

    // Named Params
    $sql = 'SELECT * FROM thedmsshield.raimica_markers';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $markers = $stmt->fetchAll();

    if (isset($_POST['note_title'])) {
        $post['id'] = filter_input(INPUT_POST, 'id');
        $post['note_title'] = filter_input(INPUT_POST, 'note_title');
        $post['note_body'] = filter_input(INPUT_POST, 'note_body');
        $sql = 'UPDATE raimica_markers SET note_title = :note_title, note_body = :note_body WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $post['id'],
            'note_title' => $post['note_title'],
            'note_body' => $post['note_body']
        ]);
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
        <button class="ml-2 mb-2 btn btn-primary btn-sm" id="add-marker">Add Marker</button>
    </section>
    <section id="map-container">
        <?php
            foreach ($markers as $marker) {
                echo '
                    <button
                        id="marker' . $marker->id . '"
                        class="marker btn show-marker"
                        data-id="' . $marker->id . '"
                        data-container="body"
                        data-toggle="popover"
                        data-placement="top"
                        data-title="<span class=\'note-title\' data-id=\'' . $marker->id . '\'>' . $marker->note_title . '</span>"
                        data-content="
                            <p data-id=\'' . $marker->id . '\' class=\'note-body\'>' . $marker->note_body . '</p>
                            <button data-id=\'' . $marker->id . '\' class=\'edit btn btn-primary btn-sm\'>Edit</button>
                            <button data-id=\'' . $marker->id . '\' class=\'save btn btn-primary btn-sm\' disabled>Save</button>
                        "
                        style="top: ' . $marker->top . '%; left: ' . $marker->left . '%;"
                    ></button>
                ';
            }
        ?>
        <img id="raimica-map" src="./raimica_map.jpg" data-click="false" />
    </section>
</main>

<?php include '../includes/footer.php'; ?>