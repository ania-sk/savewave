<section id="modal-add-contribution" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span id="close-contribution-modal" class="close">&times;</span>
            <ion-icon class="contribution--icon" name="color-fill-outline"></ion-icon>
            <p>Contribution for <span id="contribution-goal-name"></span></p>
        </div>

        <div class="modal-body flex-conteiner">

            <!-- FORM -->
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/contributions/store" class="modal-form-box flex-conteiner">

                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <input type="hidden" name="goalId" id="contribution-goal-id">
                <div class="input-form-box flex-conteiner">
                    <label>Amount</label>
                    <input type="number" step="0.01" name="amount" placeholder="0" required>
                    <ion-icon
                        id="cash-icon"
                        class="modal-icon"
                        name="cash-outline"></ion-icon>
                </div>

                <button id="goal-submit" type="submit" class="btn btn--modal">Save</button>
            </form>
        </div>
</section>