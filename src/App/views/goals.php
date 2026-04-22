<?php
include $this->resolve("partials/_header.php");
?>

<body class="<?php echo ($activeForm === 'income') ? 'modal-income-open' : '';
                echo ($activeForm === 'expense') ? 'modal-expense-open' : '';
                echo ($activeForm === 'addIncomeCategory') ? 'modal-add-income-category-open modal-income-open' : '';
                echo ($activeForm === 'addExpenseCategory') ? 'modal-add-expense-category-open modal-expense-open' : '';
                echo ($activeForm === 'addGoal') ? 'modal-add-goal-open' : '';
                echo ($activeForm === 'editGoal') ? 'modal-edit-goal-open' : '';
                echo ($activeForm === 'addContribution') ? 'modal-add-contribution-open' : '';
                echo ($activeForm === 'editContribution') ? 'modal-edit-contribution-open' : ''; ?>">
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
        <section class="section-balance flex-conteiner mrg-bottom ">
            <div class="balance-box flex-conteiner">
                <p>Your savings: <?php echo e($balance); ?></p>
                <?php if ($balance > 0): ?>
                    <ion-icon name="thumbs-up-outline"></ion-icon>
                <?php else: ?>
                    <ion-icon name="thumbs-down-outline"></ion-icon>
                <?php endif; ?>
            </div>

        </section>

        <section class="goals-section">
            <div class="heading-tertiary">
                <p>Your Current Goals</p>
            </div>

            <!-- GRID -->
            <div class="goal-cards-box grid-cols-3">
                <?php foreach ($goals as $goal): ?>
                    <div class="goal-card"
                        data-id="<?php echo e($goal['id']); ?>"
                        data-name="<?php echo e($goal['goal_name']); ?>"
                        data-description="<?php echo e($goal['goal_description']); ?>"
                        data-saved="<?php echo e($goal['amount_saved']); ?>"
                        data-target="<?php echo e($goal['amount_needed']); ?>"
                        data-remaind="<?php echo max(0, $goal['amount_remaind']); ?>"
                        data-deadline="<?php echo e($goal['deadline']); ?>"
                        data-progress="<?php echo e($goal['progress']); ?>"
                        data-achieved="<?php echo $goal['amount_saved'] >= $goal['amount_needed'] ? '1' : '0' ?>">

                        <div class="goal-card-header">
                            <h3 class="goal-title"><?php echo e($goal['goal_name']); ?></h3>

                            <div class="goal-menu">
                                <button class="menu-trigger">⋯</button>

                                <div class="menu-dropdown">
                                    <button class="btn-box btn--edit"
                                        data-id="<?php echo e($goal['id']); ?>"
                                        data-goal-name="<?php echo e($goal['goal_name']); ?>"
                                        data-goal-description="<?php echo e($goal['goal_description']); ?>"
                                        data-goal-amount="<?php echo number_format($goal['amount_needed'], 2, '.', ''); ?>"
                                        data-deadline="<?php echo e(date('Y-m-d', strtotime($goal['deadline']))); ?>">
                                        <ion-icon class="edit--icon " name="create-outline"></ion-icon>
                                        <span>Edit</span>
                                    </button>

                                    <form action="/goals/<?php echo e($goal['id']); ?>" method="POST">
                                        <input type="hidden" name="_METHOD" value="DELETE">
                                        <?php include $this->resolve("partials/_csrf.php"); ?>
                                        <button onclick="return confirm('Remove this goal?')" class="btn-box">
                                            <ion-icon class="delete--icon" name="trash-outline"></ion-icon>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="flex-conteiner gap-1 progress-box">
                            <div class="progress-bar">
                                <div class="progress-fill"
                                    style="width: <?php echo min(100, $goal['progress']); ?>%">
                                </div>
                            </div>
                            <p class="progress-text">
                                <?php echo number_format($goal['progress'], 1); ?>%
                            </p>
                        </div>
                        <div class="grid-rows-2 goal-data-box">

                            <p class="amount">
                                <ion-icon name="ribbon-outline"></ion-icon>
                                <?php echo e($goal['amount_saved']); ?> /
                                <?php echo e($goal['amount_needed']); ?> zł
                            </p>

                            <p class="deadline">
                                <ion-icon name="hourglass-outline"></ion-icon>
                                <?php echo e($goal['deadline']); ?>
                            </p>
                        </div>
                        <!-- CONTRIBUTION BUTTON -->
                        <?php if ($goal['amount_saved'] < $goal['amount_needed']):  ?>
                            <button class="btn-primary  btn--contribution"
                                data-goal-id="<?php echo e($goal['id']); ?>"
                                data-goal-name="<?php echo e($goal['goal_name']); ?>"
                                <?php echo $balance <= 0 ? 'disabled' : ''; ?>>
                                <i class="contribution--icon ph-fill ph-hand-coins"></i>
                                <i class="contribution--icon ph ph-empty lack-of-funds--icon" style="display: none;"></i>
                                <span class="btn-contribution-span">Add contribution</span>
                                <span class="btn-contribution-span-lack" style="display: none;">Lack of funds</span>
                            </button>
                        <?php else: ?>
                            <div class="flex-conteiner goal-achieved-box gap-1">
                                <ion-icon name="trophy-outline" class="achieved--icon"></ion-icon>
                                <span>Goal achieved!</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>


        <!-- SIDE PANEL -->
        <section class=" overlay">
        </section>

        <section class="side-panel">
            <div id="panel-goal-achieved-box" class="flex-conteiner">
                <ion-icon name="trophy-outline" class="achieved--icon"></ion-icon>
                <span>Goal achieved!</span>
            </div>

            <div class="flex-conteiner panel-header">
                <h2 class="goal-name"></h2>
                <button class="btn btn-box close-btn">✖</button>
            </div>


            <div class="panel-content">

                <div class="panel-section">
                    <p class="goal-description"></p>
                </div>

                <div class="panel-section stats">
                    <div class="flex-conteiner stats-box">
                        <ion-icon name="ribbon-outline"></ion-icon>
                        <p>Saved:</p>
                        <span class="goal-saved"></span>
                    </div>
                    <div class="flex-conteiner stats-box">
                        <ion-icon name="golf-outline"></ion-icon>
                        <p>Target:</p>
                        <span class="goal-target stats-box"></span>
                    </div>
                    <div class="flex-conteiner stats-box">
                        <ion-icon name="color-fill-outline"></ion-icon>
                        <p>Remaind:</p>
                        <span class="goal-remaind"></span>
                    </div>
                    <div class="flex-conteiner stats-box">
                        <ion-icon name="hourglass-outline"></ion-icon>
                        <p>Deadline:</p>
                        <span class="goal-deadline"></span>
                    </div>
                </div>

                <div class="panel-section">
                    <div class="progress-bar">
                        <div class="progress-fill panel-progress"></div>
                    </div>
                    <p class="number-progress"></p>
                </div>

                <div class="panel-actions">
                    <button class="btn-primary btn-panel-contribution">
                        <i class="contribution--icon ph-fill ph-hand-coins"></i>
                        <span>Add contribution</span>
                    </button>
                </div>

                <!-- CONTRIBUTIONS -->
                <div class=" panel-section">
                    <h4>Recent contributions</h4>
                    <template id="contribution-template">
                        <li class="contribution-item">
                            <i class="ph ph-hand-coins"></i>
                            <span class="contribution-text"></span>
                        </li>
                    </template>


                    <ul class="goal-contributions">

                    </ul>
                </div>

            </div>
        </section>


        <section class="contribution-table-box">
            <div class="table-heading flex-conteiner">
                <p>Your previous contributions</p>
            </div>

            <table class="table" id="contributionsTableBody">
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
                    <?php $i = $offset + 1;
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

        <!-- pagination for the table -->
        <div class="pagination--box" id="contributionsTablePagination">
            <!-- PREV -->
            <button
                onclick="loadPage(<?= $currentPage - 1 ?>)"
                class="btn--page"
                <?= $currentPage <= 1 ? 'disabled' : '' ?>>
                &lt;
            </button>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <button onclick="loadPage(<?php echo $i; ?>)" class="<?php echo $i === $currentPage ? 'active' : ''; ?> btn--page">
                    <?php echo $i; ?>
                </button>
            <?php endfor; ?>

            <!-- NEXT -->
            <button
                onclick="loadPage(<?= $currentPage + 1 ?>)"
                class="btn--page"
                <?= $currentPage >= $totalPages ? 'disabled' : '' ?>>
                &gt;
            </button>
        </div>

    </main>
    <?php
    include $this->resolve("partials/_sideNavAndModals.php");
    include $this->resolve("partials/modals/_addGoalModal.php");
    include $this->resolve("partials/modals/_editGoalModal.php");
    include $this->resolve("partials/modals/_addContributionModal.php");
    include $this->resolve("partials/modals/_editContributionModal.php");
    include $this->resolve("partials/modals/_addIncomeCategoryModal.php");
    include $this->resolve("partials/modals/_addExpenseCategoryModal.php");
    include $this->resolve("partials/_scripts.php");

    ?>
</body>

</html>