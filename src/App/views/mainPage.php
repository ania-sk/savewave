<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
?>


<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : '';
                echo ($activeForm === 'addCategory') ? 'modal-add-income-category-open modal-income-open' : '' ?>">


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