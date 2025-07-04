<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
include $this->resolve("partials/modals/_editIncomeModal.php");
?>

<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : '';
                echo ($activeForm === 'addIncomeCategory') ? 'modal-add-income-category-open modal-income-open' : '';
                echo ($activeForm === 'addExpenseCategory') ? 'modal-add-expense-category-open modal-expense-open' : ''; ?>">
    <!-- MAIN SECTION -->
    <!-- TABLE INCOME -->
    <main class="section-main flex-conteiner">
        <div class="incomes-heading flex-conteiner">
            <ion-icon class="nav-icon" name="cash-outline"></ion-icon>
            <p>Incomes</p>

            <button class="header-btn--icon">
                <ion-icon
                    id="header-icon-btn-modal-income"
                    class="header-icon-modal"
                    name="add-circle"></ion-icon>
            </button>
        </div>
        <div class="flex-conteiner">
            <form method="get" action="/incomes" class="date-filter date-form-box">
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
                <a href="/incomes" class="btn btn--link btn--clean">Clean filter</a>


            </form>
        </div>


        <div class="table-income-box">
            <table class="table table-income">
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
                    <?php $i = 1;
                    $sum = 0; ?>
                    <?php foreach ($incomes as $income): ?>
                        <tr>
                            <td data-label="No."><?php echo e($i); ?>.</td>
                            <td data-label="Amount"><?php echo e($income['amount']); ?></td>
                            <td data-label="Category"><?php echo e($income['name']); ?></td>
                            <td data-label="Comment"><?php echo e($income['income_comment']); ?></td>
                            <td data-label="Date"><?php echo e($income['formatted_date']); ?></td>
                            <td data-label="Edit" class="td-edit">
                                <a href="/incomes/<?php echo e($income['id']); ?>" class=" btn-box btn--edit">
                                    <ion-icon class="edit--icon" name="create-outline"></ion-icon>

                                </a>
                            </td>
                            <td data-label="Delete" class="td-delete">
                                <form action="/incomes/<?php echo e($income['id']); ?>" method="POST">
                                    <input type="hidden" name="_METHOD" value="DELETE">

                                    <?php include $this->resolve("partials/_csrf.php"); ?>
                                    <button class="btn-box"><ion-icon class="delete--icon" name="trash-outline"></ion-icon></button>

                                </form>
                            </td>
                        </tr>
                        <?php $i = $i + 1;
                        $sum =  $sum + $income['amount'] ?>
                    <?php endforeach; ?>
                    <tr>
                        <td>Sum:</td>
                        <td><?php echo e($sum); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="chart-container" style="max-width: 500px; margin: 2rem auto;">
            <canvas
                id="incomePieChart"
                data-chart-labels='<?php echo e(json_encode($chartLabels, JSON_UNESCAPED_UNICODE)); ?>'
                data-chart-data='<?php echo e(json_encode($chartData)); ?>'
                style="max-width:500px; margin:2rem auto;"></canvas>

        </div>
    </main>
    <?php
    include $this->resolve("partials/_scripts.php");
    ?>

</body>

</html>