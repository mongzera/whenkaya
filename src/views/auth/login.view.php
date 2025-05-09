<div class="auth-container">
    <form action="/login" method="POST">
        <p class='h2'>Login Account</p>

        <!-- General Error (e.g., Invalid Credentials) -->
        <?php if (!empty($content['errors']['general'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($content['errors']['general'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div class="auth-input-fields">
            <!-- Username Input -->
            <input type="text" name="username" id="username" class="auth-input-field" placeholder="Username" value="<?= htmlspecialchars($content['old']['username'] ?? '') ?>" required />
            <?php if (!empty($content['errors']['username'])): ?>
                <div class="error-message">
                    <?= htmlspecialchars($content['errors']['username'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <!-- Password Input -->
            <input type="password" name="password" id="password" class="auth-input-field" placeholder="Password" required />
            <?php if (!empty($content['errors']['password'])): ?>
                <div class="error-message">
                    <?= htmlspecialchars($content['errors']['password'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <!-- Submit Button -->
            <input type="submit" value="Login Account" class="auth-input-field auth-submit" />
        </div>

        <!-- Lower Box for "Don't have an account?" -->
        <div class="auth-lower-box">
            <p>Don't have an account? <a href="/create-account">Register</a></p>
        </div>
    </form>
</div>