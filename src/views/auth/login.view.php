<div class="auth-container">
    <form action="/login" method="POST">
        <p class='h2'>Login Account</p>
        <div class="auth-input-fields">
            <input type="text" name="username" id="username" class="auth-input-field" placeholder="Username" required />
            <input type="password" name="password" id="password" class="auth-input-field" placeholder="Password" required />
            <input type="submit" value="Login Account" class="auth-input-field auth-submit" />
            <p>Don't have an account? <a href="/create-account">Register</a></p>
            <?php if (!empty($content["error"])): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($content["error"], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>