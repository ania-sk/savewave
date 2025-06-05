<section id="modal-expense" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span class="close">&times;</span>
            <ion-icon class="header-icon" name="remove-circle"></ion-icon>
            <p>Add expense</p>
        </div>
        <div class="modal-body flex-conteiner">

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

                <div class="flex-conteiner date-category-box">
                    <div class="input-form-box flex-conteiner">
                        <label for="date">Date</label>
                        <input id="date" type="date" name="expenseDate" value="<?php echo e($oldFormData['expenseDate'] ?? ''); ?>" />
                        <?php if (($activeForm === 'expense') && array_key_exists('expenseDate', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['expenseDate'][0]); ?></p>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="input-form-box flex-conteiner">
                        <label for="category">Category</label>
                        <select name="expenseCategory" id="category">
                            <option value="">Choose category:</option>

                            <?php if (!empty($expenseCategories)): ?>
                                <?php foreach ($expenseCategories as $expenseCategory): ?>
                                    <option value="<?php echo e($expenseCategory['id']); ?>"
                                        <?php echo isset($oldFormData['expenseCategory']) && $oldFormData['expenseCategory'] == $expenseCategory['id'] ? 'selected' : ''; ?>>
                                        <?php echo e($expenseCategory['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (($activeForm === 'expense') && array_key_exists('expenseCategory', $errors)) : ?>
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