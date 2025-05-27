<?php
$incomeError = false;
$expenseError = false;
?>
<section class="section-side-nav">
    <div class="logo-box mrg-bottom">
        <a class="link flex-conteiner" href="mainPage">
            <img
                class="logo-sw"
                src="/assets/imgs/save-wave-circle.png"
                alt="Save Wave logo" />
            <span class="header-save">Save Wave</span>
        </a>
    </div>
    <div class="arrow-toggle">
        <div class="side-nav-button-hide">
            <ion-icon class="hide-icon" name="chevron-back-outline"></ion-icon>
        </div>
        <div class="side-nav-button-show">
            <ion-icon class="hide-icon" name="chevron-forward-outline"></ion-icon>
        </div>
    </div>
    <!-- modal buttons -->
    <div class="btn-box flex-conteiner">
        <button id="btn-modal-income" class="btn btn--modal">
            <span>Add income</span>
        </button>
        <button id="btn-modal-expense" class="btn btn--modal">
            <span>Add expense</span>
        </button>
    </div>

    <div class="btn-box flex-conteiner">
        <button class="btn--icon">
            <ion-icon
                id="icon-btn-modal-income"
                class="header-icon"
                name="add-circle"></ion-icon>
        </button>
        <button class="btn--icon">
            <ion-icon
                id="icon-btn-modal-expense"
                class="header-icon"
                name="remove-circle"></ion-icon>
        </button>
    </div>

    <!-- sidenav -->
    <nav class="side-nav-box flex-conteiner">
        <a
            href="/incomes"
            class="link nav-box flex-conteiner"
            data-tooltip="Incomes">
            <ion-icon class="nav-icon" name="cash-outline"></ion-icon>
            <span>Incomes</span>
        </a>
        <a href="/expenses" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="file-tray-full-outline"></ion-icon>
            <span>Expenses</span>
        </a>
        <a href="#" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="stats-chart-outline"></ion-icon>
            <span>Balance</span>
        </a>
        <a href="#" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="heart-half-outline"></ion-icon>
            <span>Goals</span>
        </a>
        <a href="#" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="settings-outline"></ion-icon>
            <span>Settings</span>
        </a>
        <a href="/logout" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="log-out-outline"></ion-icon>
            <span>Logout</span>
        </a>
    </nav>
    <!-- MODALS -->

    <!-- INCOME -->
    <?php include $this->resolve("partials/modals/_incomeModal.php"); ?>

    <!-- EXPENSE -->
    <?php include $this->resolve("partials/modals/_expenseModal.php"); ?>
    <!-- END MODALS -->
</section>