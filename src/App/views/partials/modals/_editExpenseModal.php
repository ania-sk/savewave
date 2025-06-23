<section id="modal-edit-expense" class="modal-box" style="<?= (isset($activeForm) && $activeForm === 'editExpense') ? 'display: block;' : ''; ?>">

    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span id="close-edit-expense-modal" class="close">&times;</span>
            <ion-icon class="header-icon" name="create-outline"></ion-icon>
            <p>Edit expense</p>
        </div>

        <div class="modal-body flex-conteiner">
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/expenses/<?php echo e($expenseToEdit['id']); ?>" class="modal-form-box flex-conteiner">
                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <input type="hidden" name="form_type" value="editExpense">
                <input type="hidden" name="expenseId" value="<?php echo e($expenseToEdit['id'] ?? ''); ?>">

                <!-- "Amount" -->
                <div class="input-form-box flex-conteiner">
                    <label for="edit-amount">Amount</label>
                    <input id="edit-amount" type="number" name="expenseAmount"
                        value="<?php echo e($oldFormData['expenseAmount'] ?? ($expenseToEdit['amount'] ?? '0')); ?>" />
                    <ion-icon id="cash-icon" class="modal-icon" name="cash-outline"></ion-icon>
                    <?php if ($activeForm === 'editExpense' && isset($errors['expenseAmount'])): ?>
                        <div>
                            <p class="error-text"><?php echo e($errors['expenseAmount'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- "Comment" -->
                <div class="input-form-box flex-conteiner">
                    <label for="edit-comment">Comment</label>
                    <input id="edit-comment" type="text" name="expenseComment"
                        placeholder="About expense..."
                        value="<?php echo e($oldFormData['expenseComment'] ?? ($expenseToEdit['expense_comment'] ?? '')); ?>" />
                    <ion-icon id="text-icon" class="modal-icon" name="document-text-outline"></ion-icon>
                    <?php if ($activeForm === 'editExpense' && isset($errors['expenseComment'])): ?>
                        <div>
                            <p class="error-text"><?php echo e($errors['expenseComment'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- "Date"   -->
                <div class="flex-conteiner date-category-box">
                    <div class="input-form-box flex-conteiner">
                        <label for="edit-date">Date</label>
                        <input id="edit-date" type="date" name="expenseDate"
                            value="<?php echo e($oldFormData['expenseDate'] ?? ($expenseToEdit['formatted_date'] ?? '')); ?>" />
                        <?php if ($activeForm === 'editExpense' && isset($errors['expenseDate'])): ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['expenseDate'][0]); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- "Category" -->
                    <div class="input-form-box flex-conteiner">
                        <label for="edit-category">Category</label>
                        <select name="expenseCategory" id="edit-category">
                            <option value="">Choose category:</option>
                            <?php if (!empty($expenseCategories)): ?>
                                <?php foreach ($expenseCategories as $expenseCategory): ?>
                                    <option value="<?php echo e($expenseCategory['id']); ?>"
                                        <?php echo ((isset($oldFormData['expenseCategory']) && $oldFormData['expenseCategory'] == $expenseCategory['id'])
                                            || (isset($expenseToEdit['expense_category_assigned_to_user_id']) && $expenseToEdit['expense_category_assigned_to_user_id'] == $expenseCategory['id'])) ? 'selected' : ''; ?>>
                                        <?php echo e($expenseCategory['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <option value="add_new"
                                <?php echo (isset($oldFormData['expenseCategory']) && $oldFormData['expenseCategory'] === 'add_new') ? 'selected' : ''; ?>>
                                + Add new category
                            </option>
                        </select>
                        <?php if ($activeForm === 'editExpense' && isset($errors['expenseCategory'])): ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['expenseCategory'][0]); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <button id="edit-expense-submit" type="submit" class="btn btn--modal">Save Changes</button>
            </form>

            <?php include $this->resolve("partials/modals/_addExpenseCategoryModal.php"); ?>
        </div>
    </div>

</section>