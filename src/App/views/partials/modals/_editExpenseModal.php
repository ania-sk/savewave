<section id="modal-edit-expense" class="modal-box" style="<?= (isset($activeForm) && $activeForm === 'editExpense') ? 'display: block;' : ''; ?>">

    <div class="modal-content">
        <div class="modal-header flex-container">
            <span id="close-edit-expense-modal" class="close">&times;</span>
            <ion-icon class="header-icon" name="create-outline"></ion-icon>
            <p>Edit expense</p>
        </div>

        <div class="modal-body flex-container">
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/expenses/update" class="modal-form-box flex-container">
                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <input type="hidden" name="form_type" value="editExpense">
                <input type="hidden" name="expenseId" id="edit-expense-id">

                <!-- "Amount" -->
                <div class="input-form-box flex-container">
                    <label for="edit-amount">Amount</label>
                    <input id="edit-expense-amount" type="number" name="expenseAmount"
                        value="<?php echo e($oldFormData['expenseAmount'] ??  '0'); ?>" />
                    <ion-icon id="cash-icon" class="modal-icon" name="cash-outline"></ion-icon>
                    <?php if ($activeForm === 'editExpense' && isset($errors['expenseAmount'])): ?>
                        <div>
                            <p class="error-text"><?php echo e($errors['expenseAmount'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- "Comment" -->
                <div class="input-form-box flex-container">
                    <label for="edit-comment">Comment</label>
                    <input id="edit-expense-comment" type="text" name="expenseComment"
                        placeholder="About expense..."
                        value="<?php echo e($oldFormData['expenseComment'] ?? ''); ?>" />
                    <ion-icon id="text-icon" class="modal-icon" name="document-text-outline"></ion-icon>
                    <?php if ($activeForm === 'editExpense' && isset($errors['expenseComment'])): ?>
                        <div>
                            <p class="error-text"><?php echo e($errors['expenseComment'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- "Date"   -->
                <div class="flex-container date-category-box">
                    <div class="input-form-box flex-container">
                        <label for="edit-expense-date">Date</label>
                        <input id="edit-expense-date" type="date" name="expenseDate"
                            value="<?php echo e($oldFormData['expenseDate'] ??  ''); ?>" />
                        <?php if ($activeForm === 'editExpense' && isset($errors['expenseDate'])): ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['expenseDate'][0]); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- "Category" -->
                    <div class="input-form-box flex-container">
                        <label for="edit-category">Category</label>
                        <select name="expenseCategory" id="edit-expense-category">
                            <option value="">Choose category:</option>
                            <?php if (!empty($expenseCategories)): ?>
                                <?php foreach ($expenseCategories as $expenseCategory): ?>
                                    <option
                                        value="<?php echo e($expenseCategory['id']); ?>"
                                        data-limit="<?= ($expenseCategory['monthly_limit'] !== null && $expenseCategory['monthly_limit'] != 0) ? e((int)$expenseCategory['monthly_limit']) : '' ?>"
                                        <?php echo ((isset($oldFormData['expenseCategory']) && $oldFormData['expenseCategory'] == $expenseCategory['id'])
                                            || (isset($expenseToEdit['expense_category_assigned_to_user_id']) && $expenseToEdit['expense_category_assigned_to_user_id'] == $expenseCategory['id'])) ? 'selected' : ''; ?>>
                                        <?php echo e($expenseCategory['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <option value="add_new_expense_category"
                                <?php echo (isset($oldFormData['expenseCategory']) && $oldFormData['expenseCategory'] === 'add_new_expense_category') ? 'selected' : ''; ?>>
                                + Add new category
                            </option>
                        </select>
                        <ion-icon id="cash-icon" class="modal-icon" name="medal-outline"></ion-icon>
                        <?php if ($activeForm === 'editExpense' && isset($errors['expenseCategory'])): ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['expenseCategory'][0]); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <button id="edit-expense-submit" type="submit" class="btn btn--modal">Save Changes</button>
            </form>

        </div>
    </div>

</section>