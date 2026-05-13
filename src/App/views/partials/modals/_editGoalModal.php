<section id="modal-edit-goal" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-container">
            <span id="close-edit-goal-modal" class="close">&times;</span>
            <ion-icon class="header-icon" name="create-outline"></ion-icon>
            <p>Edit Your Goal</p>
        </div>

        <div class="modal-body flex-container">

            <!-- FORM -->
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/goals/update" class="modal-form-box flex-container">

                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <input type="hidden" name="goalId" value="<?php echo e($goalToEdit['id'] ?? ''); ?>">
                <input type="hidden" name="form_type" value="editGoal">

                <div class="flex-container">
                    <div class="grid-rows-2-gap">

                        <!-- GOAL NAME -->
                        <div class="input-form-box flex-container">
                            <label for="edit-goal-name">Goal Name</label>
                            <input
                                id="edit-goal-name"
                                type="text"
                                name="goalName"
                                value="" />
                            <ion-icon
                                id="cash-icon"
                                class="modal-icon"
                                name="heart-half-outline"></ion-icon>
                            <?php if ($activeForm === 'editGoal' && array_key_exists('goalName', $errors)) : ?>
                                <div class="editGoalFormError">
                                    <p class="error-text"><?php echo e($errors['goalName'][0]); ?></p>
                                    <ion-icon class="error-icon" name="alert"></ion-icon>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- GOAL DESCRIPTION -->
                        <div class="input-form-box flex-container">
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
                            <?php if ($activeForm === 'editGoal' && array_key_exists('goalDescription', $errors)) : ?>
                                <div class="editGoalFormError">
                                    <p class="error-text"><?php echo e($errors['goalDescription'][0]); ?></p>
                                    <ion-icon class="error-icon" name="alert"></ion-icon>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="grid-rows-2-gap">

                        <!-- AMOUNT NEEDED-->
                        <div class="input-form-box flex-container">
                            <label for="goal-amount">Amount needed</label>
                            <input
                                id="edit-goal-amount"
                                type="number"
                                step="0.01"
                                min="0"
                                name="goalAmount"
                                placeholder="0"
                                value="" />
                            <ion-icon
                                id="cash-icon"
                                class="modal-icon"
                                name="cash-outline"></ion-icon>
                            <?php if ($activeForm === 'editGoal' && array_key_exists('goalAmount', $errors)) : ?>
                                <div class="editGoalFormError">
                                    <p class="error-text"><?php echo e($errors['goalAmount'][0]); ?></p>
                                    <ion-icon class="error-icon" name="alert"></ion-icon>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- DEADLINE -->
                        <div class="input-form-box flex-container">
                            <label for="date">Deadline</label>
                            <input id="edit-goal-date" type="date" name="goalDate" required
                                value="" />
                            <ion-icon id="date-icon" class="modal-icon" name="calendar-outline"></ion-icon>
                            <?php if ($activeForm === 'editGoal' && array_key_exists('goalDate', $errors)) : ?>
                                <div class="editGoalFormError">
                                    <p class="error-text"><?php echo e($errors['goalDate'][0]); ?></p>
                                    <ion-icon class="error-icon" name="alert"></ion-icon>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <button id="goal-submit" type="submit" class="btn btn--modal">Save</button>
            </form>
        </div>
    </div>
</section>