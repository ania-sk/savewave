<?php
include $this->resolve("partials/_header.php");
include $this->resolve("partials/_sideNavAndModals.php");
?>

<body class="">
    <!-- MAIN SECTION -->
    <!-- TABLE INCOME -->
    <main class="section-main flex-conteiner">
        <div class="incomes-heading flex-conteiner">
            <ion-icon class="nav-icon" name="cash-outline"></ion-icon>
            <p>Incomes</p>

            <button class="header-btn--icon">
                <ion-icon
                    id="header-icon-btn-modal-income"
                    class="header-icon-modal"
                    name="add-circle"></ion-icon>
            </button>
        </div>
        <div class="table-income-box">
            <table class="table table-income">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Category</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="No.">1.</td>
                        <td data-label="Amount">3200</td>
                        <td data-label="Category">Salary</td>
                        <td data-label="Comment"></td>
                        <td data-label="Date">10-10-2024</td>
                    </tr>

                    <tr>
                        <td data-label="No.">2.</td>
                        <td data-label="Amount">200</td>
                        <td data-label="Category">Sale</td>
                        <td data-label="Comment">Armchair on olx</td>
                        <td data-label="Date">12-10-2024</td>
                    </tr>

                    <tr>
                        <td data-label="No.">3.</td>
                        <td data-label="Amount">25</td>
                        <td data-label="Category">Sale</td>
                        <td data-label="Comment">Book on olx</td>
                        <td data-label="Date">15-10-2024</td>
                    </tr>

                    <tr>
                        <td data-label="No.">4.</td>
                        <td data-label="Amount">200</td>
                        <td data-label="Category">Sale</td>
                        <td data-label="Comment">Armchair on olx</td>
                        <td data-label="Date">12-10-2024</td>
                    </tr>

                    <tr>
                        <td data-label="No.">5.</td>
                        <td data-label="Amount">25</td>
                        <td data-label="Category">Sale</td>
                        <td data-label="Comment">Book on olx</td>
                        <td data-label="Date">15-10-2024</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
    <?php
    include $this->resolve("partials/_scripts.php");
    ?>

</body>

</html>