<?php
include $this->resolve("partials/_header.php");
?>

<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : '';
                echo ($activeForm === 'addIncomeCategory') ? 'modal-add-income-category-open modal-income-open' : '';
                echo ($activeForm === 'addExpenseCategory') ? 'modal-add-expense-category-open modal-expense-open' : ''; ?>">
    <!-- MAIN SECTION -->
    <main class="section-main flex-conteiner">
        <div class="heading-box grid-cols-2">
            <div class="goals-heading flex-conteiner"><ion-icon class="nav-icon" name="heart-half-outline"></ion-icon>
                <p>Goals</p>
            </div>
            <!--goal modal button -->
            <div class="btn-box">
                <button id="btn-modal-goal" class="btn btn--goal flex-conteiner">
                    <ion-icon
                        id="add-goal-icon"
                        name="add-circle"></ion-icon>
                    <p>Add goal</p>
                </button>
            </div>
        </div>
        <!-- BALANCE -->
        <section class="section-balance flex-conteiner">
            <div class="balance-box flex-conteiner">
                <p>Your savings: <?php echo e($balance); ?></p>
                <?php if ($balance > 0): ?>
                    <ion-icon name="thumbs-up-outline"></ion-icon>
                <?php else: ?>
                    <ion-icon name="thumbs-down-outline"></ion-icon>
                <?php endif; ?>
            </div>

        </section>

        <!-- GOALS TABLE -->
        <section class="goals-table-box">
            <div class="table-heading flex-conteiner">
                <p>Your Current Goals</p>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Goal</th>
                        <th scope="col">Description</th>
                        <th scope="col">Amount<br>needed</th>
                        <th scope="col">Amount<br>saved</th>
                        <th scope="col">Progress</th>
                        <th scope="col">Deadline</th>
                        <th scope="col">Contribution</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($goals as $goal): ?>
                        <tr>
                            <td data-label="No."><?php echo e($i++); ?>.</td>
                            <td data-label="Goal"><?php echo e($goal['goal_name']); ?></td>
                            <td data-label="Description"><?php echo e($goal['goal_description']); ?></td>
                            <td data-label="Amount needed"><?php echo e($goal['amount_needed']); ?></td>
                            <td data-label="Amount saved"><?php echo e($goal['amount_saved']); ?></td>
                            <td data-label="Progress">
                                <p class="progress-text flex-conteiner"><?php echo e(number_format($goal['progress']), 1); ?>%</p>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo min(100, e($goal['progress'])); ?>%"></div>
                                </div>

                            </td>
                            <td data-label="Deadline"><?php echo e($goal['deadline']); ?></td>
                            <td data-label="Contribution"><button class="btn-box btn--contribution" data-goal-id="<?php echo e($goal['id']); ?>"
                                    data-goal-name="<?php echo e($goal['goal_name']); ?>"
                                    <?php echo $balance <= 0 ? 'disabled' : ''; ?>>
                                    <!-- <ion-icon class="contribution--icon" name="color-fill"></ion-icon> -->
                                    <i class="contribution--icon ph-fill ph-hand-coins"></i>
                                </button></td>
                            <td data-label="Edit"> <button class="btn-box btn--edit"
                                    data-id="<?php echo e($goal['id']); ?>"
                                    data-goal-name="<?php echo e($goal['goal_name']); ?>"
                                    data-goal-description="<?php echo e($goal['goal_description']); ?>"
                                    data-goal-amount="<?php echo number_format($goal['amount_needed'], 2, '.', ''); ?>"
                                    data-deadline="<?php echo e(date('Y-m-d', strtotime($goal['deadline']))); ?>">
                                    <ion-icon class="edit--icon " name="create-outline"></ion-icon>
                                </button></td>

                            <td data-label="Delete">
                                <form action="/goals/<?php echo e($goal['id']); ?>" method="POST">
                                    <input type="hidden" name="_METHOD" value="DELETE">
                                    <?php include $this->resolve("partials/_csrf.php"); ?>
                                    <button onclick="return confirm('Remove this goal?')" class="btn-box"><ion-icon class="delete--icon" name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="contribution-table-box">
            <div class="table-heading flex-conteiner">
                <p>Your previous contributions</p>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Goal Name</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Date</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($contributions as $c): ?>
                        <tr>
                            <td data-label="No."><?php echo $i++; ?>.</td>
                            <td data-label="Goal Name"><?php echo e($c['goal_name']); ?></td>
                            <td data-label="Amount"><?php echo e($c['amount']); ?></td>
                            <td data-label="Date"><?php echo e($c['formatted_date']); ?></td>
                            <td data-label="Edit"><button class="btn-box btn--edit-contribution"
                                    data-contribution-id="<?php echo e($c['id']); ?>"
                                    data-contribution-amount="<?php echo e($c['amount']); ?>"
                                    data-contribution-goal-name="<?php echo e($c['goal_name']); ?>">
                                    <ion-icon class="edit--icon " name="create-outline"></ion-icon>
                                </button>
                            </td>
                            <td data-label="Delete">
                                <form action="/contributions/<?php echo e($c['id']); ?>" method="POST">
                                    <input type="hidden" name="_METHOD" value="DELETE">
                                    <?php include $this->resolve("partials/_csrf.php"); ?>
                                    <button onclick="return confirm('Remove this contribution?')" class="btn-box"><ion-icon class="delete--icon" name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </main>
    <?php
    include $this->resolve("partials/_sideNavAndModals.php");
    include $this->resolve("partials/modals/_addGoalModal.php");
    include $this->resolve("partials/modals/_editGoalModal.php");
    include $this->resolve("partials/modals/_addContributionModal.php");
    include $this->resolve("partials/modals/_editContributionModal.php");
    include $this->resolve("partials/_scripts.php");

    ?>
</body>

</html>