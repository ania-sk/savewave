<section id="modal-income" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span class="close">&times;</span>
            <ion-icon class="header-icon" name="add-circle"></ion-icon>
            <p>Add income</p>
        </div>

        <div class="modal-body flex-conteiner">

            <!-- FORM -->

            <form method="post" action="/mainPage/income" class="modal-form-box flex-conteiner">

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
                    <?php if (($activeForm === 'income') && array_key_exists('incomeAmount', $errors)) : ?>
                        <div>
                            <p class="error-text"><?php echo e($errors['incomeAmount'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
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
                    <?php if (($activeForm === 'income') && array_key_exists('incomeComment', $errors)) : ?>
                        <div>
                            <p class="error-text"><?php echo e($errors['incomeComment'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex-conteiner date-category-box">
                    <div class="input-form-box flex-conteiner">
                        <label for="date">Date</label>
                        <input id="date" type="date" name="incomeDate"
                            value="<?php echo e($oldFormData['incomeDate'] ?? ''); ?>" />
                        <?php if (($activeForm === 'income') && array_key_exists('incomeDate', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['incomeDate'][0]); ?></p>
                            </div>
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
                        <?php if (($activeForm === 'income') && array_key_exists('incomeCategory', $errors)) : ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['incomeCategory'][0]); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <button id="income-submit" type="submit" class="btn btn--modal">Save</button>

            </form>

        </div>
    </div>
</section>