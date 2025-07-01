<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
?>

<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : '';
                echo ($activeForm === 'addIncomeCategory') ? 'modal-add-income-category-open modal-income-open' : '';
                echo ($activeForm === 'addExpenseCategory') ? 'modal-add-expense-category-open modal-expense-open' : ''; ?>">
    <!-- MAIN SECTION -->
    <main class="section-main flex-conteiner">
        <div class="balance-heading flex-conteiner">
            <ion-icon class="nav-icon" name="stats-chart-outline"></ion-icon>
            <p>Balance</p>
        </div>
        <!-- DATE FILTER -->
        <section class="flex-conteiner">
            <form method="get" action="/balance" class="date-filter date-form-box">
                <div class="">
                    <label for="start_date">From</label>
                    <input id="start_date" type="date" name="start_date"
                        value="<?php echo e($start_date ?? ''); ?>" />
                </div>
                <div class="">
                    <label for="end_date">To</label>
                    <input id="end_date" type="date" name="end_date"
                        value="<?php echo e($end_date ?? ''); ?>" />
                </div>
                <button type="submit" class="btn btn--form">Find</button>
                <a href="/balance" class="btn btn--link btn--clean">Clean filter</a>
            </form>
        </section>
        <!-- BALANCE -->
        <section class="section-balance">
            <div class="balance-box flex-conteiner">
                <p>Your savings: <?php echo e($balance); ?></p>
                <?php if ($balance > 0): ?>
                    <ion-icon name="thumbs-up-outline"></ion-icon>
                <?php else: ?>
                    <ion-icon name="thumbs-down-outline"></ion-icon>
                <?php endif; ?>
            </div>
        </section>
        <!-- INCOMES/EXPENSES CHARTS -->
        <section class="section-charts grid-cols-2">
            <div class="incomes-chart ">
                <div class="chart-heading flex-conteiner">
                    <ion-icon class="nav-icon" name="cash-outline"></ion-icon>
                    <p>Incomes</p>
                </div>
                <div class="total-box flex-conteiner">
                    <p>Total: <?php echo e($totalIncome); ?></p>
                    <ion-icon name="trending-up-outline"></ion-icon>

                </div>
                <div class="chart-container" style="max-width: 500px; margin: 2rem auto;">
                    <canvas
                        id="incomePieChart"
                        data-income-chart-labels='<?php echo e(json_encode($incomeChartLabels, JSON_UNESCAPED_UNICODE)); ?>'
                        data-income-chart-data='<?php echo e(json_encode($incomeChartData)); ?>'
                        style="max-width:500px; margin:2rem auto;"></canvas>

                </div>
            </div>
            <div class="expenses-chart">
                <div class="chart-heading flex-conteiner">
                    <ion-icon class="nav-icon" name="file-tray-full-outline"></ion-icon>
                    <p>Expenses</p>
                </div>
                <div class="total-box flex-conteiner">
                    <p>Total: <?php echo e($totalExpense); ?></p>
                    <ion-icon name="trending-down-outline"></ion-icon>

                </div>
                <div class="chart-container" style="max-width: 500px; margin: 2rem auto;">
                    <canvas
                        id="expensePieChart"
                        data-expense-chart-labels='<?php echo e(json_encode($expenseChartLabels, JSON_UNESCAPED_UNICODE)); ?>'
                        data-expense-chart-data='<?php echo e(json_encode($expenseChartData)); ?>'
                        style="max-width:500px; margin:2rem auto;"></canvas>

                </div>
            </div>
        </section>
        <!-- TRANSACTIONS TABLE -->
        <section class="transacrions-table-box">
            <div class="table-heading flex-conteiner">
                <ion-icon name="repeat-outline"></ion-icon>
                <p>Transactions</p>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Type</th>
                        <th scope="col">Category</th>

                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td data-label="No."><?php echo e($i); ?>.</td>
                            <td data-label="Amount"><?php echo e($transaction['amount']); ?></td>
                            <td data-label="Type"><?php echo e($transaction['type']); ?></td>
                            <td data-label="Category"><?php echo e($transaction['category']); ?></td>
                            <td data-label="Date"><?php echo e($transaction['date']); ?></td>
                        </tr>
                        <?php $i = $i + 1; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <?php
    include $this->resolve("partials/_scripts.php");
    ?>
</body>

</html>