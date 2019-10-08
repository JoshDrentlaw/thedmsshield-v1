<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<?php
    if (file_exists('./index.js')) {
        echo '<script type="module" src="./index.js"></script>';
    }
?>

</body>
</html>