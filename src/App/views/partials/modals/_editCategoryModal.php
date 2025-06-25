<section id="modal-edit-category" class="modal-box">
    <div class="modal-content">
        <div class="modal-header flex-conteiner">
            <span id="close-edit-category-modal" class="close">&times;</span>
            <ion-icon class="header-icon" name="create-outline"></ion-icon>
            <p>Edit Category Name</p>
        </div>

        <div class="modal-body flex-conteiner">
            <?php $currentUrl = e($_SERVER['REQUEST_URI']); ?>

            <!-- FORM -->
            <form method="post" action="/settings/<?php echo e($categoryToEdit['id']); ?>" class="modal-form-box flex-conteiner">

                <?php include $this->resolve("partials/_csrf.php"); ?>

                <input type="hidden" name="redirect_to" value="<?= $currentUrl; ?>">

                <input type="hidden" name="form_type" value="editCategory">
                <input type="hidden" name="categoryId" value="<?php echo e($categoryToEdit['id'] ?? ''); ?>">
                <input type="hidden" name="categoryType" value="<?php echo e($categoryToEdit['type'] ?? '');                                                                         ?>">

                <div class="input-form-box flex-conteiner">
                    <label for="edit-category-name">Category Name</label>
                    <input id="edit-category-name" type="text" name="newCategoryName"
                        value="<?php echo e($oldFormData['newCategoryName'] ?? ($categoryToEdit['name'] ?? '')); ?>"
                        placeholder="Enter new category name" />
                    <ion-icon id="cash-icon" class="modal-icon" name="medal-outline"></ion-icon>

                    <?php if ($activeForm === 'editCategory' && isset($errors['newCategoryName'])): ?>
                        <div>
                            <p class="error-text"><?php echo e($errors['newCategoryName'][0]); ?></p>
                            <ion-icon class="error-icon" name="alert"></ion-icon>
                        </div>
                    <?php endif; ?>
                </div>
                <button id="edit-category-submit" type="submit" class="btn btn--modal">Save Changes</button>
            </form>
        </div>
    </div>
</section>