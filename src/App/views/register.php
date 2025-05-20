<?php
include $this->resolve("partials/_header.php");
?>

<body>
    <section class="section-singup flex-conteiner">
        <div class="logo-box">
            <a class="link flex-conteiner" href="/">
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
                <label for="username">Username</label>
                <input
                    id="username"
                    type="text"
                    placeholder="moneySaver"
                    name="username"
                    value="<?php echo e($oldFormData['username'] ?? ''); ?>" />
                <ion-icon class="form-icon" name="person-add"></ion-icon>

                <?php if (array_key_exists('username', $errors)) : ?>
                    <div>
                        <p class="error-text"><?php echo e($errors['username'][0]); ?></p>
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
                    name="password"
                    value="" />
                <ion-icon class="form-icon" name="lock-closed"></ion-icon>

                <?php if (array_key_exists('password', $errors)) : ?>
                    <div>
                        <p class="error-text"><?php echo e($errors['password'][0]); ?></p>
                        <ion-icon class="error-icon" name="alert"></ion-icon>
                    </div>
                <?php endif; ?>
            </div>

            <div class="input-box flex-conteiner">
                <label for="confirm-password">Password confirmation</label>
                <input
                    id="confirm-password"
                    type="password"
                    placeholder="********"
                    name="confirm-password"
                    value="" />
                <ion-icon class="form-icon" name="lock-closed"></ion-icon>

                <?php if (array_key_exists('confirm-password', $errors)) : ?>
                    <div>
                        <p class="error-text"><?php echo e($errors['confirm-password'][0]); ?></p>
                        <ion-icon class="error-icon" name="alert"></ion-icon>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn--full btn--form">Sing-up</button>
            </div>
        </form>
        <div class="question-box flex-conteiner">
            <p>Alredy have an account?</p>
            <a class="link" href="/login">Login</a>
        </div>
    </section>

    <!--  -->
    <?php
    include $this->resolve("partials/_scripts.php");
    ?>

</body>

</html>