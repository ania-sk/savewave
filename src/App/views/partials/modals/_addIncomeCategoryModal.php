<section id="modal-add-income-category" class="modal-box">
    <div id="income-add-category" class="modal-content">
        <div class="modal-header flex-container">
            <!-- Zamknięcie modala – przekierowanie do głównego formularza -->
            <span class="close-add-category">&times;</span>
            <ion-icon class="header-icon" name="add-circle"></ion-icon>
            <p>Add new category</p>
        </div>
        <div class="modal-body flex-container">

            <?php $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI']); ?>

            <!-- FORM -->
            <form id="form-add-income-category" method="post" action="/api/addNewIncomeCategory" class="modal-form-box flex-container">

                <?php include $this->resolve("partials/_csrf.php"); ?>

                <div class="input-form-box flex-container">
                    <label for="newCategoryName">New category</label>
                    <input type="text" id="newCategoryName" name="newCategoryName" value="<?php echo e($oldFormData['newCategoryName'] ?? ''); ?>" placeholder="Insert new category name">
                    <ion-icon
                        id="bulb-icon"
                        class="modal-icon"
                        name="bulb-outline"></ion-icon>
                </div>

                <button id="income-submit" type="submit" class="btn btn--modal">Save</button>
            </form>
        </div>
    </div>

</section>