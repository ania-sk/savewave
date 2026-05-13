<?php
include $this->resolve("partials/_header.php");
?>

<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : '';
                echo ($activeForm === 'addIncomeCategory') ? 'modal-add-income-category-open modal-income-open' : '';
                echo ($activeForm === 'addExpenseCategory') ? 'modal-add-expense-category-open modal-expense-open' : ''; ?>">
    <!-- MAIN SECTION -->

    <main class="section-main flex-container">
        <div class="expense-heading flex-container">
            <ion-icon class="hamburger-menu" name="menu-outline"></ion-icon>
            <div class=" flex-container">
                <ion-icon class="nav-icon" name="file-tray-full-outline"></ion-icon>
                <p>Expenses</p>
            </div>
            <button class="header-btn--icon" data-tooltip="Add expense">
                <ion-icon
                    id="header-icon-btn-modal-expense"
                    class="header-icon-modal"
                    name="remove-circle"></ion-icon>
            </button>
        </div>
        <!-- DATE FILTER -->
        <div class="flex-container">
            <form method="get" action="/expenses" class="date-filter date-form-box">
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
                <a href="/expenses" class="btn btn--link btn--clean">Clean filter</a>
            </form>
        </div>

        <!-- EXPENSES CHART -->
        <section class="section-chart">
            <div class="expense-chart ">
                <div class="total-box flex-container">
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

        <!-- TABLE EXPENSE -->
        <section class="table-expense-box">
            <table class="table table-expense" id="expensesTableBody">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Category</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Date</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = $offset + 1;
                    $sum = 0; ?>
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td data-label="No."><?php echo e($i); ?>.</td>
                            <td data-label="Amount"><?php echo e($expense['amount']); ?></td>
                            <td data-label="Category"><?php echo e($expense['name']); ?>
                                <?php if ($expense['monthly_limit'] !== null && $expense['monthly_limit'] != 0) : ?>
                                    <span class="limit-label"><?= e((int)$expense['monthly_limit']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Comment"><?php echo e($expense['expense_comment']); ?></td>
                            <td data-label="Date"><?php echo e($expense['formatted_date']); ?></td>
                            <td data-label="Edit" class="td-edit">
                                <!-- <a href="/expenses/<?php echo e($expense['id']); ?>" class="btn-box btn--edit"> -->
                                <button
                                    class="btn-box btn--edit-expense"
                                    data-expense-id="<?php echo e($expense['id']); ?>"
                                    data-expense-amount="<?php echo e($expense['amount']); ?>"
                                    data-expense-category-id="<?php echo e($expense['categoryId']); ?>"
                                    data-expense-category-name="<?php echo e($expense['name']); ?>"
                                    data-expense-category-active="<?php echo e($expense['active']); ?>"
                                    data-expense-comment="<?php echo e($expense['expense_comment']); ?>"
                                    data-expense-date="<?php echo e($expense['formatted_date']); ?>">
                                    <ion-icon class="edit--icon" name="create-outline"></ion-icon>
                                </button>


                                <!-- </a> -->
                            </td>
                            <td data-label="Delete" class="td-delete">
                                <form action="/expenses/<?php echo e($expense['id']); ?>" method="POST">
                                    <input type="hidden" name="_METHOD" value="DELETE">

                                    <?php include $this->resolve("partials/_csrf.php"); ?>
                                    <button onclick="return confirm('Remove this expense?')" class="btn-box"><ion-icon class="delete--icon" name="trash-outline"></ion-icon></button>

                                </form>
                            </td>
                        </tr>
                        <?php $i = $i + 1;
                        $sum =  $sum + $expense['amount']; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td>Sum:</td>
                        <td><?php echo e($sum); ?></td>
                    </tr>

                </tbody>
            </table>
        </section>

        <!-- pagination for the table -->
        <div class="pagination--box" id="expensesTablePagination">
            <!-- PREV -->
            <button
                onclick="loadPage(<?= $currentPage - 1 ?>)"
                class="btn--page"
                <?= $currentPage <= 1 ? 'disabled' : '' ?>>
                &lt;
            </button>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <button onclick="loadPage(<?php echo $i; ?>)" class="<?php echo $i === $currentPage ? 'active' : ''; ?> btn--page">
                    <?php echo $i; ?>
                </button>
            <?php endfor; ?>

            <!-- NEXT -->
            <button
                onclick="loadPage(<?= $currentPage + 1 ?>)"
                class="btn--page"
                <?= $currentPage >= $totalPages ? 'disabled' : '' ?>>
                &gt;
            </button>
        </div>
    </main>

    <?php
    include $this->resolve("partials/modals/_editExpenseModal.php");
    include $this->resolve("partials/_sideNavAndModals.php");
    include $this->resolve("partials/modals/_addIncomeCategoryModal.php");
    include $this->resolve("partials/modals/_addExpenseCategoryModal.php");
    include $this->resolve("partials/_scripts.php");
    ?>
</body>
<?php
include $this->resolve("partials/_footer.php");
?>

</html>