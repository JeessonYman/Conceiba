<?php

/**
 * =====================================================
 * M.A.R.I.A - INTERFAZ Y SISTEMA DE VOZ
 * =====================================================
 * Este archivo contiene el HTML, JavaScript y estilos
 * de la interfaz de M.A.R.I.A
 * 
 * AGREGAR A includes/scripts.php AL FINAL
 * =====================================================
 */
?>

<!-- ========================================== -->
<!-- M.A.R.I.A - INTERFAZ HTML -->
<!-- ========================================== -->

<!-- Botón flotante de M.A.R.I.A -->
<div class="maria-button" id="mariaButton">
    <img src="<?php echo (isset($_SESSION['admin'])) ? '../images/'.($settings['assistant_avatar'] ?? 'maria-avatar.png') : 'images/'.($settings['assistant_avatar'] ?? 'maria-avatar.png'); ?>" alt="M.A.R.I.A">
    <span class="maria-badge" id="mariaBadge" style="display: none;">1</span>
</div>

<!-- Modal del chat de M.A.R.I.A -->
<div class="maria-modal" id="mariaModal">
    <div class="maria-header">
        <div class="maria-header-info">
            <img src="<?php echo (isset($_SESSION['admin'])) ? '../images/'.($settings['assistant_avatar'] ?? 'maria-avatar.png') : 'images/'.($settings['assistant_avatar'] ?? 'maria-avatar.png'); ?>" alt="M.A.R.I.A" class="maria-avatar">
            <div class="maria-status">
                <p class="maria-name"><?php echo htmlspecialchars($settings['assistant_name'] ?? 'M.A.R.I.A'); ?></p>
                <p class="maria-online">
                    <span class="status-dot"></span>
                    En línea
                </p>
            </div>
        </div>
        <div id="mariaVoiceNotice" class="maria-voice-notice" style="display:none;font-size:12px;color:#666;margin-left:8px;padding-left:8px;border-left:1px solid #eee;"></div>
        <button type="button" class="maria-mute" id="mariaMute" title="Silenciar/Activar voz">
            <i class="fa fa-volume-up"></i>
        </button>
        <button type="button" class="maria-close" id="mariaClose">&times;</button>
    </div>

    <div class="maria-messages" id="mariaMessages">
        <div class="welcome-message">
            <img src="<?php echo (isset($_SESSION['admin'])) ? '../images/'.($settings['assistant_avatar'] ?? 'maria-avatar.png') : 'images/'.($settings['assistant_avatar'] ?? 'maria-avatar.png'); ?>" alt="M.A.R.I.A">
            <h3>¡Hola<?php if (isset($_SESSION['user']) && isset($user)) echo ', ' . $user['firstname']; ?>! Soy <?php echo htmlspecialchars($settings['assistant_name'] ?? 'M.A.R.I.A'); ?></h3>
            <p><strong><?php echo htmlspecialchars($settings['assistant_tagline'] ?? 'Modelo Avanzado de Respuesta e Interacción Automatizada'); ?></strong></p>
            <p>Tu asistente virtual de <?php echo htmlspecialchars($settings['store_name'] ?? 'Conceiba'); ?> 🌿</p>
            <p style="margin-top: 10px;">¿En qué puedo ayudarte hoy?</p>

            <div class="quick-questions">
                <div class="quick-question" data-question="¿Qué productos tienen disponibles?">
                    🛍️ ¿Qué productos tienen disponibles?
                </div>
                <div class="quick-question" data-question="¿Cuál es el precio de los cojines?">
                    💰 ¿Cuál es el precio de los cojines?
                </div>
                <div class="quick-question" data-question="¿Qué es la fibra de kapok?">
                    🌱 ¿Qué es la fibra de kapok?
                </div>
                <div class="quick-question" data-question="¿Cómo puedo contactarlos?">
                    📞 ¿Cómo puedo contactarlos?
                </div>
            </div>
        </div>
    </div>

    <div class="maria-input-area">
        <button type="button" class="maria-mic-button" id="mariaMicButton" title="Hablar con M.A.R.I.A">
            <i class="fa fa-microphone"></i>
        </button>
        <input
            type="text"
            class="maria-input"
            id="mariaInput"
            placeholder="Escribe tu mensaje..."
            maxlength="500"
            autocomplete="off">
        <button type="button" class="maria-send" id="mariaSend">
            <i class="fa fa-paper-plane"></i>
        </button>
    </div>
</div>

<!-- ========================================== -->
<!-- M.A.R.I.A - JAVASCRIPT -->
<!-- ========================================== -->
<script>
    // ========================================
    // SISTEMA DE CHAT DE M.A.R.I.A
    // ========================================
    (function() {
        'use strict';

        // Esperar a que el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMaria);
        } else {
            initMaria();
        }

        function initMaria() {
            const mariaButton = document.getElementById('mariaButton');
            const mariaModal = document.getElementById('mariaModal');
            const mariaClose = document.getElementById('mariaClose');
            const mariaInput = document.getElementById('mariaInput');
            const mariaSend = document.getElementById('mariaSend');
            const mariaMessages = document.getElementById('mariaMessages');
            const mariaBadge = document.getElementById('mariaBadge');
            const mariaMute = document.getElementById('mariaMute');
            const mariaVoiceNoticeEl = document.getElementById('mariaVoiceNotice');

            if (!mariaButton || !mariaModal) {
                // console.warn('M.A.R.I.A: Elementos no encontrados');
                return;
            }

            let isOpen = false;
            let isTyping = false;

            // Obtener la página actual y parámetros (para pasar slug de producto o categoría)
            const currentPage = window.location.pathname.split('/').pop() || 'index.php';
            const urlParams = new URLSearchParams(window.location.search);
            const currentProduct = urlParams.get('product') || '';
            const currentCategory = urlParams.get('category') || '';
            const currentTitle = document.title;

            // Determinar si estamos en admin
            const isAdmin = window.location.pathname.includes('admin');
            const avatarPath = isAdmin ? '../images/maria-avatar.png' : 'images/maria-avatar.png';

            // Detección de soporte de voz (cliente)
            const supportsSpeechSynthesis = 'speechSynthesis' in window;
            const supportsRecognition = ('SpeechRecognition' in window) || ('webkitSpeechRecognition' in window);
            const isSecureContext = (location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1');

            if (!supportsSpeechSynthesis || !supportsRecognition || !isSecureContext) {
                const messages = [];
                if (!supportsSpeechSynthesis) messages.push('Síntesis de voz no disponible en este navegador.');
                if (!supportsRecognition) messages.push('Reconocimiento por voz no disponible en este navegador.');
                if (!isSecureContext) messages.push('Las funciones de voz requieren HTTPS; en algunos hosts (no-local) están deshabilitadas.');
                if (mariaVoiceNoticeEl) {
                    mariaVoiceNoticeEl.textContent = messages.join(' ');
                    mariaVoiceNoticeEl.style.display = 'block';
                }

                // Desactivar botones que no funcionarán
                const micBtn = document.getElementById('mariaMicButton');
                if (micBtn && !supportsRecognition) {
                    micBtn.disabled = true;
                    micBtn.title = 'Micrófono no disponible en este navegador/host';
                    micBtn.style.opacity = 0.5;
                }
                if (mariaMute && !supportsSpeechSynthesis) {
                    mariaMute.disabled = true;
                    mariaMute.title = 'Síntesis de voz no soportada';
                    mariaMute.style.opacity = 0.5;
                }
            }

            // Toggle del modal
            mariaButton.addEventListener('click', function() {
                isOpen = !isOpen;
                if (isOpen) {
                    mariaModal.classList.add('show');
                    mariaButton.classList.add('active');
                    mariaButton.style.display = 'none';
                    mariaBadge.style.display = 'none';
                    mariaInput.focus();
                } else {
                    mariaModal.classList.remove('show');
                    mariaButton.classList.remove('active');
                    mariaButton.style.display = 'flex';
                }
            });

            mariaClose.addEventListener('click', function() {
                mariaModal.classList.remove('show');
                mariaButton.classList.remove('active');
                mariaButton.style.display = 'flex';
                isOpen = false;
            });

            // Cerrar con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isOpen) {
                    mariaModal.classList.remove('show');
                    mariaButton.classList.remove('active');
                    mariaButton.style.display = 'flex';
                    isOpen = false;
                }
            });

            // Función para agregar mensaje
            function addMessage(content, isUser = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `maria-message ${isUser ? 'user' : 'assistant'}`;

                const time = new Date().toLocaleTimeString('es-PE', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Formatear contenido del asistente: escapamos todo salvo placeholders [LINK:...]
                function formatAssistantContent(text) {
                    // Recolectar placeholders y reemplazarlos por tokens
                    const links = [];
                    const tokenized = text.replace(/\[LINK:(product|category):([^\|\]]+)\|([^\]]+)\]/g, function(m, type, slug, name) {
                        const idx = links.length;
                        const href = (type === 'product') ? `product.php?product=${encodeURIComponent(slug)}` : `category.php?category=${encodeURIComponent(slug)}`;
                        links.push(`<a href="${href}" target="_blank" rel="noopener noreferrer">${escapeHtml(name)}</a>`);
                        return `@@LINK${idx}@@`;
                    });

                    // Escapar el resto del texto
                    let escaped = escapeHtml(tokenized);

                    // Dividir en líneas y detectar listas
                    const lines = escaped.split(/\r?\n/);
                    // Heurística: si muchas líneas empiezan con el emoji de producto, renderizar como lista
                    const emojiCount = lines.filter(l => l.trim().startsWith('🏷️') || l.trim().startsWith('-') || l.trim().startsWith('•')).length;
                    const useList = lines.length > 1 && emojiCount / lines.length >= 0.35;

                    function replaceInlineFormatting(s) {
                        // negrita **texto** -> <strong>
                        s = s.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
                        // cursiva *texto* -> <em>
                        s = s.replace(/\*(.+?)\*/g, '<em>$1</em>');
                        // inline code `code`
                        s = s.replace(/`(.+?)`/g, '<code>$1</code>');
                        return s;
                    }

                    let out = '';
                    if (useList) {
                        out += '<ul class="maria-list">';
                        lines.forEach(line => {
                            let t = line.trim();
                            // eliminar prefijos de lista/emoji visual en el texto mostrado
                            t = t.replace(/^[-•]\s*/, '');
                            t = t.replace(/^🏷️\s*/, '');
                            t = replaceInlineFormatting(t);
                            out += `<li>${t}</li>`;
                        });
                        out += '</ul>';
                    } else {
                        // No lista: procesar cada línea y unir con <br>
                        const processed = lines.map(line => replaceInlineFormatting(line));
                        out = processed.join('<br>');
                    }

                    // Reemplazar tokens por los enlaces HTML seguros (ya escapados)
                    out = out.replace(/@@LINK(\d+)@@/g, function(m, idx) {
                        return links[Number(idx)] || '';
                    });

                    return out;
                }

                const bubbleContent = isUser ? escapeHtml(content) : formatAssistantContent(content);

                messageDiv.innerHTML = `
                ${!isUser ? `<img src="${avatarPath}" class="message-avatar" alt="M.A.R.I.A">` : ''}
                <div class="message-content">
                    <div class="message-bubble">${bubbleContent}</div>
                    <div class="message-time">${time}</div>
                </div>
            `;

                // Remover mensaje de bienvenida
                const welcomeMsg = mariaMessages.querySelector('.welcome-message');
                if (welcomeMsg) {
                    welcomeMsg.remove();
                }

                mariaMessages.appendChild(messageDiv);
                mariaMessages.scrollTop = mariaMessages.scrollHeight;
            }

            // Función para escapar HTML
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Mostrar indicador de escritura
            function showTyping() {
                if (isTyping) return;
                isTyping = true;

                const typingDiv = document.createElement('div');
                typingDiv.className = 'maria-message assistant';
                typingDiv.id = 'typing-indicator';
                typingDiv.innerHTML = `
                <img src="${avatarPath}" class="message-avatar" alt="M.A.R.I.A">
                <div class="message-content">
                    <div class="typing-indicator" style="display: block;">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            `;

                mariaMessages.appendChild(typingDiv);
                mariaMessages.scrollTop = mariaMessages.scrollHeight;
            }

            function hideTyping() {
                const typingIndicator = document.getElementById('typing-indicator');
                if (typingIndicator) {
                    typingIndicator.remove();
                }
                isTyping = false;
            }

            // Función para enviar mensaje
            window.sendMariaMessage = async function(message) {
                if (!message.trim() || isTyping) return;

                // Detener voz si está hablando
                if (window.mariaVoice) {
                    window.mariaVoice.stopSpeaking();
                }

                // Agregar mensaje del usuario
                addMessage(message, true);
                mariaInput.value = '';
                mariaSend.disabled = true;

                // Mostrar indicador de escritura
                showTyping();

                try {
                    // Determinar URL de la API
                    const apiUrl = isAdmin ? '../maria_chat.php' : 'maria_chat.php';

                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            message: message,
                            page: currentPage,
                            product: currentProduct,
                            category: currentCategory,
                            title: currentTitle
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }

                    const data = await response.json();

                    // Ocultar indicador de escritura
                    hideTyping();

                    if (data.error) {
                        addMessage('❌ ' + data.error, false);
                    } else {
                        addMessage(data.response, false);

                        // Reproducir voz si está habilitado
                        if (window.mariaVoice && window.mariaVoice.autoSpeak) {
                            setTimeout(() => {
                                window.mariaVoice.speak(data.response);
                            }, 500);
                        }
                    }
                } catch (error) {
                    hideTyping();
                    console.error('Error M.A.R.I.A:', error);
                    addMessage('😔 Lo siento, hubo un error al procesar tu mensaje. Por favor, intenta nuevamente o contáctanos por WhatsApp al +51 945 472 993.', false);
                } finally {
                    mariaSend.disabled = false;
                    mariaInput.focus();
                }
            };

            // Event listeners para enviar mensaje
            mariaSend.addEventListener('click', function() {
                const message = mariaInput.value.trim();
                if (message) {
                    window.sendMariaMessage(message);
                }
            });

            // Usar keydown en vez de keypress para prevenir el submit de formularios
            mariaInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    // Previene comportamiento por defecto (submit) en cualquier contexto
                    e.preventDefault();
                    const message = mariaInput.value.trim();
                    if (message) {
                        window.sendMariaMessage(message);
                    }
                }
            });

            // Event listeners para preguntas rápidas
            document.querySelectorAll('.quick-question').forEach(button => {
                button.addEventListener('click', function() {
                    const question = this.getAttribute('data-question');
                    window.sendMariaMessage(question);
                });
            });

            // Mostrar badge después de 5 segundos
            setTimeout(() => {
                if (!isOpen) {
                    mariaBadge.style.display = 'flex';
                }
            }, 5000);

            // console.log('M.A.R.I.A Chat System inicializado ✅');
        }
    })();

    // ========================================
    // SISTEMA DE VOZ DE M.A.R.I.A
    // ========================================
    // ========================================
    // SISTEMA DE VOZ DE M.A.R.I.A (versión tipo C.A.M.I.L.A.)
    // ========================================
    class MariaVoiceSystem {
        constructor() {
            this.recognition = null;
            this.synthesis = window.speechSynthesis;
            this.isListening = false;
            this.isSpeaking = false;
            this.autoSpeak = localStorage.getItem('maria_auto_speak') !== 'false';
            this.selectedVoice = null;
            this.isAdmin = window.location.pathname.includes('admin');
            this.avatarPath = this.isAdmin ? '../images/maria-avatar.png' : 'images/maria-avatar.png';
            this.init();
        }

        init() {
            const hasNativeSpeech = 'speechSynthesis' in window;
            const hasExternalTTS = !!window.MARIA_TTS_BACKEND_URL;

            if (!hasNativeSpeech && !hasExternalTTS) {
                // console.warn('Tu navegador no soporta síntesis de voz y no hay backend externo configurado');
                return;
            }

            // Configurar reconocimiento (sin cambios)
            this.setupRecognition();

            // Esperar a que se carguen las voces y seleccionar la más parecida a C.A.M.I.L.A.
            // (solo aplica a la voz nativa del navegador; el backend externo trae su propia voz clonada)
            if (hasNativeSpeech) {
                window.speechSynthesis.onvoiceschanged = () => {
                    this.selectCamilaVoice();
                };
            }

            this.setupMicButton();
            this.setupMuteButton();

            // console.log('M.A.R.I.A Voice System (voz tipo C.A.M.I.L.A.) inicializado ✅');
        }

        setupRecognition() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) return;

            this.recognition = new SpeechRecognition();
            this.recognition.lang = 'es-MX';
            this.recognition.continuous = false;
            this.recognition.interimResults = false;

            this.recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                const confidence = event.results[0][0].confidence;
                // console.log('🎤 Transcrito:', transcript);

                const mariaInput = document.getElementById('mariaInput');
                if (mariaInput) {
                    mariaInput.value = transcript;
                    if (confidence > 0.7 && window.sendMariaMessage) {
                        setTimeout(() => window.sendMariaMessage(transcript), 400);
                    }
                }
            };

            this.recognition.onerror = (event) => {
                // console.error('Error de reconocimiento:', event.error);
            };
        }

        selectCamilaVoice() {
            // Esperar a que las voces se carguen realmente
            const tryLoadVoices = (attempt = 0) => {
                const voices = this.synthesis.getVoices();

                if (voices.length === 0 && attempt < 10) {
                    // Reintenta cada 250ms hasta que el navegador cargue las voces
                    return setTimeout(() => tryLoadVoices(attempt + 1), 250);
                }

                // console.log('🎙️ Voces detectadas:', voices.map(v => `${v.name} | ${v.lang}`));

                // Buscar una voz de Google femenina en español (la más natural)
                let selectedVoice = voices.find(v =>
                    v.name.toLowerCase().includes('google') &&
                    (v.lang === 'es-MX' || v.lang === 'es-ES' || v.lang.startsWith('es'))
                );

                // Si no hay voz de Google, probar con alguna que tenga nombre femenino
                if (!selectedVoice) {
                    selectedVoice = voices.find(v =>
                        v.lang.startsWith('es') &&
                        (
                            v.name.toLowerCase().includes('female') ||
                            v.name.toLowerCase().includes('sofia') ||
                            v.name.toLowerCase().includes('maria') ||
                            v.name.toLowerCase().includes('lucia') ||
                            v.name.toLowerCase().includes('carmen')
                        )
                    );
                }

                // Último recurso: primera voz en español
                if (!selectedVoice) {
                    selectedVoice = voices.find(v => v.lang.startsWith('es'));
                }

                this.selectedVoice = selectedVoice || null;

                /* console.log(
                    '🔊 Voz seleccionada:',
                    this.selectedVoice ?
                    `${this.selectedVoice.name} | ${this.selectedVoice.lang}` :
                    'ninguna (usando voz por defecto del sistema)'
                ); */
            };

            tryLoadVoices(); // Ejecutar la búsqueda de voz
        }

        setupMicButton() {
            const micButton = document.getElementById('mariaMicButton');
            if (!micButton || !('webkitSpeechRecognition' in window)) return;

            micButton.addEventListener('click', () => {
                if (this.isListening) {
                    this.recognition.stop();
                    this.isListening = false;
                    micButton.innerHTML = '<i class="fa fa-microphone"></i>';
                } else {
                    this.isListening = true;
                    micButton.innerHTML = '<i class="fa fa-stop"></i>';
                    this.recognition.start();
                }
            });
        }

        setupMuteButton() {
            const muteButton = document.getElementById('mariaMute');
            if (!muteButton) return;

            this.updateMuteButton();

            muteButton.addEventListener('click', () => {
                this.autoSpeak = !this.autoSpeak;
                localStorage.setItem('maria_auto_speak', this.autoSpeak);
                this.updateMuteButton();
                if (!this.autoSpeak) this.stopSpeaking();
            });
        }

        updateMuteButton() {
            const muteButton = document.getElementById('mariaMute');
            if (!muteButton) return;

            const icon = muteButton.querySelector('i');
            icon.className = this.autoSpeak ? 'fa fa-volume-up' : 'fa fa-volume-off';
            muteButton.title = this.autoSpeak ? 'Silenciar voz' : 'Activar voz';
        }

        speak(text) {
            if (!this.autoSpeak) return;

            const cleanText = text
                .replace(/[^\w\sáéíóúñÁÉÍÓÚÑ.,!?¡¿]/g, '')
                .replace(/\s{2,}/g, ' ')
                .trim();

            if (!cleanText) return;

            // Si hay un backend de voz externo configurado (Colab/XTTS con clonación de voz),
            // usarlo en vez de la voz del navegador — mejor calidad y sin el ruido raro
            // que genera speechSynthesis con palabras cortas.
            if (window.MARIA_TTS_BACKEND_URL) {
                this.speakViaExternalTTS(cleanText);
                return;
            }

            this.speakViaBrowser(cleanText);
        }

        async speakViaExternalTTS(cleanText) {
            try {
                this.stopSpeaking();
                const response = await fetch(window.MARIA_TTS_BACKEND_URL.replace(/\/$/, '') + '/tts', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ texto: cleanText })
                });
                const data = await response.json();
                if (!data.audio_b64) {
                    throw new Error('Sin audio en la respuesta del backend de voz');
                }
                const audio = new Audio('data:audio/wav;base64,' + data.audio_b64);
                this.currentExternalAudio = audio;
                audio.play();
            }
            catch (err) {
                console.warn('⚠️ Backend de voz externo (Colab) no disponible, usando voz del navegador:', err);
                this.speakViaBrowser(cleanText);
            }
        }

        speakViaBrowser(cleanText) {
            if (!this.synthesis) return;

            this.synthesis.cancel(); // cancelar anterior

            const utterance = new SpeechSynthesisUtterance(cleanText);
            if (this.selectedVoice && this.selectedVoice.name.toLowerCase().includes('male')) {
                // console.warn('⚠️ La voz detectada es masculina, intentando usar voz femenina alternativa...');
                const femaleFallback = this.synthesis.getVoices().find(v =>
                    v.lang.startsWith('es') &&
                    (
                        v.name.toLowerCase().includes('female') ||
                        v.name.toLowerCase().includes('sofia') ||
                        v.name.toLowerCase().includes('maria') ||
                        v.name.toLowerCase().includes('lucia')
                    )
                );
                if (femaleFallback) this.selectedVoice = femaleFallback;
            }

            utterance.voice = this.selectedVoice;
            utterance.lang = 'es-MX'; // Acento mexicano natural
            utterance.pitch = 1.2; // Voz más fina y cálida
            utterance.rate = 0.9; // Más pausada
            utterance.volume = 1; // Completa

            utterance.onstart = () => {
                this.isSpeaking = true;
            };
            utterance.onend = () => {
                this.isSpeaking = false;
            };

            this.synthesis.speak(utterance);
        }

        stopSpeaking() {
            if (this.synthesis) this.synthesis.cancel();
            if (this.currentExternalAudio) {
                this.currentExternalAudio.pause();
                this.currentExternalAudio = null;
            }
            this.isSpeaking = false;
        }
    }
    // Inicializar M.A.R.I.A Voice
    // Backend de voz externo (Colab/XTTS con clonación de voz), configurado desde el admin.
    // Vacío = usar la voz nativa del navegador como respaldo.
    window.MARIA_TTS_BACKEND_URL = <?php echo json_encode($settings['assistant_tts_backend_url'] ?? ''); ?> || null;

    window.mariaVoice = null;
    setTimeout(() => {
        const supportsSpeech = ('speechSynthesis' in window) || !!window.MARIA_TTS_BACKEND_URL;
        const supportsRecog = ('SpeechRecognition' in window) || ('webkitSpeechRecognition' in window);
        const isSecure = (location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1');
        if (supportsSpeech || supportsRecog) {
            if (!isSecure) {
                /* console.warn('M.A.R.I.A: Voice features require secure context (HTTPS)...'); */
            }
            try {
                window.mariaVoice = new MariaVoiceSystem();
            } catch (e) {
                // console.error('Error al inicializar MariaVoice:', e);
            }
        } else {
            // console.warn('M.A.R.I.A: No hay soporte para síntesis/recogida de voz en este navegador/host.');
        }
    }, 1500);
</script>

<!-- Estilos adicionales para animaciones -->
<style>
    /* Estilos del modal */
    .maria-modal {
        position: fixed;
        bottom: 120px;
        left: 24px;
        width: 320px;
        height: 460px;
        max-height: min(70vh, 520px);
        background: var(--bg-main, rgba(255, 255, 255, 0.82));
        color: var(--text-main, #1c231f);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid var(--border-glass, rgba(255, 255, 255, 0.4));
        border-radius: 16px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);
        display: none;
        flex-direction: column;
        z-index: 9998;
        overflow: hidden;
        box-sizing: border-box;
    }

    /* Mostrar modal cuando tenga la clase .show */
    .maria-modal.show {
        display: flex !important;
    }

    /* Contenedor de mensajes: permitir crecimiento y scroll */
    .maria-messages {
        flex: 1 1 auto;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        /* desplazamiento suave en iOS */
        padding: 12px;
        background: transparent;
    }

    /* Ajustes para el área de entrada para no pisar el contenido */
    .maria-input-area {
        display: flex;
        align-items: center;
        padding: 10px;
        gap: 8px;
        border-top: 1px solid var(--border-glass, rgba(255,255,255,0.4));
        background: var(--bg-main, rgba(255, 255, 255, 0.65));
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
    }

    /* Botón de silenciar mejorado */
    .maria-mute {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        color: #495057;
        padding: 8px;
        margin-right: 8px;
        cursor: pointer;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .maria-mute:hover {
        background: #e9ecef;
        color: #212529;
        transform: scale(1.1);
    }

    .maria-mute i {
        font-size: 16px;
    }

    /* Ajustes responsive */
    @media (max-width: 480px) {
        .maria-modal {
            width: 92%;
            height: 60vh;
            max-height: 80vh;
            bottom: 70px;
            right: 4%;
            left: 4%;
            border-radius: 12px;
        }

        .maria-messages {
            /* En pantallas pequeñas, asegurar espacio para encabezado y input */
            max-height: calc(60vh - 120px);
        }
    }

    /* En pantallas de escritorio y portátiles reducir la altura para evitar que el modal sea demasiado largo */
    @media (min-width: 769px) {
        .maria-modal {
            height: 430px;
            /* altura ajustada a 430px en desktop/laptop */
            max-height: calc(100vh - 160px);
        }

        .maria-messages {
            /* Reservar espacio para header y área de input: ajustar para nueva altura */
            max-height: calc(430px - 120px);
        }
    }

    @keyframes slideInRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    /* Estilos para M.A.R.I.A */
    .maria-button {
        position: fixed;
        bottom: 90px;
        left: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--bg-main, rgba(255, 255, 255, 0.75));
        -webkit-backdrop-filter: blur(14px) saturate(180%);
        backdrop-filter: blur(14px) saturate(180%);
        border: 1px solid var(--border-glass, rgba(255,255,255,0.4));
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        z-index: 9999;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .maria-button img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
    }

    .maria-mute {
        background: transparent;
        border: none;
        color: #ffffffff;
        padding: 8px;
        margin-right: 5px;
        cursor: pointer;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .maria-mute:hover {
        background: #eee;
        color: #333;
    }

    .typing-indicator {
        display: block !important;
    }

    /* Asegurar que el botón de M.A.R.I.A sea visible */
    .maria-button {
        pointer-events: auto !important;
    }

    /* Animación para el avatar cuando está hablando */
    .maria-header {
    background: linear-gradient(135deg, rgba(15,81,50,0.9) 0%, rgba(11,61,46,0.9) 100%);
    -webkit-backdrop-filter: blur(12px) saturate(160%);
    backdrop-filter: blur(12px) saturate(160%);
    color: white;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 16px 16px 0 0;
    flex-shrink: 0;
}

.maria-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.maria-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 2px solid #e0ac2b;
    object-fit: cover;
}

.maria-name {
    margin: 0;
    font-weight: 700;
    font-size: 15px;
}

.maria-online {
    margin: 0;
    font-size: 12px;
    opacity: .85;
}

.maria-avatar.speaking {
    animation: avatar-speak 1s infinite;
}

/* ========================================== */
/* BADGE de notificación (recuperado, faltaba) */
/* ========================================== */
.maria-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff3b30;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    border: 2px solid white;
    animation: maria-badge-pulse 2s infinite;
}
@keyframes maria-badge-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* ========================================== */
/* Botón enviar mensaje (recuperado, faltaba) */
/* ========================================== */
.maria-send {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #0f5132 0%, #0b3d2e 100%);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    font-size: 18px;
    flex-shrink: 0;
}
.maria-send:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(15, 81, 50, 0.4);
}
.maria-send:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ========================================== */
/* Botón de micrófono / voz (recuperado, faltaba) */
/* ========================================== */
.maria-mic-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: #e0ac2b;
    color: #2b1e00;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    font-size: 18px;
    margin-right: 10px;
    flex-shrink: 0;
}
.maria-mic-button:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(224, 172, 43, 0.4);
}
.maria-mic-button.listening {
    background: #dc3545;
    color: #fff;
    animation: maria-pulse-mic 1.5s infinite;
}
@keyframes maria-pulse-mic {
    0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
    50% { box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
}

/* ========================================== */
/* Mensaje de bienvenida y preguntas rápidas (recuperado) */
/* ========================================== */
.welcome-message {
    text-align: center;
    padding: 15px;
    color: var(--text-main, #333);
}
.welcome-message img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin-bottom: 12px;
    border: 3px solid #e0ac2b;
}
.welcome-message h3 {
    margin: 0 0 8px 0;
    color: #c9971f !important;
    font-size: 18px;
}
.welcome-message p {
    margin: 4px 0;
    font-size: 13px;
    opacity: 0.8;
}
.quick-questions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 12px;
}
.quick-question {
    padding: 10px 15px;
    background: rgba(15, 81, 50, 0.08);
    border: 1px solid rgba(15, 81, 50, 0.25);
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 12px;
    text-align: left;
    color: var(--text-main, #333);
}
.quick-question:hover {
    background: rgba(224, 172, 43, 0.18);
    transform: translateX(5px);
}

/* ========================================== */
/* Punto "en línea" y botón cerrar (recuperado) */
/* ========================================== */
.status-dot {
    width: 8px;
    height: 8px;
    background: #4cd964;
    border-radius: 50%;
    animation: maria-blink 2s infinite;
    display: inline-block;
}
@keyframes maria-blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.maria-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    font-size: 20px;
}
.maria-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* ========================================== */
/* Mensajes del chat (recuperado) */
/* ========================================== */
.maria-message {
    display: flex;
    gap: 10px;
    animation: maria-message-appear 0.3s ease;
}
@keyframes maria-message-appear {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.message-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    flex-shrink: 0;
    object-fit: cover;
}

.message-content {
    max-width: 75%;
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    margin-bottom: 4px;
    word-wrap: break-word;
    word-break: break-word;
    line-height: 1.4;
}

.maria-message.assistant .message-bubble {
    background: linear-gradient(135deg, #0f5132 0%, #0b3d2e 100%);
    color: white;
    border-bottom-left-radius: 4px;
}

.maria-message.user {
    flex-direction: row-reverse;
}
.maria-message.user .message-content {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}
.maria-message.user .message-bubble {
    background: #e0ac2b;
    color: #2b1e00;
    border-bottom-right-radius: 4px;
}

.message-time {
    font-size: 11px;
    opacity: 0.6;
    padding: 0 8px;
    color: var(--text-main, #333);
}

/* ========================================== */
/* Campo de texto del chat (recuperado) */
/* ========================================== */
.maria-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #e5e5ea;
    border-radius: 20px;
    background: var(--bg-main, #fff);
    color: var(--text-main, #333);
    outline: none;
    font-size: 14px;
    transition: all 0.3s;
}
[data-theme="dark"] .maria-input {
    background: #2d2d2d;
    border-color: #3d3d3d;
}
.maria-input:focus {
    border-color: #0f5132;
    box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.15);
}

/* ========================================== */
/* Indicador "escuchando" del micrófono (recuperado) */
/* ========================================== */
.listening-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
    font-size: 12px;
    color: var(--text-main, #333);
}
.listening-animation {
    display: flex;
    gap: 3px;
}
.listening-animation span {
    width: 4px;
    height: 16px;
    background: #e0ac2b;
    border-radius: 2px;
    animation: maria-listening-wave 1s infinite ease-in-out;
}
.listening-animation span:nth-child(1) { animation-delay: 0s; }
.listening-animation span:nth-child(2) { animation-delay: 0.15s; }
.listening-animation span:nth-child(3) { animation-delay: 0.3s; }
@keyframes maria-listening-wave {
    0%, 100% { transform: scaleY(0.4); }
    50% { transform: scaleY(1); }
}

    @keyframes avatar-speak {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }
</style>