<section id="modal-goal" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span class="close">&times;</span>
            <ion-icon class="header-icon" name="add-circle"></ion-icon>
            <p>Add new goal</p>
        </div>

        <div class="modal-body flex-conteiner">

            <!-- FORM -->
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/" class="modal-form-box flex-conteiner">

                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <!-- <input type="hidden" name="form_type" value="income"> -->

                <div class="input-form-box flex-conteiner">
                    <label for="goal-name">Goal Name</label>
                    <input
                        id="goal-name"
                        type="text"
                        name="goalName"
                        placeholder="Your goal"
                        value="<?php echo e(($oldFormData['goalName'] ?? '')); ?>" />
                    <ion-icon
                        id="cash-icon"
                        class="modal-icon"
                        name="heart-half-outline"></ion-icon>
                </div>

                <div class="input-form-box flex-conteiner">
                    <label for="amount">Amount needed</label>
                    <input
                        id="amount"
                        type="number"
                        step="0.01"
                        min="0"
                        name="goalAmount"
                        placeholder="0"
                        value="<?php echo e((float)($oldFormData['goalAmount'] ?? '')); ?>" />
                    <ion-icon
                        id="cash-icon"
                        class="modal-icon"
                        name="cash-outline"></ion-icon>

                </div>

                <div class="input-form-box flex-conteiner">
                    <label for="description">Description</label>
                    <input
                        type="text"
                        name="goalDescriptiont"
                        id="comment"
                        placeholder="About goal..."
                        value="<?php echo e($oldFormData['goalDescription'] ?? ''); ?>" />
                    <ion-icon
                        id="text-icon"
                        class="modal-icon"
                        name="document-text-outline"></ion-icon>
                </div>
                <div class="input-form-box flex-conteiner">
                    <label for="date">Deadline</label>
                    <input id="goal-date" type="date" name="goalDate"
                        value="<?php echo e($oldFormData['goalDate'] ?? date('Y-m-d')); ?>" />


                </div>
                <button id="goal-submit" type="submit" class="btn btn--modal">Save</button>
            </form>
        </div>
</section>