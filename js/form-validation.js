/**
 * ============================================================
 * PORTAFOLIO — form-validation.js
 * Validación del formulario de contacto:
 *   - Validación en tiempo real por campo
 *   - Simulación de envío (reemplazar con fetch() real)
 *   - Estados visuales: cargando, éxito, error
 * ============================================================
 */

'use strict';

const $ = (selector, parent = document) => parent.querySelector(selector);

(function initContactForm() {
  const form         = $('#contactForm');
  if (!form) return;

  const nameInput    = $('#contactName');
  const emailInput   = $('#contactEmail');
  const messageInput = $('#contactMessage');
  const submitBtn    = $('#submitBtn');
  const submitText   = $('#submitText');
  const submitLoader = $('#submitLoader');
  const submitIcon   = $('#submitIcon');
  const successMsg   = $('#formSuccess');

  /* ── Validaciones ── */

  function validateName() {
    const val = nameInput.value.trim();
    const err = $('#nameError');

    if (!val) {
      showError(nameInput, err, 'El nombre es obligatorio.');
      return false;
    }
    if (val.length < 2) {
      showError(nameInput, err, 'Mínimo 2 caracteres.');
      return false;
    }
    showValid(nameInput, err);
    return true;
  }

  function validateEmail() {
    const val   = emailInput.value.trim();
    const err   = $('#emailError');
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!val) {
      showError(emailInput, err, 'El correo es obligatorio.');
      return false;
    }
    if (!regex.test(val)) {
      showError(emailInput, err, 'Ingresa un correo válido.');
      return false;
    }
    showValid(emailInput, err);
    return true;
  }

  function validateMessage() {
    const val = messageInput.value.trim();
    const err = $('#messageError');

    if (!val) {
      showError(messageInput, err, 'El mensaje es obligatorio.');
      return false;
    }
    if (val.length < 10) {
      showError(messageInput, err, 'Mínimo 10 caracteres.');
      return false;
    }
    showValid(messageInput, err);
    return true;
  }

  /* ── Helpers de estado visual ── */

  function showError(input, errorEl, msg) {
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    if (errorEl) errorEl.textContent = msg;
  }

  function showValid(input, errorEl) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
    if (errorEl) errorEl.textContent = '';
  }

  /* ── Validación en tiempo real ── */
  nameInput?.addEventListener('input', validateName);
  emailInput?.addEventListener('input', validateEmail);
  messageInput?.addEventListener('input', validateMessage);

  /* ── Submit del formulario ── */
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const isNameOk    = validateName();
    const isEmailOk   = validateEmail();
    const isMessageOk = validateMessage();

    if (!isNameOk || !isEmailOk || !isMessageOk) return;

    setSubmitState('loading');

    try {
      // TODO: Reemplazar simulateSend() con fetch() real a tu API/servicio
      // Ejemplo con Formspree:
      // await fetch('https://formspree.io/f/YOUR_ID', {
      //   method: 'POST',
      //   headers: { 'Content-Type': 'application/json' },
      //   body: JSON.stringify({
      //     name: nameInput.value,
      //     email: emailInput.value,
      //     message: messageInput.value
      //   })
      // });

      await simulateSend();

      setSubmitState('success');
      form.reset();
      [nameInput, emailInput, messageInput].forEach(inp => {
        inp.classList.remove('is-valid', 'is-invalid');
      });
      successMsg?.classList.remove('d-none');
      setTimeout(() => successMsg?.classList.add('d-none'), 5000);

    } catch (err) {
      console.error('Error al enviar formulario:', err);
      setSubmitState('error');
    }
  });

  /**
   * Simula una solicitud de red.
   * REEMPLAZAR con fetch() real en producción.
   */
  function simulateSend() {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        Math.random() > 0.1 ? resolve() : reject(new Error('Simulated error'));
      }, 1500);
    });
  }

  /**
   * Cambia el estado visual del botón de enviar.
   * @param {'loading'|'success'|'error'} state
   */
  function setSubmitState(state) {
    if (!submitBtn) return;

    if (state === 'loading') {
      submitBtn.disabled = true;
      submitText?.classList.add('d-none');
      submitLoader?.classList.remove('d-none');
      if (submitIcon) submitIcon.className = '';
    } else {
      submitBtn.disabled = false;
      submitText?.classList.remove('d-none');
      submitLoader?.classList.add('d-none');

      if (state === 'success') {
        submitText.textContent = 'Enviado ✓';
        if (submitIcon) submitIcon.className = '';
        setTimeout(() => {
          submitText.textContent = 'Enviar Mensaje';
          if (submitIcon) submitIcon.className = 'bi bi-send-fill ms-2';
        }, 3000);
      } else {
        submitText.textContent = 'Error. Intenta de nuevo.';
        if (submitIcon) submitIcon.className = 'bi bi-exclamation-circle ms-2';
        setTimeout(() => {
          submitText.textContent = 'Enviar Mensaje';
          if (submitIcon) submitIcon.className = 'bi bi-send-fill ms-2';
        }, 4000);
      }
    }
  }
})();
