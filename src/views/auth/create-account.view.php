<div class="auth-container flex flex-col">
    <form action="/create-account" method="POST">
        <p class='h2'>Create Account</p>

        <?php if (!empty($content['errors'])): ?>
            <div class="error-messages" style="color: red; font-size: 0.8em; margin-bottom: 10px;">
                <?php foreach ($content['errors'] as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="auth-input-fields">
            <input type="text" name="firstname" id="firstname" class="auth-input-field" placeholder="First Name" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
            <input type="text" name="lastname" id="lastname" class="auth-input-field" placeholder="Last Name" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
            <input type="text" name="username" id="username" class="auth-input-field" placeholder="Username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            <input type="text" name="email" id="email" class="auth-input-field" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <input type="password" name="password" id="password" class="auth-input-field" placeholder="Password">
            <input type="password" name="retype-password" id="retype-password" class="auth-input-field" placeholder="Retype Password">
            <input type="submit" value="Create Account" class="auth-input-field auth-submit">
            <p>Already have an account? <a href="/login">Login</a></p>
        </div>
    </form>
</div>
