<?php
include $this->resolve("partials/_header.php");
?>

<body>
    <section class="section-login flex-conteiner">
        <div class="logo-box">
            <a class="link flex-conteiner" href="main.php">
                <img
                    class="logo-sw"
                    src="/assets/imgs/save-wave-circle.png"
                    alt="Save Wave logo" />
                <span class="header-save">Save Wave</span>
            </a>
        </div>

        <form method="post" class="login-form-box flex-conteiner">
            <div class="input-box flex-conteiner">
                <label for="username">Email</label>
                <input
                    id="email"
                    type="email"
                    placeholder="me@example.com"
                    name="email"
                    required />
                <ion-icon class="form-icon" name="mail"></ion-icon>
                <div class="error-box">
                    <p class="error-text">Looks like this is not a correct email</p>
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
                    required />
                <ion-icon class="form-icon" name="lock-closed"></ion-icon>
                <div class="error-box">
                    <p class="error-text">Password cannot be empty</p>
                    <ion-icon class="error-icon" name="alert"></ion-icon>
                </div>
            </div>

            <button type="submit" class="btn btn--full btn--form">Login</button>

        </form>
        <div class="question-box flex-conteiner">
            <p>Don't have an account?</p>
            <a class="link" href="/register">Sing up</a>
        </div>
    </section>

    <?php
    include $this->resolve("partials/_scripts.php");
    ?>
</body>

</html>