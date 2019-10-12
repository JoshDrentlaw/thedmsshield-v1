<?php include '../header.php'; ?>

<main class="container">
    <section class="row align-items-end">
        <h1 class="mx-4">The Raimica Region</h1>
        <label class="font-weight-bold">Show markers: </label>
        <label class="switch ml-2">
            <input type="checkbox" id="toggle-markers">
            <span class="slider"></span>
        </label>
    </section>
    <section id="map-container">
        <img id="raimica-map" class="d-block" src="https://res.cloudinary.com/josh-drentlaw-web-development/image/upload/v1570429648/thedmsshield.com/Rimica_Region.jpg" />
    </section>
</main>

<?php include '../footer.php'; ?>