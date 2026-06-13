<script>
document.querySelectorAll('.password-toggle').forEach(btn => {
    btn.addEventListener('click', function () {
        const target = this.dataset.target;
        const input = document.getElementById(target);
        const icon = this.querySelector('i');
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});

document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('focus', function () {
        this.parentElement?.classList.add('focused');
    });
    input.addEventListener('blur', function () {
        if (!this.value) {
            this.parentElement?.classList.remove('focused');
        }
    });
});
</script>
