<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
?>


<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : '';
                echo ($activeForm === 'addIncomeCategory') ? 'modal-add-income-category-open modal-income-open' : '';
                echo ($activeForm === 'addExpenseCategory') ? 'modal-add-expense-category-open modal-expense-open' : ''; ?>">


    <!-- MAIN SECTION -->
    <main class="section-main">
        <header class="main-header">
            <p>Welcome to savings, <?php echo e($username['username']); ?>!</p>
        </header>
    </main>

    <?php
    include $this->resolve("partials/_scripts.php");
    ?>

</body>

</html>