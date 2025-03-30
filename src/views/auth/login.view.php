<div class="auth-container ">
    <form action="/login" method="POST">
        <p class='h2'>Login Account</p>
        <div class="auth-input-fields">
            <input type="text" name="username" id="username" class="auth-input-field" placeholder="Username"/>
            <input type="password" name="password" id="pasword" class="auth-input-field" placeholder="Password"/>
            <input type="submit" value="Login Account" class="auth-input-field auth-submit" />
            <?php
                echo $content["error"];
            ?> 
        </div>
    </form>
</div>