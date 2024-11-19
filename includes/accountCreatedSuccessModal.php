<div id="accountCreatedSuccessModal" style="display: none;">
    <div id="accountCreated-content">
        <span class="close" onclick="closeSuccessModal()">&times;</span>
        <p>Your account has been created successfully!</p>
        <p>Please continue by logging into your account!</p>
        <button onclick="closeSuccessModal()">Ok</button>
    </div>
</div>

<script>
    function closeSuccessModal() {
        document.getElementById('accountCreatedSuccessModal').style.display = 'none';
    }
</script>