<section id="modal-goal" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-container">
            <span id="close-goal-modal" class="close">&times;</span>
            <ion-icon class="header-icon" name="add-circle"></ion-icon>
            <p>Add new goal</p>
        </div>

        <div class="modal-body flex-container">

            <!-- FORM -->
            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>
            <form method="post" action="/goals/addGoal" class="modal-form-box flex-container">

                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">
                <input type="hidden" name="form_type" value="addGoal">
                <div class="flex-container">
                    <div class="grid-rows-2-gap">
                        <div class="input-form-box flex-container">
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
                            <?php if ($activeForm === 'addGoal' && array_key_exists('goalName', $errors)) : ?>
                                <div class="addGoalFormError">
                                    <p class="error-text"><?php echo e($errors['goalName'][0]); ?></p>
                                    <ion-icon class="error-icon" name="alert"></ion-icon>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="input-form-box flex-container">
                            <label for="goal-description">Description</label>
                            <input
                                type="text"
                                name="goalDescription"
                                id="goal-description"
                                placeholder="About goal..."
                                value="<?php echo e($oldFormData['goalDescription'] ?? ''); ?>" />
                            <ion-icon
                                id="text-icon"
                                class="modal-icon"
                                name="document-text-outline"></ion-icon>
                            <?php if ($activeForm === 'addGoal' && array_key_exists('goalDescription', $errors)) : ?>
                                <div class="addGoalFormError">
                                    <p class="error-text"><?php echo e($errors['goalDescription'][0]); ?></p>
                                    <ion-icon class="error-icon" name="alert"></ion-icon>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="grid-rows-2-gap">
                        <div class="input-form-box flex-container">
                            <label for="goal-amount">Amount needed</label>
                            <input
                                id="goal-amount"
                                type="number"
                                step="0.01"
                                min="0"
                                name="goalAmount"
                                placeholder="0"
                                value="<?php echo e(($oldFormData['goalAmount'] ?? '')); ?>" />
                            <ion-icon
                                id="cash-icon"
                                class="modal-icon"
                                name="cash-outline"></ion-icon>
                            <?php if ($activeForm === 'addGoal' && array_key_exists('goalAmount', $errors)) : ?>
                                <div class="addGoalFormError">
                                    <p class="error-text"><?php echo e($errors['goalAmount'][0]); ?></p>
                                    <ion-icon class="error-icon" name="alert"></ion-icon>
                                </div>
                            <?php endif; ?>

                        </div>
                        <div class="input-form-box flex-container">
                            <label for="date">Deadline</label>
                            <input id="goal-date" type="date" name="goalDate"
                                value="<?php echo e($oldFormData['goalDate'] ?? date('Y-m-d')); ?>" />
                            <ion-icon id="date-icon" class="modal-icon" name="calendar-outline"></ion-icon>
                            <?php if ($activeForm === 'addGoal' && array_key_exists('goalDate', $errors)) : ?>
                                <div class="addGoalFormError">
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
</section>