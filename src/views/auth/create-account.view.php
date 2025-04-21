<div class="auth-container flex flex-col">
    <form action="/create-account" method="POST">
        <p class='h2'>Create Account</p>
        <div class="auth-input-fields">

            <input type="text" name="firstname" id="firstname" class="auth-input-field" placeholder="First Name" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
            <?php if (!empty($content['errors']['firstname'])): ?>
                <p class="error"><?= $content['errors']['firstname']; ?></p>
            <?php endif; ?>

            <input type="text" name="lastname" id="lastname" class="auth-input-field" placeholder="Last Name" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
            <?php if (!empty($content['errors']['lastname'])): ?>
                <p class="error"><?= $content['errors']['lastname']; ?></p>
            <?php endif; ?>

            <input type="text" name="username" id="username" class="auth-input-field" placeholder="Username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            <?php if (!empty($content['errors']['username'])): ?>
                <p class="error"><?= $content['errors']['username']; ?></p>
            <?php endif; ?>

            <input type="text" name="email" id="email" class="auth-input-field" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <?php if (!empty($content['errors']['email'])): ?>
                <p class="error"><?= $content['errors']['email']; ?></p>
            <?php endif; ?>

            <input type="password" name="password" id="password" class="auth-input-field" placeholder="Password">
            <?php if (!empty($content['errors']['password'])): ?>
                <p class="error"><?= $content['errors']['password']; ?></p>
            <?php endif; ?>

            <input type="password" name="retype-password" id="retype-password" class="auth-input-field" placeholder="Retype Password">
            <?php if (!empty($content['errors']['retype-password'])): ?>
                <p class="error"><?= $content['errors']['retype-password']; ?></p>
            <?php endif; ?>

            <input type="submit" value="Create Account" class="auth-input-field auth-submit">
        </div>
    </form>
</div>
        <div class="auth-footer">
            <p>Already have an account? <a href="/login">Login</a></p>
        </div>
    </div>