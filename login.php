<!DOCTYPE html>
<html>

<head>
    <title>Login and Signup</title>
    <link rel="stylesheet" href="./style/reg-log.css">
</head>

<body>
    <div class="main-box">
        <div class="form-box">
            <div class="button-box">
                <div id="btn"></div>
                <button type="button" class="toggle-btn" onclick="login()">Login</button>
                <button type="button" class="toggle-btn" onclick="signup()">Signup</button>
            </div>

            <div class="socialmedia">
                <img src="./images/login-reg-img/fb.png" alt="">
                <img src="./images/login-reg-img/gp.png" alt="">
                <img src="./images/login-reg-img/tw.png" alt="">
            </div>

            <form action="process.php" method="POST" id="login" class="input-group">
                <label for="email"></label>
                <input type="email" name="email" id="email" class="input-field" placeholder="Email " required>
                <label for="password"></label>
                <input type="password" name="password" class="input-field" id="password" placeholder="Password"
                    required>
                <input type="checkbox" class="check-box" id="showPassword" onclick="togglePassword()">
                <span>Show Password</span>
                <button type="submit" class="submit-btn">Login</button>
            </form>

            <form action="process.php" method="POST" id="signup" class="input-group">
                <label for="username1"></label>
                <input type="text" name="username" id="username1" class="input-field" placeholder="Name" required>
                <label for="email1"></label>
                <input type="email" name="email" id="email1" class="input-field" placeholder="Email" required>
                <label for="password1"></label>
                <input type="password" name="password" class="input-field" id="password1" placeholder="Password" required>
                <input type="checkbox" class="check-box"><span>I agree to the terms & conditions</span>
                <button type="submit" class="submit-btn">Signup</button>
            </form>
        </div>
    </div>
    <script src="./js/reg-log.js"></script>
</body>

</html>