<section id="modal-edit-goal" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span id="close-edit-goal-modal" class="close">&times;</span>
            <ion-icon class="header-icon" name="create-outline"></ion-icon>
            <p>Edit Your Goal</p>
        </div>

        <div class="modal-body flex-conteiner">

            <!-- FORM -->
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/api/goals/<?php echo e($goalToEdit['id']); ?>" class="modal-form-box flex-conteiner">

                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <input type="hidden" name="goalId" value="<?php echo e($goalToEdit['id'] ?? ''); ?>">

                <div class="flex-conteiner">
                    <div class="grid-rows-2-gap">

                        <!-- GOAL NAME -->
                        <div class="input-form-box flex-conteiner">
                            <label for="edit-goal-name">Goal Name</label>
                            <input
                                id="edit-goal-name"
                                type="text"
                                name="goalName"
                                required
                                value="" />
                            <ion-icon
                                id="cash-icon"
                                class="modal-icon"
                                name="heart-half-outline"></ion-icon>
                        </div>

                        <!-- GOAL DESCRIPTION -->
                        <div class="input-form-box flex-conteiner">
                            <label for="edit-goal-description">Description</label>
                            <input
                                type="text"
                                name="goalDescription"
                                id="edit-goal-description"
                                placeholder="About goal..."
                                value="" />
                            <ion-icon
                                id="text-icon"
                                class="modal-icon"
                                name="document-text-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="grid-rows-2-gap">

                        <!-- AMOUNT NEEDED-->
                        <div class="input-form-box flex-conteiner">
                            <label for="goal-amount">Amount needed</label>
                            <input
                                id="edit-goal-amount"
                                type="number"
                                step="0.01"
                                min="0"
                                name="goalAmount"
                                placeholder="0"
                                required
                                value="" />
                            <ion-icon
                                id="cash-icon"
                                class="modal-icon"
                                name="cash-outline"></ion-icon>
                        </div>

                        <!-- DEADLINE -->
                        <div class="input-form-box flex-conteiner">
                            <label for="date">Deadline</label>
                            <input id="edit-goal-date" type="date" name="goalDate" required
                                value="" />
                            <ion-icon id="date-icon" class="modal-icon" name="calendar-outline"></ion-icon>
                        </div>
                    </div>
                </div>
                <button id="goal-submit" type="submit" class="btn btn--modal">Save</button>
            </form>
        </div>
    </div>
</section>