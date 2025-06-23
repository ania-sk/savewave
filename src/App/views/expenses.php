<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
include $this->resolve("partials/modals/_editExpenseModal.php");
?>

<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : '';
                echo ($activeForm === 'addIncomeCategory') ? 'modal-add-income-category-open modal-income-open' : '';
                echo ($activeForm === 'addExpenseCategory') ? 'modal-add-expense-category-open modal-expense-open' : ''; ?>">
    <!-- MAIN SECTION -->
    <!-- TABLE EXPENSE -->
    <main class="section-main flex-conteiner">
        <div class="expense-heading flex-conteiner">
            <ion-icon class="nav-icon" name="file-tray-full-outline"></ion-icon>
            <p>Expenses</p>

            <button class="header-btn--icon">
                <ion-icon
                    id="header-icon-btn-modal-expense"
                    class="header-icon-modal"
                    name="remove-circle"></ion-icon>
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
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td data-label="No."><?php echo e($i); ?></td>
                            <td data-label="Amount"><?php echo e($expense['amount']); ?></td>
                            <td data-label="Category"><?php echo e($expense['name']); ?></td>
                            <td data-label="Comment"><?php echo e($expense['expense_comment']); ?></td>
                            <td data-label="Date"><?php echo e($expense['formatted_date']); ?></td>
                            <td data-label="Edit" class="td-edit">
                                <a href="/expenses/<?php echo e($expense['id']); ?>" class="btn-box btn--edit">
                                    <ion-icon class="edit--icon" name="create-outline"></ion-icon>

                                </a>
                            </td>
                            <td data-label="Delete" class="td-delete">
                                <form action="/expenses/<?php echo e($expense['id']); ?>" method="POST">
                                    <input type="hidden" name="_METHOD" value="DELETE">

                                    <?php include $this->resolve("partials/_csrf.php"); ?>
                                    <button class="btn-box"><ion-icon class="delete--icon" name="trash-outline"></ion-icon></button>

                                </form>
                            </td>
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