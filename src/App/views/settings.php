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
                <!-- add new income form -->
                <div class="add-category-form">
                    <form action="/settings/addIncomeCategory" method="post">
                        <div class="category-field">
                            <label for="income-category">New Income Category:</label>
                            <input type="text" id="income-category" name="categoryName" placeholder="Enter new income category" required>
                        </div>
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
                <!-- add new expense form -->
                <div class="add-category-form">
                    <form action="/settings/addExpenseCategory" method="post">
                        <div class="category-field">
                            <label for="expense-category">New Expense Category:</label>
                            <input type="text" id="expense-category" name="categoryName" placeholder="Enter new expense category" required>
                        </div>
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
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="" required>
                        </div>
                        <button type="submit" class="btn btn--full btn--modal">Update Email</button>
                    </form>
                </div>

                <!--  Username -->
                <div class="account-edit-section">
                    <form class="account-form" action="/settings/updateUsername" method="post">
                        <div class="account-field">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" value="" required>
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