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