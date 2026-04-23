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

        <!-- CURRENT BALANCE SECTION -->
        <section class="section-balance flex-conteiner">
            <div class="balance-box">
                <div class=" flex-conteiner">
                    <p>Your savings: <?php echo e($balance); ?></p>
                    <?php if ($balance > 0): ?>
                        <ion-icon name="thumbs-up-outline"></ion-icon>
                    <?php else: ?>
                        <ion-icon name="thumbs-down-outline"></ion-icon>
                    <?php endif; ?>
                </div>
                <div class="mini-stats">
                    <p>+<?php echo e($incomeSum); ?> zł / -<?php echo e($expenseSum); ?> zł</p>
                </div>
            </div>
        </section>

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
            <div class="flex-conteiner">
                <div class="heading-tertiary">Last transactions</div>
                <a href="/balance" class="btn--link">View all →</a>
            </div>

            <div class="grid-cols-3 gap-4">
                <!-- incomes -->
                <div class="incomes-box">
                    <div class="flex-conteiner">
                        <h4>Incomes</h4>
                        <a href="/incomes" class="btn--link">View all →</a>
                    </div>

                    <?php foreach ($incomes as $income): ?>
                        <div class="transaction-item flex-conteiner income-item">
                            <div class="transaction-left">
                                <p class="transaction-category">
                                    <?php echo e($income['name']); ?>
                                </p>
                                <p class="transaction-date">
                                    <?php echo e($income['formatted_date']); ?>
                                </p>
                            </div>

                            <div class="transaction-right">
                                <p class="transaction-amount positive">
                                    +<?php echo e($income['amount']); ?> zł
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- expenses -->
                <div class="expenses-box">
                    <div class="flex-conteiner">
                        <h4>Expenses</h4>
                        <a href="/incomes" class="btn--link">View all →</a>
                    </div>

                    <?php foreach ($expenses as $expense): ?>
                        <div class="transaction-item flex-conteiner expense-item">
                            <div class="transaction-left">
                                <p class="transaction-category">
                                    <?php echo e($expense['name']); ?>
                                </p>
                                <p class="transaction-date">
                                    <?php echo e($expense['formatted_date']); ?>
                                </p>
                            </div>

                            <div class="transaction-right">
                                <p class="transaction-amount negative">
                                    -<?php echo e($expense['amount']); ?> zł
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- contributions -->
                <div class="contributions-box">
                    <div class="flex-conteiner">
                        <h4>Contributions</h4>
                        <a href="/goals" class="btn--link">View all →</a>
                    </div>

                    <?php foreach ($contributions as $contribution): ?>
                        <div class="transaction-item flex-conteiner expense-item">
                            <div class="transaction-left">
                                <p class="transaction-category">
                                    <?php echo e($contribution['goal_name']); ?>
                                </p>
                                <p class="transaction-date">
                                    <?php echo e($contribution['formatted_date']); ?>
                                </p>
                            </div>

                            <div class="transaction-right">
                                <p class="transaction-amount">
                                    <?php echo e($contribution['amount']); ?> zł
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
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