<section id="modal-add-contribution" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span id="close-contribution-modal" class="close">&times;</span>
            <i class="contribution--icon ph ph-hand-coins"></i>
            <p>Contribution for <span id="contribution-goal-name"></span></p>
        </div>

        <div class="modal-body flex-conteiner">

            <!-- FORM -->
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/contributions/store" class="modal-form-box flex-conteiner">

                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <input type="hidden" name="goalId" id="contribution-goal-id">
                <input type="hidden" name="form_type" value="addContribution">

                <div class="input-form-box flex-conteiner">
                    <label>Amount</label>
                    <input type="number" step="0.01" name="contributionAmount" placeholder="0">
                    <ion-icon
                        id="cash-icon"
                        class="modal-icon"
                        name="cash-outline"></ion-icon>
                    <?php if ($activeForm === 'addContribution' && array_key_exists('contributionAmount', $errors)) : ?>
                        <div class="addContributionFormError">
                            <p class="error-text"><?php echo e($errors['contributionAmount'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>

                <button id="goal-submit" type="submit" class="btn btn--modal">Save</button>
            </form>
        </div>
</section>