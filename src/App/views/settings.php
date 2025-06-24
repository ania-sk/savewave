<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
?>

<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : '';
                echo ($activeForm === 'addIncomeCategory') ? 'modal-add-income-category-open modal-income-open' : '';
                echo ($activeForm === 'addExpenseCategory') ? 'modal-add-expense-category-open modal-expense-open' : ''; ?>">>

    <!-- MAIN SECTION -->
    <main class="section-main flex-conteiner">
        <div class="settings-heading flex-conteiner">
            <ion-icon class="nav-icon" name="settings-outline"></ion-icon>
            <p>Settings</p>
        </div>

        <!-- INCOME/EXPENSE CATEGORIES -->
        <div class="section-category-set  grid-cols-2 ">
            <!-- INCOME CATEGORIES -->
            <div>
                <div class="heading-secondary">
                    <p>Income categories</p>
                </div>

                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Categorie</th>
                                <th scope="col">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="No.">1.</td>
                                <td data-label="Categories">Income</td>
                                <td data-label="Edit" class="td-edit">
                                    <a href="/settings" class="btn-box btn--edit">
                                        <ion-icon class="edit--icon" name="create-outline"></ion-icon>
                                    </a>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>

            <!-- EXPENSE CATEGORIES -->
            <div>
                <div class="heading-secondary">
                    <p>Expense categories</p>
                </div>

                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Categorie</th>
                                <th scope="col">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="No.">1.</td>
                                <td data-label="Categories">Expense</td>
                                <td data-label="Edit" class="td-edit">
                                    <a href="/settings" class="btn-box btn--edit">
                                        <ion-icon class="edit--icon" name="create-outline"></ion-icon>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="No.">1.</td>
                                <td data-label="Categories">Expense</td>
                                <td data-label="Edit" class="td-edit">
                                    <a href="/settings" class="btn-box btn--edit">
                                        <ion-icon class="edit--icon" name="create-outline"></ion-icon>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </main>

    <?php
    include $this->resolve("partials/_scripts.php");
    ?>
</body>