<?php
include $this->resolve("partials/_header.php");
?>

<body>
    <section class="section-login flex-conteiner">
        <div class="logo-box">
            <a class="link flex-conteiner" href="/">
                <img
                    class="logo-sw"
                    src="/assets/imgs/save-wave-circle.png"
                    alt="Save Wave logo" />
                <span class="header-save">Save Wave</span>
            </a>
        </div>

        <form method="post" class="login-form-box flex-conteiner">
            <?php include $this->resolve('partials/_csrf.php'); ?>

            <div class="input-box flex-conteiner">
                <label for="username">Email</label>
                <input
                    id="email"
                    type="email"
                    placeholder="me@example.com"
                    name="email"
                    value="<?php echo e($oldFormData['email'] ?? ''); ?>" />
                <ion-icon class="form-icon" name="mail"></ion-icon>
                <?php if (array_key_exists('email', $errors)) : ?>
                    <div>
                        <p class="error-text"><?php echo e($errors['email'][0]); ?></p>
                        <ion-icon class="error-icon" name="alert"></ion-icon>
                    </div>
                <?php endif; ?>

            </div>

            <div class="input-box flex-conteiner">
                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    placeholder="********"
                    name="password" />
                <ion-icon class="form-icon" name="lock-closed"></ion-icon>
                <?php if (array_key_exists('password', $errors)) : ?>
                    <div>
                        <p class="error-text"><?php echo e($errors['password'][0]); ?></p>
                        <ion-icon class="error-icon" name="alert"></ion-icon>
                    </div>
                <?php endif; ?>
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