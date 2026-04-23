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


        <!-- GOALS SECTION -->
        <section class="goals-section ">
            <div class="flex-conteiner">
                <div class="heading-tertiary">Your goals</div>
                <a href="/goals" class="btn--link">
                    View all →
                </a>
            </div>
            <div class="goal-cards-box grid-cols-4">
                <?php foreach ($goals as $goal): ?>
                    <div class="goal-card">
                        <div class="goal-card-header">
                            <h3 class="goal-title"><?php echo e($goal['goal_name']); ?></h3>
                        </div>

                        <div class="flex-conteiner gap-1 progress-box">
                            <div class="progress-bar">
                                <div class="progress-fill"
                                    style="width: <?php echo min(100, $goal['progress']); ?>%">
                                </div>
                            </div>
                            <p class="progress-text">
                                <?php echo number_format($goal['progress'], 1); ?>%
                            </p>
                        </div>

                        <div class="grid-rows-2 goal-data-box">
                            <p class="amount">
                                <ion-icon name="ribbon-outline"></ion-icon>
                                <?php echo e($goal['amount_saved']); ?> /
                                <?php echo e($goal['amount_needed']); ?> zł
                            </p>

                            <p class="deadline">
                                <ion-icon name="hourglass-outline"></ion-icon>
                                <?php echo e($goal['deadline']); ?>
                            </p>
                        </div>

                        <?php if ($goal['amount_saved'] >= $goal['amount_needed']): ?>
                            <div class="flex-conteiner goal-achieved-box gap-1">
                                <ion-icon name="trophy-outline"></ion-icon>
                                <span>Goal achieved!</span>
                            </div>
                        <?php endif; ?>

                        <?php if ($goal['amount_remaind'] > 0): ?>
                            <div class="flex-conteiner stats-box">
                                <ion-icon name="color-fill-outline"></ion-icon>

                                <span class="goal-remaind"><?php echo e($goal['amount_remaind']); ?> zł left</span>
                            </div>

                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>

        </section>

        <!-- LAST TRANSACTION SECTION -->
        <section class="transaction-section ">
            <div class="heading-tertiary">Last transactions</div>

            <!-- incomes -->
            <div class="grid-cols-3">
                <div class="incomes-box"></div>
                <!-- expenses -->
                <div class="expenses-box"></div>
                <!-- contributions -->
                <div class="contributions-box"></div>
            </div>
        </section>

        <!-- CURRENT BALANCE SECTION -->
        <section></section>

    </main>

    <?php
    include $this->resolve("partials/_sideNavAndModals.php");
    include $this->resolve("partials/modals/_addIncomeCategoryModal.php");
    include $this->resolve("partials/modals/_addExpenseCategoryModal.php");
    include $this->resolve("partials/_scripts.php");
    ?>

</body>

</html>