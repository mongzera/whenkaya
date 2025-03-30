<div class="auth-container flex flex-col">
    <form action="/create-account" method="POST">
        <p class='h2'>Create Account</p>
        <div class="auth-input-fields">
            <input type="text" name="firstname" id="firstname" class="auth-input-field" placeholder="First Name"/>
            <input type="text" name="lastname" id="lastname" class="auth-input-field" placeholder="Last Name"/>
            <input type="text" name="username" id="username" class="auth-input-field" placeholder="Username"/>
            <input type="text" name="email" id="email" class="auth-input-field" placeholder="Email"/>
            <input type="password" name="password" id="password" class="auth-input-field" placeholder="Password"/>
            <input type="password" name="retype-password" id="retype-password" class="auth-input-field" placeholder="Retype-Password"/>
            <input type="submit" value="Create Account" class="auth-input-field auth-submit" />
        </div>
    </form>

    <?php 
    echo "<br>";
    echo $content['error'];
    
    ?>
</div>