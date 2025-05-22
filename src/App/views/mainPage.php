<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
?>

<body class="<?php isset($errors) ? 'modal-income-open' : '' ?>">
    <!-- MAIN SECTION -->
    <main class="section-main">
        <header class="main-header">
            <p>Welcome to savings</p>
        </header>
    </main>

    <?php
    include $this->resolve("partials/_scripts.php");
    ?>
</body>

</html>