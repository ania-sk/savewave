<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
?>

<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : ''; ?>">>

    <!-- MAIN SECTION -->
    <main class="section-main flex-conteiner">
        <div class="settings-heading flex-conteiner">
            <ion-icon class="nav-icon" name="settings-outline"></ion-icon>
            <p>Settings</p>
        </div>

        <!-- INCOME/EXPENSE CATEGORIES -->
        <section class="section-category-set  grid-cols-2 ">
            <!-- INCOME CATEGORIES -->
            <div>
                <div class="heading-tertiary">
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
                            <?php $i = 1; ?>
                            <?php foreach ($incomeCategories as $incomeCategory): ?>
                                <tr>
                                    <td data-label="No."><?php echo e($i); ?></td>
                                    <td data-label="Categories"><?php echo e($incomeCategory['name']); ?></td>
                                    <td data-label="Edit" class="td-edit">
                                        <a href="/settings" class="btn-box btn--edit">
                                            <ion-icon class="edit--icon" name="create-outline"></ion-icon>
                                        </a>
                                    </td>
                                </tr>
                                <?php $i = $i + 1; ?>
                            <?php endforeach; ?>
                        </tbody>

                    </table>

                </div>
                <!-- add new income form -->
                <div class="add-category-form">
                    <form action="/settings/addIncomeCategory" method="post">
                        <?php include $this->resolve("partials/_csrf.php"); ?>
                        <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                        <div class="category-field">
                            <label for="income-category">New Income Category:</label>
                            <input type="text" id="income-category" name="newCategoryName" placeholder="Enter new income category">
                        </div>
                        <?php if (array_key_exists('newCategoryName', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['newCategoryName'][0]); ?></p>
                            </div>
                        <?php endif; ?>
                        <button id="add-income-category-btn" class="btn btn--modal">Add new categorie</button>
                    </form>
                </div>
            </div>

            <!-- EXPENSE CATEGORIES -->
            <div>
                <div class="heading-tertiary">
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
                            <?php $i = 1; ?>
                            <?php foreach ($expenseCategories as $expenseCategory): ?>
                                <tr>
                                    <td data-label="No."><?php echo e($i); ?></td>
                                    <td data-label="Categories"><?php echo e($expenseCategory['name']); ?></td>
                                    <td data-label="Edit" class="td-edit">
                                        <a href="/settings" class="btn-box btn--edit">
                                            <ion-icon class="edit--icon" name="create-outline"></ion-icon>
                                        </a>
                                    </td>
                                </tr>
                                <?php $i = $i + 1; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
                <!-- add new expense form -->
                <div class="add-category-form">
                    <form action="/settings/addExpenseCategory" method="post">
                        <?php include $this->resolve("partials/_csrf.php"); ?>
                        <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                        <div class="category-field">
                            <label for="expense-category">New Expense Category:</label>
                            <input type="text" id="expense-category" name="newCategoryName" placeholder="Enter new expense category" required>
                        </div>
                        <?php if (array_key_exists('newCategoryName', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['newCategoryName'][0]); ?></p>
                            </div>
                        <?php endif; ?>

                        <button id="add-expense-category-btn" class="btn btn--modal" type="submit">Add new categorie</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- ACCOUNT DETAILS -->
        <section class="account-sec flex-conteiner">
            <div class="account-details-container">
                <div class="heading-tertiary">
                    <p>Account Details</p>
                </div>

                <!--  Email -->
                <div class="account-edit-section">
                    <form class="account-form" action="/settings/updateEmail" method="post">
                        <div class="account-field">
                            <label for="email">Email: <?php echo e($email['email']); ?></label>
                            <input type="email" id="email" name="email" placeholder="Enter new email" value="" required>
                        </div>
                        <button type="submit" class="btn btn--full btn--modal">Update Email</button>
                    </form>
                </div>

                <!--  Username -->
                <div class="account-edit-section">
                    <form class="account-form" action="/settings/updateUsername" method="post">
                        <div class="account-field">
                            <label for="username">Username: <?php echo e($username['username']); ?></label>
                            <input type="text" id="username" name="username" value="" placeholder="Enetr new Username" required>
                        </div>
                        <button type="submit" class="btn btn--full btn--modal">Update Username</button>
                    </form>
                </div>

                <!-- Password -->
                <div class="account-edit-section">
                    <form class="account-form" action="/settings/updatePassword" method="post">
                        <div class="account-field">
                            <label for="password">New Password:</label>
                            <input type="password" id="password" name="password" placeholder="Enter new password">
                        </div>
                        <button type="submit" class="btn btn--full btn--modal">Update Password</button>
                    </form>
                </div>

                <!-- delete account -->
                <div class="account-delete">
                    <p>Danger Zone</p>
                    <form action="/settings/deleteAccount" method="post" onsubmit="return confirm('Czy na pewno chcesz usunąć konto? Operacja jest nieodwracalna.');">
                        <button type="submit" class="btn btn--modal btn--delete">Delete Account</button>
                    </form>
                </div>
            </div>
        </section>

    </main>

    <?php
    include $this->resolve("partials/_scripts.php");
    ?>
</body>