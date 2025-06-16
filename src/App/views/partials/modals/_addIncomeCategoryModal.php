<section id="modal-add-income-category" class="modal-box">
    <div id="income-add-category" class="modal-content">
        <div class="modal-header flex-conteiner">
            <!-- Zamknięcie modala – przekierowanie do głównego formularza -->
            <span class="close-add-category">&times;</span>
            <ion-icon class="header-icon" name="add-circle"></ion-icon>
            <p>Add new category</p>
        </div>
        <div class="modal-body flex-conteiner">

            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>

            <!-- FORM -->
            <form id="form-add-category" method="post" action="/mainPage/addIncomeCategory" class="modal-form-box flex-conteiner">

                <?php include $this->resolve("partials/_csrf.php"); ?>
                <!-- Przekierowanie po dodaniu kategorii na stronę z formularzem dochodu -->
                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">

                <div class="input-form-box flex-conteiner">
                    <label for="newCategoryName">New category</label>
                    <input type="text" id="newCategoryName" name="newCategoryName" value="<?php echo e($oldFormData['newCategoryName'] ?? ''); ?>" placeholder="Insert new category name">
                    <ion-icon
                        id="bulb-icon"
                        class="modal-icon"
                        name="bulb-outline"></ion-icon>
                </div>
                <?php if (($activeForm === 'addIncomeCategory') && array_key_exists('newCategoryName', $errors)) : ?>
                    <div>
                        <p class="error-text"><?php echo e($errors['newCategoryName'][0]); ?></p>
                        <ion-icon class="error-icon" name="alert"></ion-icon>
                    </div>
                <?php endif; ?>
                <button id="income-submit" type="submit" class="btn btn--modal">Save</button>
            </form>
        </div>
    </div>
</section>