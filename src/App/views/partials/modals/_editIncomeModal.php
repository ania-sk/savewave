<section id="modal-edit-income" class="modal-box" style="<?= (isset($activeForm) && $activeForm === 'editIncome') ? 'display: block;' : ''; ?>">
    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span id="close-edit-income-modal" class="close">&times;</span>
            <ion-icon class="header-icon" name="create-outline"></ion-icon>
            <p>Edit income</p>
        </div>

        <div class="modal-body flex-conteiner">
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/incomes/updateIncome" class="modal-form-box flex-conteiner">
                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <input type="hidden" name="form_type" value="editIncome">
                <input type="hidden" name="incomeId" value="<?php echo e($incomeToEdit['id'] ?? ''); ?>">

                <!-- "Amount" -->
                <div class="input-form-box flex-conteiner">
                    <label for="edit-amount">Amount</label>
                    <input id="edit-amount" type="number" name="incomeAmount"
                        value="<?php echo e($oldFormData['incomeAmount'] ?? ($incomeToEdit['amount'] ?? '0')); ?>" />
                    <ion-icon id="cash-icon" class="modal-icon" name="cash-outline"></ion-icon>
                    <?php if ($activeForm === 'editIncome' && isset($errors['incomeAmount'])): ?>
                        <div>
                            <p class="error-text"><?php echo e($errors['incomeAmount'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- "Comment" -->
                <div class="input-form-box flex-conteiner">
                    <label for="edit-comment">Comment</label>
                    <input id="edit-comment" type="text" name="incomeComment"
                        placeholder="About income..."
                        value="<?php echo e($oldFormData['incomeComment'] ?? ($incomeToEdit['income_comment'] ?? '')); ?>" />
                    <ion-icon id="text-icon" class="modal-icon" name="document-text-outline"></ion-icon>
                    <?php if ($activeForm === 'editIncome' && isset($errors['incomeComment'])): ?>
                        <div>
                            <p class="error-text"><?php echo e($errors['incomeComment'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- "Date"   -->
                <div class="flex-conteiner date-category-box">
                    <div class="input-form-box flex-conteiner">
                        <label for="edit-date">Date</label>
                        <input id="edit-date" type="date" name="incomeDate"
                            value="<?php echo e($oldFormData['incomeDate'] ?? ($incomeToEdit['formatted_date'] ?? '')); ?>" />
                        <?php if ($activeForm === 'editIncome' && isset($errors['incomeDate'])): ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['incomeDate'][0]); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- "Category" -->
                    <div class="input-form-box flex-conteiner">
                        <label for="edit-category">Category</label>
                        <select name="incomeCategory" id="edit-category">
                            <option value="">Choose category:</option>
                            <?php if (!empty($incomeCategories)): ?>
                                <?php foreach ($incomeCategories as $incomeCategory): ?>
                                    <option value="<?php echo e($incomeCategory['id']); ?>"
                                        <?php echo ((isset($oldFormData['incomeCategory']) && $oldFormData['incomeCategory'] == $incomeCategory['id'])
                                            || (isset($incomeToEdit['income_category_assigned_to_user_id']) && $incomeToEdit['income_category_assigned_to_user_id'] == $incomeCategory['id'])) ? 'selected' : ''; ?>>
                                        <?php echo e($incomeCategory['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <option value="add_new"
                                <?php echo (isset($oldFormData['incomeCategory']) && $oldFormData['incomeCategory'] === 'add_new') ? 'selected' : ''; ?>>
                                + Add new category
                            </option>
                        </select>
                        <?php if ($activeForm === 'editIncome' && isset($errors['incomeCategory'])): ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['incomeCategory'][0]); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <button id="edit-income-submit" type="submit" class="btn btn--modal">Save Changes</button>
            </form>

            <?php include $this->resolve("partials/modals/_addIncomeCategoryModal.php"); ?>
        </div>
    </div>
</section>