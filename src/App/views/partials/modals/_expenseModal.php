<section id="modal-expense" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span class="close">&times;</span>
            <ion-icon class="header-icon" name="remove-circle"></ion-icon>
            <p>Add expense</p>
        </div>
        <div class="modal-body flex-conteiner">

            <div class="modal-expense grid-cols-2">

                <!-- FORM -->
                <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
                <form method="post" action="/mainPage/expense" class="modal-form-box flex-conteiner">

                    <?php include $this->resolve("partials/_csrf.php"); ?>

                    <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
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
                        <?php if (($activeForm === 'expense') && array_key_exists('expenseAmount', $errors)) : ?>
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


                        <?php if (($activeForm === 'expense') && array_key_exists('expenseComment', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['expenseComment'][0]); ?></p>
                                <ion-icon class="error-icon" name="alert"></ion-icon>
                            </div>
                        <?php endif; ?>
                    </div>


                    <div class="input-form-box flex-conteiner">
                        <label for="date">Date</label>
                        <input id="date" type="date" name="expenseDate" value="<?php echo e($oldFormData['expenseDate'] ?? date('Y-m-d')); ?>" />
                        <ion-icon id="date-icon" class="modal-icon" name="calendar-outline"></ion-icon>
                        <?php if (($activeForm === 'expense') && array_key_exists('expenseDate', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['expenseDate'][0]); ?></p>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="input-form-box flex-conteiner">
                        <label for="category">Category</label>
                        <select name="expenseCategory" id="expenseCategory">
                            <option value="">Choose category:</option>

                            <?php if (!empty($expenseCategories)): ?>
                                <?php foreach ($expenseCategories as $expenseCategory): ?>
                                    <option value="<?php echo e($expenseCategory['id']); ?>"
                                        data-limit="<?= ($expenseCategory['monthly_limit'] !== null && $expenseCategory['monthly_limit'] != 0) ? e((int)$expenseCategory['monthly_limit']) : '' ?>"
                                        <?php echo isset($oldFormData['expenseCategory']) && $oldFormData['expenseCategory'] == $expenseCategory['id'] ? 'selected' : ''; ?>>
                                        <?php echo e($expenseCategory['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <option value="add_new_expense_category" <?php echo (isset($oldFormData['expenseCategory']) && $oldFormData['expenseCategory'] == 'add_new_expense_category') ? 'selected' : ''; ?>>
                                + Add new category
                            </option>
                        </select>
                        <ion-icon id="cash-icon" class="modal-icon" name="medal-outline"></ion-icon>
                        <?php if (($activeForm === 'expense') && array_key_exists('expenseCategory', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['expenseCategory'][0]); ?></p>
                            </div>
                        <?php endif; ?>

                    </div>


                    <button type="submit" class="btn btn--modal">Save</button>

                </form>

                <!-- CATEGORY LIMIT INFO -->
                <div class="category-limit-box flex-conteiner">
                    <div class="limit-box">
                        <h4>Category Limit Value</h4>
                        <p>You set the limit (funkcja pobrania kwoty limitu z bazy danych) monthly for that category.</p>
                    </div>
                    <div class="limit-box">
                        <h4>Category Limit Spent</h4>
                        <p>You spent on this category 456 this month</p>
                    </div>
                    <div class="limit-box">

                        <h4>Category Limit Left</h4>
                        <p>Limit balance after operation: (funkcja pobiera sumę wydatków dla wybranej kategorii i daty i oblicza pozostałą wartość limitu)</p>

                    </div>
                </div>
                <!-- ADD NEW Expense CATEGORY -->
                <?php include $this->resolve("partials/modals/_addExpenseCategoryModal.php"); ?>

            </div>
        </div>
</section>