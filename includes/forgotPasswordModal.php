<div class="modal" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-back" data-target="#loginModal" aria-label="Back">
                    <span aria-hidden="true">&larr;</span>
                </button>
                <h5 class="modal-title">Forgot Password</h5>
                <p class="modal-subtitle" style="font-size: 20px; margin-top: 10px;">Tell us the username and email
                    associated
                    with your SimplyDelicious account, and we’ll send you an email with your password.</p>
                <button type="button" style="margin-top: 0px" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="forgotPasswordForm">
                    <input type="text" id="forgotPassUsernameInput" placeholder="username" required>
                    <input type="email" id="forgotPassEmailInput" placeholder="email" required>
                    <button type="submit"
                        style="padding: 10px; border-radius: 10px; margin-top: 15px; background-color: white; font-size: 20px;">Send
                        Password</button>
                </form>
            </div>
        </div>
    </div>
</div>