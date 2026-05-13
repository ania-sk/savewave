<section id="modal-edit-contribution" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-container">
            <span id="close-edit-contribution-modal" class="close">&times;</span>
            <i class="contribution--icon ph ph-hand-coins"></i>
            <p>Edit contribution for <span id="edit-contribution-goal-name"><?= e($oldFormData['contributionGoalName'] ?? '') ?></span></p>
        </div>

        <div class="modal-body flex-container">

            <!-- FORM -->
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/contributions/update" class="modal-form-box flex-container">

                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <input type="hidden" name="contributionId" value="<?php echo e($contributionToEdit['id'] ?? ''); ?>" id="edit-contribution-id">
                <input type="hidden" name="form_type" value="editContribution">
                <input type="hidden" name="contributionGoalName" id="edit-contribution-goal-name-input">
                <input type="hidden" name="oldContributionAmount" id="old-contribution-amount">

                <div class="input-form-box flex-container">
                    <label>Amount</label>
                    <input id="edit-contribution-amount" type="number" step="0.01" name="contributionAmount" placeholder="0" value="">
                    <ion-icon
                        id="cash-icon"
                        class="modal-icon"
                        name="cash-outline"></ion-icon>
                    <?php if ($activeForm === 'editContribution' && array_key_exists('contributionAmount', $errors)) : ?>
                        <div class="editContributionFormError">
                            <p class="error-text"><?php echo e($errors['contributionAmount'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <button id="goal-submit" type="submit" class="btn btn--modal">Save</button>
            </form>
        </div>
</section>