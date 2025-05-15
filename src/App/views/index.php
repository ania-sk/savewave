<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SaveWave &mdash; <?php echo $title ?></title>
    <link rel="icon" href="imgs/save-wave-circle.png" />
    <link rel="stylesheet" href="/assets/global.css" />
    <link rel="stylesheet" href="/assets/main.css" />


</head>

<body>
    <header class="header flex-conteiner">
        <div class="logo-box">
            <a class="link flex-conteiner" href="savings-boat.php">
                <img
                    class="logo-sw"
                    src="/assets/imgs/save-wave-circle.png"
                    alt="Save Wave logo" />
                <span class="header-save">Save Wave</span>
            </a>
        </div>
        <!-- NAVIGATION -->
        <nav class="header-nav">
            <div class="btn-box flex-conteiner">
                <a href="login.php" class="btn">Login</a>
                <a href="singup.php" class="btn btn--full">Sing-up</a>
            </div>
        </nav>

        <button class="btn-mobile-nav">
            <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
            <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
        </button>
    </header>

    <main>
        <!-- HERO SECTION -->
        <section class="section-hero grid-cols-2 mrg-bottom">
            <div class="hero-img-box flex-conteiner">
                <img class="hero-img" src="imgs/hero.png" alt="" />
            </div>
            <div class="hero-text-box flex-conteiner">
                <h1 class="heading">
                    Wave Goodbye to Worries,<br />
                    Save Money for What Matters!
                </h1>
                <p class="hero-text">
                    SaveWave is the app for those riding the wave of savings.
                </p>

                <div class="btn-box flex-conteiner">
                    <a href="login.php" class="btn">Login</a>
                    <a href="singup.php" class="btn btn--full">Sing-up</a>
                </div>
            </div>
        </section>

        <!-- FEATURES SECTION -->
        <section class="section-features mrg-bottom">
            <h2 class="heading-secondary">
                Saving has never been this easy and fun!
            </h2>
            <div class="features-box">
                <div class="feature-box flex-conteiner">
                    <img
                        src="/assets/imgs/illustrations/track.png"
                        alt=""
                        class="feature-img" />
                    <p class="feature-text">Track your expenses and incomes</p>
                </div>

                <div class="feature-box flex-conteiner">
                    <p class="feature-text">Compare balances over time</p>
                    <img
                        src="/assets/imgs/illustrations/compare.png"
                        alt=""
                        class="feature-img" />
                </div>

                <div class="feature-box flex-conteiner">
                    <img
                        src="/assets/imgs/illustrations/cours.png"
                        alt=""
                        class="feature-img" />
                    <p class="feature-text">Stay on course toward your dreams</p>
                </div>
            </div>
        </section>
    </main>
    <!-- FOOTER -->
    <footer class="section-footer flex-conteiner">
        <img class="logo-sw" src="/assets/imgs/save-wave-circle.png" alt="" />
        <p class="copy">
            Copyright &copy; <span class="year">yyyy</span> by Ania-Ska
        </p>
    </footer>

</body>

</html>