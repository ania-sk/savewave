<?php include $this->resolve("partials/_header.php"); ?>

<body>
    <header class="header flex-container">
        <div class="logo-box">
            <a class="link flex-container" href="/">
                <img
                    class="logo-sw"
                    src="/assets/imgs/save-wave-circle.png"
                    alt="Save Wave logo" />
                <span class="header-save">Save Wave</span>
            </a>
        </div>
        <!-- NAVIGATION -->
        <nav class="header-nav">
            <div class="btn-box flex-container">
                <a href="/login" class="btn">Login</a>
                <a href="/register" class="btn btn--full">Sign-up</a>
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
            <div class="hero-img-box flex-container">
                <img class="hero-img" src="/assets/imgs/hero.png" alt="" />
            </div>
            <div class="hero-text-box flex-container">
                <h1 class="heading">
                    Wave Goodbye to Worries,<br />
                    Save Money for What Matters!
                </h1>
                <p class="hero-text">
                    SaveWave is the app for those riding the wave of savings.
                </p>

                <div class="btn-box flex-container">
                    <a href="/login" class="btn">Login</a>
                    <a href="/register" class="btn btn--full">Sign-up</a>
                </div>
            </div>
        </section>

        <!-- FEATURES SECTION -->
        <section class="section-features mrg-bottom">
            <h2 class="heading-secondary">
                Saving has never been this easy and fun!
            </h2>
            <div class="features-box">
                <div class="feature-box flex-container">
                    <img
                        src="/assets/imgs/illustrations/track.png"
                        alt=""
                        class="feature-img" />
                    <p class="feature-text">Track your expenses and incomes</p>
                </div>

                <div class="feature-box flex-container">
                    <p class="feature-text">Compare balances over time</p>
                    <img
                        src="/assets/imgs/illustrations/compare.png"
                        alt=""
                        class="feature-img" />
                </div>

                <div class="feature-box flex-container">
                    <img
                        src="/assets/imgs/illustrations/cours.png"
                        alt=""
                        class="feature-img" />
                    <p class="feature-text">Stay on course toward your dreams</p>
                </div>
            </div>
        </section>
    </main>


</body>
<?php
include $this->resolve("partials/_footer.php");
?>

</html>