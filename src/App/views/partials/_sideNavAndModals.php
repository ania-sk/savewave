<?php
$incomeError = false;
$expenseError = false;
?>
<section class="section-side-nav">
    <div class="logo-box mrg-bottom">
        <a class="link flex-conteiner" href="mainPage">
            <img
                class="logo-sw"
                src="/assets/imgs/save-wave-circle.png"
                alt="Save Wave logo" />
            <span class="header-save">Save Wave</span>
        </a>
    </div>
    <div class="arrow-toggle">
        <div class="side-nav-button-hide">
            <ion-icon class="hide-icon" name="chevron-back-outline"></ion-icon>
        </div>
        <div class="side-nav-button-show">
            <ion-icon class="hide-icon" name="chevron-forward-outline"></ion-icon>
        </div>
    </div>
    <!-- modal buttons -->
    <div class="btn-box flex-conteiner">
        <button id="btn-modal-income" class="btn btn--modal">
            <span>Add income</span>
        </button>
        <button id="btn-modal-expense" class="btn btn--modal">
            <span>Add expense</span>
        </button>
    </div>

    <div class="btn-box flex-conteiner">
        <button class="btn--icon">
            <ion-icon
                id="icon-btn-modal-income"
                class="header-icon"
                name="add-circle"></ion-icon>
        </button>
        <button class="btn--icon">
            <ion-icon
                id="icon-btn-modal-expense"
                class="header-icon"
                name="remove-circle"></ion-icon>
        </button>
    </div>

    <!-- sidenav -->
    <nav class="side-nav-box flex-conteiner">
        <a
            href="/incomes"
            class="link nav-box flex-conteiner"
            data-tooltip="Incomes">
            <ion-icon class="nav-icon" name="cash-outline"></ion-icon>
            <span>Incomes</span>
        </a>
        <a href="/expenses" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="file-tray-full-outline"></ion-icon>
            <span>Expenses</span>
        </a>
        <a href="#" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="stats-chart-outline"></ion-icon>
            <span>Balance</span>
        </a>
        <a href="#" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="heart-half-outline"></ion-icon>
            <span>Goals</span>
        </a>
        <a href="#" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="settings-outline"></ion-icon>
            <span>Settings</span>
        </a>
        <a href="/logout" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="log-out-outline"></ion-icon>
            <span>Logout</span>
        </a>
    </nav>
    <!-- MODALS -->

    <!-- INCOME -->
    <section id="modal-income" class="modal-box">
        <div class="modal-content">
            <div class="modal-header flex-conteiner">
                <span class="close">&times;</span>
                <ion-icon class="header-icon" name="add-circle"></ion-icon>
                <p>Add income</p>
            </div>

            <div class="modal-body flex-conteiner">

                <!-- FORM -->

                <form method="post" class="modal-form-box flex-conteiner">

                    <?php include $this->resolve("partials/_csrf.php"); ?>

                    <input type="hidden" name="form_type" value="income">

                    <div class="input-form-box flex-conteiner">
                        <label for="amount">Amount</label>
                        <input
                            id="amount"
                            type="number"
                            name="incomeAmount"
                            value="<?php echo e($oldFormData['incomeAmount'] ?? '0'); ?>" />
                        <ion-icon
                            id="cash-icon"
                            class="modal-icon"
                            name="cash-outline"></ion-icon>
                        <?php if (array_key_exists('incomeAmount', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['incomeAmount'][0]); ?></p>
                                <ion-icon class="error-icon" name="alert"></ion-icon>
                            </div>
                            <?php $incomeError = !empty($errors['incomeAmount'][0]); ?>
                        <?php endif; ?>

                    </div>

                    <div class="input-form-box flex-conteiner">
                        <label for="comment">Comment</label>
                        <input
                            type="text"
                            name="incomeComment"
                            id="comment"
                            placeholder="About income..."
                            value="<?php echo e($oldFormData['incomeComment'] ?? ''); ?>" />
                        <ion-icon
                            id="text-icon"
                            class="modal-icon"
                            name="document-text-outline"></ion-icon>
                    </div>

                    <div class="flex-conteiner date-category-box">
                        <div class="input-form-box flex-conteiner">
                            <label for="date">Date</label>
                            <input id="date" type="date" name="incomeDate"
                                value="<?php echo e($oldFormData['incomeDate'] ?? ''); ?>" />
                            <?php if (array_key_exists('incomeDate', $errors)) : ?>
                                <div>
                                    <p class="error-text"><?php echo e($errors['incomeDate'][0]); ?></p>
                                </div>
                                <?php $incomeError = !empty($errors['incomeDate'][0]); ?>
                            <?php endif; ?>
                        </div>

                        <div class="input-form-box flex-conteiner">
                            <label for="category">Category</label>
                            <select name="incomeCategory" id="category">
                                <option value="">Choose category:</option>

                                <option value="salary">Salary</option>
                                <option value="sale">Sale</option>
                                <option value="repayment">Debt repayment</option>
                                <option value="other">Other</option>
                            </select>
                            <?php if (array_key_exists('incomeCategory', $errors)) : ?>
                                <div>
                                    <p class="error-text"><?php echo e($errors['incomeCategory'][0]); ?></p>
                                </div>
                                <?php $incomeError = !empty($errors['incomeCategory'][0]); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button id="income-submit" type="submit" class="btn btn--modal">Save</button>

                </form>

            </div>
        </div>
    </section>

    <!-- EXPENSE -->
    <section id="modal-expense" class="modal-box">
        <div class="modal-content">
            <div class="modal-header flex-conteiner">
                <span class="close">&times;</span>
                <ion-icon class="header-icon" name="remove-circle"></ion-icon>
                <p>Add expense</p>
            </div>
            <div class="modal-body flex-conteiner">

                <!-- FORM -->

                <form method="post" class="modal-form-box flex-conteiner">
                    <?php $expenseError = false; ?>

                    <?php include $this->resolve("partials/_csrf.php"); ?>

                    <input type="hidden" name="form_type" value="expense">

                    <div class="input-form-box flex-conteiner">
                        <label for="amount">Amount</label>
                        <input
                            id="amount"
                            type="number"
                            name="expenseAmount"
                            value="<?php echo e($oldFormData['expenseAmount'] ?? '0'); ?>" />
                        <ion-icon
                            id="cash-icon"
                            class="modal-icon"
                            name="cash-outline"></ion-icon>
                        <?php if (array_key_exists('expenseAmount', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['expenseAmount'][0]); ?></p>
                                <ion-icon class="error-icon" name="alert"></ion-icon>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="input-form-box flex-conteiner">
                        <label for="comment">Comment</label>
                        <input
                            type="text"
                            name="expenseComment"
                            id="comment"
                            placeholder="About expense..."
                            value=" <?php echo e($oldFormData['expenseComment'] ?? ''); ?>" />
                        <ion-icon
                            id="text-icon"
                            class="modal-icon"
                            name="document-text-outline"></ion-icon>
                    </div>

                    <div class="flex-conteiner date-category-box">
                        <div class="input-form-box flex-conteiner">
                            <label for="date">Date</label>
                            <input id="date" type="date" name="expenseDate" value="<?php echo e($oldFormData['expenseDate'] ?? ''); ?>" />
                            <?php if (array_key_exists('expenseDate', $errors)) : ?>
                                <div>
                                    <p class="error-text"><?php echo e($errors['expenseDate'][0]); ?></p>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="input-form-box flex-conteiner">
                            <label for="category">Category</label>
                            <select name="expenseCategory" id="category">
                                <option value="">Choose category:</option>

                                <option value="salary">Bills</option>
                                <option value="sale">Shopping</option>
                                <option value="repayment">Entertainment</option>
                                <option value="other">Other</option>
                            </select>
                            <?php if (array_key_exists('expenseCategory', $errors)) : ?>
                                <div>
                                    <p class="error-text"><?php echo e($errors['expenseCategory'][0]); ?></p>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <button type="submit" class="btn btn--modal">Save</button>
                </form>
            </div>
        </div>
    </section>
    <!-- END MODALS -->
</section>