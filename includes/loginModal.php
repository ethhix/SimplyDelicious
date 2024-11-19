<?php $loginError = ""; ?>

<!-- Login Modal -->
<div class="modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-custom-height">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Log In</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Social Login Options -->
                <button id="googleLogin">
                    <i class="fab fa-google" style="margin-right: 10px"></i>
                    Continue with Google
                </button>
                <hr />
                <!-- Manual Login Form -->
                <form id="loginForm">
                    <input type="text" id="username" name="loginUsername" placeholder="Email or username *" required>
                    <div class="form-group">
                        <input id="password" name="loginPassword" type="password" placeholder="Enter your password"
                            required>
                        <!-- display and hide login password when selected -->
                        <span class="show-login-pass" onclick="toggleLoginPassword()">
                            <!-- when we select the eye, change it to a slashed eye -->
                            <i class="far fa-eye" onclick="changeEyeIcon(this)"></i>
                        </span>
                    </div>
                    <div class="login-options">
                        <p class="error" id="loginError">
                            <!-- display login error under the password textfield if we find one -->
                            <?php echo $loginError; ?>
                        </p>
                        <p class="forgot-username-password">Forgot your
                            <a id="forgotUsernameAnchor" href="#" data-toggle="modal"
                                data-target="#forgotUsernameModal">username</a>
                            or
                            <a id="forgotPasswordAnchor" href="#" data-toggle="modal"
                                data-target="#forgotPasswordModal">password?</a>
                        </p>
                        <p>Don't have an account?
                            <a id="loginSignupButton" href="#" data-toggle="modal" data-target="#signupModal">Sign
                                Up</a>
                        </p>
                    </div>
                    <button class="loginButton" type="submit">Log In</button>
                </form>
            </div>
        </div>
    </div>
</div>