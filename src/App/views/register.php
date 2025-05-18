<?php
include $this->resolve("partials/_header.php");
?>

<body>
    <section class="section-singup flex-conteiner">
        <div class="logo-box">
            <a class="link flex-conteiner" href="main.php">
                <img
                    class="logo-sw"
                    src="/assets/imgs/save-wave-circle.png"
                    alt="Save Wave logo" />
                <span class="header-save">Save Wave</span>
            </a>
        </div>

        <form method="post" class="singup-form-box flex-conteiner">
            <div class="input-box flex-conteiner">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    placeholder="me@example.com"
                    name="email"
                    value="<?php if (isset($_SESSION['formEmailValue'])) {
                                echo $_SESSION['formEmailValue'];
                                unset($_SESSION['formEmailValue']);
                            } ?>" />
                <?php if (isset($_SESSION['emailInDatabase'])): ?>
                    <p class="session-error">
                        <?php echo $_SESSION['emailInDatabase']; ?>
                    </p>
                    <?php unset($_SESSION['emailInDatabase']); ?>
                <?php endif; ?>

                <ion-icon class="form-icon" name="mail"></ion-icon>
                <div class="error-box">
                    <p class="error-text">Looks like this is not an email</p>

                    <ion-icon class="error-icon" name="alert"></ion-icon>
                </div>
            </div>

            <div class="input-box flex-conteiner">
                <label for="username">Username</label>
                <input
                    id="username"
                    type="text"
                    placeholder="moneySaver"
                    name="username"
                    value="<?php if (isset($_SESSION['formUsernameValue'])) {
                                echo $_SESSION['formUsernameValue'];
                                unset($_SESSION['formUsernameValue']);
                            } ?>" />
                <ion-icon class="form-icon" name="person-add"></ion-icon>
                <div class="error-box">
                    <p class="error-text">Username cannot be empty or shorter then 4 characters</p>
                    <ion-icon class="error-icon" name="alert"></ion-icon>
                </div>
            </div>

            <div class="input-box flex-conteiner">
                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    placeholder="********"
                    name="password"
                    value="<?php if (isset($_SESSION['formPasswordValue'])) {
                                echo $_SESSION['formPasswordValue'];
                                unset($_SESSION['formPasswordValue']);
                            } ?>" />
                <ion-icon class="form-icon" name="lock-closed"></ion-icon>
                <div class="error-box">
                    <p class="error-text">Password cannot be empty or shorter then 6 characters </p>
                    <ion-icon class="error-icon" name="alert"></ion-icon>
                </div>
            </div>

            <div class="input-box flex-conteiner">
                <label for="confirm-password">Password confirmation</label>
                <input
                    id="confirm-password"
                    type="password"
                    placeholder="********"
                    name="confirm-password"
                    value="<?php if (isset($_SESSION['formConfirmPasswordValue'])) {
                                echo $_SESSION['formConfirmPasswordValue'];
                                unset($_SESSION['formConfirmPasswordValue']);
                            } ?>" />
                <ion-icon class="form-icon" name="lock-closed"></ion-icon>
                <div class="error-box">
                    <p class="error-text">Looks like this password is diffrent</p>
                    <ion-icon class="error-icon" name="alert"></ion-icon>
                </div>
                <button type="submit" class="btn btn--full btn--form">Sing-up</button>
            </div>
        </form>
        <div class="question-box flex-conteiner">
            <p>Alredy have an account?</p>
            <a class="link" href="/login">Login</a>
        </div>
    </section>

    <!--  -->

</body>

</html>