/**
 * =====================================================
 * THEME SWITCHER - JavaScript para Sistema de Temas
 * =====================================================
 * Gestiona el cambio entre modo claro y oscuro
 * Guarda preferencias en localStorage
 * Aplica el tema automáticamente al cargar
 */

(function() {
  'use strict';

  // Configuración
  const THEME_KEY = 'conceiba_theme';
  const THEME_DARK = 'dark';
  const THEME_LIGHT = 'light';
  
  /**
   * Obtener el tema actual desde localStorage
   * @returns {string} 'light' o 'dark'
   */
  function getCurrentTheme() {
    return localStorage.getItem(THEME_KEY) || THEME_LIGHT;
  }
  
  /**
   * Guardar el tema en localStorage
   * @param {string} theme - 'light' o 'dark'
   */
  function saveTheme(theme) {
    localStorage.setItem(THEME_KEY, theme);
  }
  
  /**
   * Aplicar el tema al documento
   * @param {string} theme - 'light' o 'dark'
   */
  function applyTheme(theme) {
    if (theme === THEME_DARK) {
      document.documentElement.setAttribute('data-theme', 'dark');
    } else {
      document.documentElement.removeAttribute('data-theme');
    }
    
    // Actualizar el icono del botón toggle
    updateToggleButton(theme);
    
    // Forzar actualización de date pickers si existen
    updateDatePickers();
  }
  
  /**
   * Alternar entre temas
   */
  function toggleTheme() {
    const currentTheme = getCurrentTheme();
    const newTheme = currentTheme === THEME_LIGHT ? THEME_DARK : THEME_LIGHT;
    
    saveTheme(newTheme);
    applyTheme(newTheme);
    
    // Disparar evento personalizado para que otros scripts puedan reaccionar
    document.dispatchEvent(new CustomEvent('themeChanged', { 
      detail: { theme: newTheme } 
    }));
  }
  
  /**
   * Actualizar el icono del botón toggle
   * @param {string} theme - 'light' o 'dark'
   */
  function updateToggleButton(theme) {
    const toggleBtn = document.getElementById('theme-toggle');
    if (!toggleBtn) return;
    
    const icon = toggleBtn.querySelector('i');
    const text = toggleBtn.querySelector('.toggle-text');
    
    if (theme === THEME_DARK) {
      if (icon) {
        icon.className = 'fa fa-sun-o';
      }
      if (text) {
        text.textContent = 'Modo Claro';
      }
      toggleBtn.title = 'Cambiar a modo claro';
    } else {
      if (icon) {
        icon.className = 'fa fa-moon-o';
      }
      if (text) {
        text.textContent = 'Modo Oscuro';
      }
      toggleBtn.title = 'Cambiar a modo oscuro';
    }
  }
  
  /**
   * Forzar actualización visual de date pickers
   */
  function updateDatePickers() {
    // Date range picker
    if (typeof $.fn.daterangepicker !== 'undefined') {
      $('.daterangepicker').each(function() {
        $(this).addClass('theme-updated');
      });
    }
    
    // Bootstrap datepicker
    if (typeof $.fn.datepicker !== 'undefined') {
      $('.datepicker').each(function() {
        $(this).addClass('theme-updated');
      });
    }
  }
  
  /**
   * Inicializar el sistema de temas
   */
  function initThemeSystem() {
    // Aplicar tema guardado inmediatamente
    const savedTheme = getCurrentTheme();
    applyTheme(savedTheme);
    
    // Esperar a que el DOM esté listo
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', setupToggleButton);
    } else {
      setupToggleButton();
    }
  }
  
  /**
   * Configurar el botón de toggle
   */
  function setupToggleButton() {
    const toggleBtn = document.getElementById('theme-toggle');
    if (toggleBtn) {
      toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        toggleTheme();
      });
      
      // Actualizar icono inicial
      updateToggleButton(getCurrentTheme());
    }
  }
  
  /**
   * Detectar preferencia del sistema operativo
   * @returns {string} 'light' o 'dark'
   */
  function detectSystemTheme() {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      return THEME_DARK;
    }
    return THEME_LIGHT;
  }
  
  /**
   * Escuchar cambios en la preferencia del sistema
   */
  function listenToSystemThemeChanges() {
    if (window.matchMedia) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
      
      // Listener para cambios
      mediaQuery.addEventListener('change', function(e) {
        // Solo aplicar si el usuario no ha configurado un tema manualmente
        if (!localStorage.getItem(THEME_KEY)) {
          const systemTheme = e.matches ? THEME_DARK : THEME_LIGHT;
          applyTheme(systemTheme);
        }
      });
    }
  }
  
  /**
   * API pública para otros scripts
   */
  window.ConeibaTheme = {
    toggle: toggleTheme,
    getCurrent: getCurrentTheme,
    set: function(theme) {
      if (theme === THEME_LIGHT || theme === THEME_DARK) {
        saveTheme(theme);
        applyTheme(theme);
      }
    },
    isDark: function() {
      return getCurrentTheme() === THEME_DARK;
    },
    isLight: function() {
      return getCurrentTheme() === THEME_LIGHT;
    }
  };
  
  // Inicializar inmediatamente (antes del DOMContentLoaded)
  initThemeSystem();
  
  // Escuchar cambios del sistema
  listenToSystemThemeChanges();
  
  // Observador para date pickers que se crean dinámicamente
  if (typeof MutationObserver !== 'undefined') {
    const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.addedNodes.length) {
          mutation.addedNodes.forEach(function(node) {
            if (node.nodeType === 1) { // Element node
              if (node.classList && (node.classList.contains('daterangepicker') || node.classList.contains('datepicker'))) {
                updateDatePickers();
              }
            }
          });
        }
      });
    });
    
    // Observar cambios en el body
    document.addEventListener('DOMContentLoaded', function() {
      observer.observe(document.body, {
        childList: true,
        subtree: true
      });
    });
  }
  
})();

/**
 * Función de ayuda para integración con jQuery
 */
if (typeof jQuery !== 'undefined') {
  (function($) {
    $(document).ready(function() {
      // Re-aplicar estilos cuando se abren date pickers
      $(document).on('show.daterangepicker', function(ev, picker) {
        setTimeout(function() {
          if (picker && picker.container) {
            picker.container.addClass('theme-updated');
          }
        }, 10);
      });
      
      // Para bootstrap datepicker
      $(document).on('show', '.datepicker', function() {
        setTimeout(function() {
          $('.datepicker').addClass('theme-updated');
        }, 10);
      });
    });
  })(jQuery);
}

// Log para debugging (remover en producción)
console.log('Conceiba Theme Switcher loaded - Current theme:', window.ConeibaTheme.getCurrent());
