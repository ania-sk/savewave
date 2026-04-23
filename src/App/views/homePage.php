<?php
include $this->resolve("partials/_header.php");

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
        <section class="box">
            <!-- GOALS SECTION -->
            <section class="">
                <div class="heading-tertiary">Your goals</div>
                <div class="grid-cols-4"></div>

            </section>

            <!-- LAST TRANSACTION SECTION -->
            <section>

            </section>

            <!-- CURRENT BALANCE SECTION -->
            <section></section>
        </section>
    </main>

    <?php
    include $this->resolve("partials/_sideNavAndModals.php");
    include $this->resolve("partials/modals/_addIncomeCategoryModal.php");
    include $this->resolve("partials/modals/_addExpenseCategoryModal.php");
    include $this->resolve("partials/_scripts.php");
    ?>

</body>

</html>