<?php $firstName = $lastName = $email = $username = "";
$nameError = $emailError = $usernameError = $passwordError = ""; ?>
<!-- Signup Modal -->
<div class="modal" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signupModalLabel">Sign Up</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Signup Form -->
                <form id="signupForm">
                    <h4
                        style='font-family: "Abril Fatface", serif; font-weight: 800; font-style: normal; font-size: 30px;'>
                        General Information</h4>
                    <input type="text" id="firstName" name="firstName" placeholder="First Name"
                        value="<?php echo $firstName; ?>" required>
                    <input type="text" id="lastName" name="lastName" placeholder="Last Name"
                        value="<?php echo $lastName; ?>" required>
                    <p class="error" id="nameError">
                        <!-- display nameError is found -->
                        <?php echo $nameError; ?>
                    </p>
                    <h4
                        style='font-family: "Abril Fatface", serif; font-weight: 800; font-style: normal; font-size: 30px;'>
                        Contact Information</h4>
                    <input type="email" id="signupEmail" name="email" placeholder="Email" value="<?php echo $email; ?>"
                        required>
                    <p class="error" id="emailError">
                        <!-- display emailError is found -->
                        <?php echo $emailError; ?>
                    </p>
                    <h4
                        style='font-family: "Abril Fatface", serif; font-weight: 800; font-style: normal; font-size: 30px;'>
                        Account Information</h4>
                    <input type="text" id="signupUsername" name="signupUsername" placeholder="Username"
                        value="<?php echo $email; ?>" required>
                    <p class="error" id="usernameError">
                        <!-- display usernameError is found -->
                        <?php echo $usernameError; ?>
                    </p>
                    <div class="form-group">
                        <input id="signupPassword" name="signupPassword" type="password"
                            placeholder="Enter your password" required>
                        <span class="show-pass" onclick="toggle()">
                            <i class="far fa-eye" onclick="myFunction(this)"></i>
                        </span>
                        <div id="popover-password">
                            <p><span id="result"></span></p>
                            <div class="progress">
                                <div id="password-strength" class="progress-bar" role="progressbar" aria-valuenow="40"
                                    aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                            </div>
                            <ul class="list-unstyled">
                                <li class="">
                                    <span class="low-upper-case" style="font-size: 20px">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Lowercase &amp; Uppercase
                                    </span>
                                </li>
                                <li class="">
                                    <span class="one-number" style="font-size: 20px">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Number (0-9)
                                    </span>
                                </li>
                                <li class="">
                                    <span class="one-special-char" style="font-size: 20px">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Special Character (!@#$%^&*)
                                    </span>
                                </li>
                                <li class="">
                                    <span class="eight-character" style="font-size: 20px">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;At least 8 Characters
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button class="signupButton" type="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
</div>