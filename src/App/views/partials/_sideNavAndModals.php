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
            href="incomes.php"
            class="link nav-box flex-conteiner"
            data-tooltip="Incomes">
            <ion-icon class="nav-icon" name="cash-outline"></ion-icon>
            <span>Incomes</span>
        </a>
        <a href="expenses.php" class="link nav-box flex-conteiner">
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
        <a href="main.php" class="link nav-box flex-conteiner">
            <ion-icon class="nav-icon" name="log-out-outline"></ion-icon>
            <span>Logout</span>
        </a>
    </nav>
    <!-- MODALS -->

    <!-- INCOME -->
    <section id="modal-income" class="modal-box">
        <div class="modal-content">
            <div class="modal-header flex-conteiner">
                <span class="close">&times;</span>
                <ion-icon class="header-icon" name="add-circle"></ion-icon>
                <p>Add income</p>
            </div>
            <div class="modal-body flex-conteiner">
                <form action="" class="modal-form-box flex-conteiner">
                    <div class="input-form-box flex-conteiner">
                        <label for="amount">Amount</label>
                        <input
                            id="amount"
                            type="number"
                            name="amount"
                            value="0"
                            required />
                        <ion-icon
                            id="cash-icon"
                            class="modal-icon"
                            name="cash-outline"></ion-icon>
                    </div>

                    <div class="input-form-box flex-conteiner">
                        <label for="comment">Comment</label>
                        <input
                            type="text"
                            name="comment"
                            id="comment"
                            placeholder="About income..." />
                        <ion-icon
                            id="text-icon"
                            class="modal-icon"
                            name="document-text-outline"></ion-icon>
                    </div>

                    <div class="flex-conteiner date-category-box">
                        <div class="input-form-box flex-conteiner">
                            <label for="date">Date</label>
                            <input id="date" type="date" name="date" required />
                        </div>

                        <div class="input-form-box flex-conteiner">
                            <label for="category">Category</label>
                            <select name="category" id="category">
                                <option value="">Choose category:</option>

                                <option value="salary">Salary</option>
                                <option value="sale">Sale</option>
                                <option value="repayment">Debt repayment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn--modal">Save</button>
                </form>
            </div>
        </div>
    </section>

    <!-- EXPENSE -->
    <section id="modal-expense" class="modal-box">
        <div class="modal-content">
            <div class="modal-header flex-conteiner">
                <span class="close">&times;</span>
                <ion-icon class="header-icon" name="remove-circle"></ion-icon>
                <p>Add expense</p>
            </div>
            <div class="modal-body flex-conteiner">
                <form action="" class="modal-form-box flex-conteiner">
                    <div class="input-form-box flex-conteiner">
                        <label for="amount">Amount</label>
                        <input
                            id="amount"
                            type="number"
                            name="amount"
                            value="0"
                            required />
                        <ion-icon
                            id="cash-icon"
                            class="modal-icon"
                            name="cash-outline"></ion-icon>
                    </div>

                    <div class="input-form-box flex-conteiner">
                        <label for="comment">Comment</label>
                        <input
                            type="text"
                            name="comment"
                            id="comment"
                            placeholder="About expense..." />
                        <ion-icon
                            id="text-icon"
                            class="modal-icon"
                            name="document-text-outline"></ion-icon>
                    </div>

                    <div class="flex-conteiner date-category-box">
                        <div class="input-form-box flex-conteiner">
                            <label for="date">Date</label>
                            <input id="date" type="date" name="date" required />
                        </div>

                        <div class="input-form-box flex-conteiner">
                            <label for="category">Category</label>
                            <select name="category" id="category">
                                <option value="">Choose category:</option>

                                <option value="salary">Bills</option>
                                <option value="sale">Shopping</option>
                                <option value="repayment">Entertainment</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn--modal">Save</button>
                </form>
            </div>
        </div>
    </section>
    <!-- END MODALS -->
</section>