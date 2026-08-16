/* ============================================================
   Conceiba — Toast Alerts (glass)
   Convierte automáticamente cualquier .alert que ya exista en la
   página (las de $_SESSION['error']/['success'] etc, sin tocar
   cada archivo PHP) en una notificación flotante estilo Reddit:
   aparece abajo, tiene una barra que se va reduciendo, y se
   auto-oculta a los 5s. El usuario también puede cerrarla a mano.
   ============================================================ */
(function () {
  const AUTO_DISMISS_MS = 5000;

  function iconFor(alertEl) {
    if (alertEl.classList.contains('alert-danger')) return 'fa-circle-exclamation';
    if (alertEl.classList.contains('alert-success')) return 'fa-circle-check';
    if (alertEl.classList.contains('alert-warning')) return 'fa-triangle-exclamation';
    return 'fa-circle-info';
  }

  function buildToastStack() {
    let stack = document.getElementById('toast-glass-stack');
    if (!stack) {
      stack = document.createElement('div');
      stack.id = 'toast-glass-stack';
      document.body.appendChild(stack);
    }
    return stack;
  }

  // Crea y muestra un toast a partir de texto plano + tipo (success/danger/warning/info).
  // Esto es lo que usa mariaAlert() para reemplazar los alert() nativos del navegador.
  function createToast(message, type) {
    const iconMap = {
      danger: 'fa-circle-exclamation',
      success: 'fa-circle-check',
      warning: 'fa-triangle-exclamation',
      info: 'fa-circle-info',
    };
    const toast = document.createElement('div');
    toast.className = 'toast-glass glass toast-' + (type || 'info');
    toast.innerHTML = `
      <button type="button" class="toast-glass-close" aria-label="Cerrar">&times;</button>
      <div class="toast-glass-body">
        <i class="fa ${iconMap[type] || iconMap.info} toast-glass-icon"></i>
        <div class="toast-glass-content">${message}</div>
      </div>
      <div class="toast-glass-progress"></div>
    `;

    const stack = buildToastStack();
    stack.appendChild(toast);

    const progress = toast.querySelector('.toast-glass-progress');
    requestAnimationFrame(() => {
      progress.style.transitionDuration = AUTO_DISMISS_MS + 'ms';
      progress.style.width = '0%';
    });

    let dismissTimer = setTimeout(() => dismiss(toast), AUTO_DISMISS_MS);

    toast.querySelector('.toast-glass-close').addEventListener('click', () => {
      clearTimeout(dismissTimer);
      dismiss(toast);
    });

    toast.addEventListener('mouseenter', () => {
      clearTimeout(dismissTimer);
      progress.style.transitionDuration = '0ms';
      progress.style.width = getComputedStyle(progress).width;
    });

    return toast;
  }

  function convertAlert(original) {
    if (original.dataset.toastProcessed) return;
    original.dataset.toastProcessed = '1';

    // Extraer el texto/heading sin el botón de cerrar viejo
    const clone = original.cloneNode(true);
    const oldClose = clone.querySelector('.close, .btn-close, [data-bs-dismiss="alert"]');
    if (oldClose) oldClose.remove();

    const type = original.classList.contains('alert-danger') ? 'danger'
      : original.classList.contains('alert-success') ? 'success'
      : original.classList.contains('alert-warning') ? 'warning' : 'info';

    createToast(clone.innerHTML, type);
    original.style.display = 'none';
  }

  function dismiss(toast) {
    toast.classList.add('toast-glass-hide');
    setTimeout(() => toast.remove(), 300);
  }

  function scan() {
    document.querySelectorAll('.alert:not([data-toast-processed])').forEach(convertAlert);
  }

  // Formularios de eliminación con confirmación glass en vez de confirm() nativo.
  // Uso: <form class="maria-confirm-form" data-confirm-msg="¿Eliminar esto?">
  document.addEventListener('submit', async function (e) {
    const form = e.target.closest ? e.target.closest('.maria-confirm-form') : null;
    if (!form || form.dataset.confirmed === '1') return;
    e.preventDefault();
    const ok = await window.mariaConfirm(form.dataset.confirmMsg || '¿Estás seguro?', 'Confirmar eliminación');
    if (ok) {
      form.dataset.confirmed = '1';
      form.requestSubmit ? form.requestSubmit() : form.submit();
    }
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', scan);
  } else {
    scan();
  }

  // Por si algún script agrega alertas después vía AJAX
  window.refreshToastAlerts = scan;

  // Reemplazo directo de alert() nativo del navegador, con el estilo glass
  // de Conceiba. Uso: mariaAlert('Mensaje', 'danger'|'success'|'warning'|'info')
  window.mariaAlert = function (message, type) {
    createToast(message, type || 'info');
  };

  // Reemplazo de confirm() nativo, con el estilo glass. Como abrir un modal
  // es asíncrono, esto devuelve una Promise<boolean> — hay que usarlo con
  // await o .then(), no se puede usar como "if (confirm(...))" directo.
  // Uso: const ok = await mariaConfirm('¿Eliminar esto?'); if (ok) { ... }
  window.mariaConfirm = function (message, title) {
    return new Promise(function (resolve) {
      let modalEl = document.getElementById('maria-confirm-modal');
      if (!modalEl) {
        modalEl = document.createElement('div');
        modalEl.id = 'maria-confirm-modal';
        modalEl.className = 'modal fade';
        modalEl.innerHTML = `
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass">
              <div class="modal-header">
                <h5 class="modal-title" id="maria-confirm-title">¿Estás seguro?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="maria-confirm-body"></div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="maria-confirm-cancel">Cancelar</button>
                <button type="button" class="btn btn-danger" id="maria-confirm-ok">Sí, continuar</button>
              </div>
            </div>
          </div>
        `;
        document.body.appendChild(modalEl);
      }

      modalEl.querySelector('#maria-confirm-title').textContent = title || '¿Estás seguro?';
      modalEl.querySelector('#maria-confirm-body').textContent = message;

      const bsModal = (window.bootstrap) ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;
      const okBtn = modalEl.querySelector('#maria-confirm-ok');
      const cancelBtn = modalEl.querySelector('#maria-confirm-cancel');

      function cleanup(result) {
        okBtn.removeEventListener('click', onOk);
        cancelBtn.removeEventListener('click', onCancel);
        modalEl.removeEventListener('hidden.bs.modal', onCancel);
        resolve(result);
      }
      function onOk() {
        if (bsModal) bsModal.hide();
        cleanup(true);
      }
      function onCancel() {
        cleanup(false);
      }

      okBtn.addEventListener('click', onOk);
      cancelBtn.addEventListener('click', onCancel);
      modalEl.addEventListener('hidden.bs.modal', onCancel, { once: true });

      if (bsModal) bsModal.show();
      else resolve(window.confirm(message)); // respaldo si Bootstrap no cargó
    });
  };
})();
