<section id="modal-add-limit" class="modal-box">
    <div class="modal-content">
        <div class="modal-content">
            <div class="modal-header flex-conteiner">
                <span id="close-add-limit-modal" class="close">&times;</span>
                <ion-icon class="header-icon" name="hand-right-outline"></ion-icon>
                <p>Add Monthly Limit</p>
            </div>

            <div class="modal-body flex-conteiner">
                <?php $currentUrl = e($_SERVER['REQUEST_URI']); ?>

                <!-- FORM -->
                <form method="post" action="" class="modal-form-box flex-conteiner">

                    <?php include $this->resolve("partials/_csrf.php"); ?>

                    <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                    <input type="hidden" name="categoryType" value="<?php echo e($categoryToEdit['type'] ?? ''); ?>">

                    <div class="input-form-box flex-conteiner">
                        <label for="monthly-limit">Monthly Limit</label>
                        <input id="monthly-limit" type="number" step="1" min="0" name="monthly_limit"
                            value="<?php echo e($oldFormData['monthly_limit'] ?? ($categoryToEdit['monthly_limit'] ?? '')); ?>"
                            placeholder="Enter monthly limit" />
                        <ion-icon id="hand-icon" class="modal-icon" name="hand-right-outline"></ion-icon>

                        <?php if ($activeForm === 'addLimit' && isset($errors['monthly_limit'])): ?>
                            <div>
                                <p class="error-text"><?php echo e($errors['monthly_limit'][0]); ?></p>
                                <ion-icon class="error-icon" name="alert"></ion-icon>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button id="add-limit-btn" type="submit" class="btn btn--modal">Save Limit</button>
                </form>
            </div>
        </div>
</section>