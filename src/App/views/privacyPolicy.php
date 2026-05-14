<?php
include $this->resolve("partials/_header.php");
?>
<header class="header flex-container">
    <div class="logo-box">
        <a class="link flex-container" href="<?php echo isset($_SESSION['user']) ? '/homePage' : '/'; ?>">
            <img
                class="logo-sw"
                src="/assets/imgs/save-wave-circle.png"
                alt="Save Wave logo" />
            <span class="header-save">Save Wave</span>
        </a>
    </div>

    <nav class="header-nav">
        <div class="btn-box flex-container">
            <?php if (isset($_SESSION['user'])) : ?>
                <a href="/homePage" class="btn btn--full">Back to your SaveWave</a>
            <?php else : ?>
                <a href="/login" class="btn">Login</a>
                <a href="/register" class="btn btn--full">Sign-up</a>
            <?php endif; ?>
        </div>
    </nav>

    <button class="btn-mobile-nav">
        <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
        <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
    </button>
</header>

<main class="container-privacy">
    <section class="section-privacy">
        <h1 class="heading-secondary">Privacy Policy</h1>
        <p class="last-update">Last updated: 2026-05-14</p>

        <div class="privacy-content">
            <h2 class="heading-tertiary">1. Data Controller</h2>
            <p>The administrator of your personal data is <strong>Anna Słojewska</strong>, contact email: <strong>anna.slojewska.as@gmail.com</strong>. For any matters regarding data protection, please contact us via the email address provided above.</p>

            <h2 class="heading-tertiary">2. Data We Collect and Purpose</h2>

            <h3 class="sub-heading">a) Account Data</h3>
            <p>During registration, we collect your username, email address, and password (stored only as a bcrypt cryptographic hash). These data are necessary to provide the service.</p>

            <h3 class="sub-heading">b) Financial Data</h3>
            <p>While using the app, we store your incomes, expenses, categories, comments, and savings goals. This data is processed solely at your request to provide the core functionality of the application.</p>

            <h3 class="sub-heading">c) Server Logs</h3>
            <p>Our server automatically records your IP address, browser type, URLs visited, and timestamps. Logs are kept for <strong>30 days</strong> for security and technical diagnostic purposes.</p>

            <h3 class="sub-heading">d) Receipt Scanning (OCR)</h3>
            <p>The automatic receipt reading function processes your uploaded image <strong>exclusively locally</strong> on our server using the Tesseract OCR library. The image is NOT sent to external services and is NOT stored — it is deleted immediately after the amount and date are read.</p>

            <h2 class="heading-tertiary">3. Cookies</h2>
            <p>SaveWave uses only <strong>technical cookies</strong> necessary for its operation:</p>
            <ul class="privacy-list">
                <li><strong>Session Cookie</strong> – maintains the logged-in user session.</li>
                <li><strong>CSRF Token</strong> – protects against Cross-Site Request Forgery attacks.</li>
            </ul>
            <p>These cookies do not serve analytical or advertising purposes and do not require consent under the ePrivacy directive.</p>

            <h2 class="heading-tertiary">4. Data Recipients and Location</h2>
            <p>Your data is stored on <strong>DigitalOcean LLC</strong> servers located in Frankfurt (Germany, EU). We do not sell or share your data with third parties for marketing purposes.</p>

            <h2 class="heading-tertiary">5. Data Retention Period</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Data Category</th>
                        <th>Retention Period</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Account & Transactions</td>
                        <td>Until account deletion by the user</td>
                    </tr>
                    <tr>
                        <td>Server Logs</td>
                        <td>30 days</td>
                    </tr>
                    <tr>
                        <td>Receipt Images</td>
                        <td>Deleted immediately after OCR analysis</td>
                    </tr>
                    <tr>
                        <td>Session Cookies</td>
                        <td>Until logout or closing the browser</td>
                    </tr>
                </tbody>
            </table>

            <h2 class="heading-tertiary">6. Your Rights</h2>
            <p>Under GDPR, you have the following rights:</p>
            <ul class="privacy-list">
                <li><strong>Right to access</strong> your data (available in Settings).</li>
                <li><strong>Right to rectification</strong> (edit email/username in Settings).</li>
                <li><strong>Right to erasure</strong> ("Delete Account" in Settings).</li>
                <li><strong>Right to data portability</strong> (export data to JSON).</li>
                <li><strong>Right to lodge a complaint</strong> with the President of the Personal Data Protection Office (PUODO).</li>
            </ul>
        </div>
    </section>
</main>
<?php
include $this->resolve("partials/_footer.php");
?>