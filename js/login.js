'use strict';

document.addEventListener('DOMContentLoaded', () => {

  const form       = document.querySelector('#loginForm');
  const userInput  = document.querySelector('#loginUser');
  const passInput  = document.querySelector('#loginPass');
  const togglePass = document.querySelector('#togglePass');
  const toggleIcon = document.querySelector('#togglePassIcon');
  const loginError = document.querySelector('#loginError');

  if (!form) return;

  // Toggle ver/ocultar contraseña
  togglePass?.addEventListener('click', () => {
    const isPass     = passInput.type === 'password';
    passInput.type   = isPass ? 'text' : 'password';
    toggleIcon.className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
  });

  // Ocultar error al escribir
  userInput?.addEventListener('input', () => loginError?.classList.add('d-none'));
  passInput?.addEventListener('input', () => loginError?.classList.add('d-none'));

  // Submit
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    let valid = true;

    const userErr = document.querySelector('#loginUserError');
    const passErr = document.querySelector('#loginPassError');

    if (!userInput.value.trim()) {
      userErr.textContent = 'El usuario es obligatorio.';
      userInput.classList.add('is-invalid');
      valid = false;
    } else {
      userErr.textContent = '';
      userInput.classList.remove('is-invalid');
    }

    if (!passInput.value.trim()) {
      passErr.textContent = 'La contraseña es obligatoria.';
      passInput.classList.add('is-invalid');
      valid = false;
    } else {
      passErr.textContent = '';
      passInput.classList.remove('is-invalid');
    }

    if (!valid) return;

    if (userInput.value === 'admin' && passInput.value === '1234') {
      window.location.href = 'dashboard.html';
    } else {
      loginError?.classList.remove('d-none');
    }
  });

});