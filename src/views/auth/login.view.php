<div class="auth-container">
    <form action="/login" method="POST">
        <p class='h2'>Login Account</p>
        <div class="auth-input-fields">
            <input type="text" name="username" id="username" class="auth-input-field" placeholder="Username" value="<?= htmlspecialchars($content['old']['username'] ?? '') ?>" />
            <?php if (!empty($content['errors']['username'])): ?>
                <p class="error-message"><?= htmlspecialchars($content['errors']['username']) ?></p>
            <?php endif; ?>

            <input type="password" name="password" id="password" class="auth-input-field" placeholder="Password" />
            <?php if (!empty($content['errors']['password'])): ?>
                <p class="error-message"><?= htmlspecialchars($content['errors']['password']) ?></p>
            <?php endif; ?>

            <input type="submit" value="Login Account" class="auth-input-field auth-submit" />
            
            <?php if (!empty($content['errors']['general'])): ?>
                <p class="error-message"><?= htmlspecialchars($content['errors']['general']) ?></p>
            <?php endif; ?>
        </div>
    </form>
</div>