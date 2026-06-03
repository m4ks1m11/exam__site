// Простая валидация форм
document.addEventListener('DOMContentLoaded', function() {
    // Валидация формы регистрации
    const registerForm = document.querySelector('form[action="register.php"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirm');
            const email = document.getElementById('email');

            // Проверка паролей
            if (password && passwordConfirm && password.value !== passwordConfirm.value) {
                e.preventDefault();
                alert('Пароли не совпадают!');
                return false;
            }

            // Проверка email
            if (email && !email.value.includes('@')) {
                e.preventDefault();
                alert('Введите корректный email!');
                return false;
            }
        });
    }

    // Валидация формы входа
    const loginForm = document.querySelector('form[action="login.php"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');

            if (!email.value || !password.value) {
                e.preventDefault();
                alert('Заполните все поля!');
                return false;
            }
        });
    }

    // Валидация новой заявки
    const requestForm = document.querySelector('form[action="new-request.php"]');
    if (requestForm) {
        requestForm.addEventListener('submit', function(e) {
            const service = document.getElementById('service');
            if (service && !service.value) {
                e.preventDefault();
                alert('Выберите услугу!');
                return false;
            }
        });
    }
});
