<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
?>

<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : ''; ?>">
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
        <div class="table-income-box">
            <table class="table table-income">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Category</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($incomes as $income): ?>
                        <tr>
                            <td data-label="No."><?php echo e($i); ?></td>
                            <td data-label="Amount"><?php echo e($income['amount']); ?></td>
                            <td data-label="Category"><?php echo e($income['name']); ?></td>
                            <td data-label="Comment"><?php echo e($income['income_comment']); ?></td>
                            <td data-label="Date"><?php echo e($income['formatted_date']); ?></td>
                        </tr>
                        <?php $i = $i + 1; ?>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </main>
    <?php
    include $this->resolve("partials/_scripts.php");
    ?>

</body>

</html>