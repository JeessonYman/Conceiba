<div id="maria-widget-container">
    <!-- Chat Window -->
    <div id="maria-chat-window" class="maria-window hidden">
        <div class="maria-header">
            <div class="maria-header-info">
                <img src="../images/maria-avatar.png" alt="AI" onerror="this.src='../images/profile.jpg'">
                <div>
                    <span class="maria-title">M.A.R.I.A.</span>
                    <span class="maria-status">Asistente Virtual</span>
                </div>
            </div>
            <div class="maria-header-actions">
                <i class="fa fa-volume-up" id="maria-mute-btn" onclick="toggleMariaMute()" title="Silenciar voz"></i>
                <i class="fa fa-minus" onclick="toggleMariaWidget()" title="Minimizar"></i>
            </div>
        </div>

        <div class="maria-body">
            <div class="maria-messages" id="maria-global-messages">
                <div class="maria-msg maria-msg-bot">
                    ¡Hola! Soy M.A.R.I.A. ¿En qué puedo ayudarte hoy?
                </div>
            </div>
        </div>

        <div class="maria-footer">
            <div class="maria-input-wrapper">
                <input type="text" id="maria-global-input" placeholder="Escribe un mensaje..." onkeypress="handleMariaInput(event)">
                <button onclick="startMariaVoice()" id="maria-mic-btn" title="Hablar"><i class="fa fa-microphone"></i></button>
                <button onclick="sendMariaGlobalMessage()" title="Enviar"><i class="fa fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <!-- Floating Bubble -->
    <div id="maria-bubble" class="maria-bubble" onclick="toggleMariaWidget()">
        <img src="../images/maria-avatar.png" alt="AI" onerror="this.src='../images/profile.jpg'">
        <div class="maria-notification-dot"></div>
    </div>
</div>

<style>
    /* Container positioning */
    #maria-widget-container {
        position: fixed;
        bottom: 90px;
        right: 20px;
        z-index: 9999;
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    /* Floating Bubble */
    .maria-bubble {
        width: 60px;
        height: 60px;
        background: #00a65a;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        z-index: 10000;
    }

    .maria-bubble:hover {
        transform: scale(1.1);
    }

    .maria-bubble img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
    }

    .maria-notification-dot {
        position: absolute;
        top: 0;
        right: 0;
        width: 14px;
        height: 14px;
        background: #dd4b39;
        border-radius: 50%;
        border: 2px solid #fff;
        display: none;
    }

    /* Chat Window */
    .maria-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 350px;
        height: 350px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.3s ease;
        transform-origin: bottom right;
        opacity: 1;
        transform: scale(1);
        z-index: 9999;
    }

    .maria-window.hidden {
        opacity: 0;
        transform: scale(0);
        pointer-events: none;
    }

    /* Header */
    .maria-header {
        background: #00a65a;
        color: #fff;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .maria-header-info {
        display: flex;
        align-items: center;
    }

    .maria-header-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .maria-title {
        display: block;
        font-weight: bold;
        font-size: 16px;
    }

    .maria-status {
        display: block;
        font-size: 12px;
        opacity: 0.9;
    }

    .maria-header-actions i {
        cursor: pointer;
        font-size: 18px;
        opacity: 0.8;
        transition: opacity 0.2s;
        margin-left: 10px;
    }

    .maria-header-actions i:hover {
        opacity: 1;
    }

    /* Body */
    .maria-body {
        flex-grow: 1;
        background: #f5f7fa;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    /* Messages */
    .maria-msg {
        max-width: 80%;
        margin-bottom: 12px;
        padding: 10px 14px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
        word-wrap: break-word;
    }

    .maria-msg-bot {
        background: #fff;
        color: #333;
        border-bottom-left-radius: 4px;
        align-self: flex-start;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .maria-msg-user {
        background: #00a65a;
        color: #fff;
        border-bottom-right-radius: 4px;
        align-self: flex-end;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Footer / Input */
    .maria-footer {
        padding: 10px;
        background: #fff;
        border-top: 1px solid #eee;
    }

    .maria-input-wrapper {
        display: flex;
        align-items: center;
        background: #f0f2f5;
        border-radius: 20px;
        padding: 5px 15px;
    }

    .maria-input-wrapper input {
        flex-grow: 1;
        border: none;
        background: transparent;
        padding: 10px 5px;
        outline: none;
        font-size: 14px;
    }

    .maria-input-wrapper button {
        background: none;
        border: none;
        color: #00a65a;
        font-size: 18px;
        cursor: pointer;
        padding: 5px;
        transition: color 0.2s;
    }

    .maria-input-wrapper button:hover {
        color: #008d4c;
    }

    #maria-mic-btn.listening {
        color: #dd4b39;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Scrollbar */
    .maria-body::-webkit-scrollbar {
        width: 6px;
    }

    .maria-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .maria-body::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 3px;
    }

    /* DARK MODE STYLES */
    [data-theme="dark"] .maria-window {
        background: #222d32;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
    }

    [data-theme="dark"] .maria-header {
        background: #1a2226;
        border-bottom: 1px solid #4b646f;
    }

    [data-theme="dark"] .maria-body {
        background: #2c3b41;
    }

    [data-theme="dark"] .maria-msg-bot {
        background: #1a2226;
        color: #b8c7ce;
        box-shadow: none;
        border: 1px solid #4b646f;
    }

    [data-theme="dark"] .maria-footer {
        background: #222d32;
        border-top: 1px solid #4b646f;
        transform-origin: bottom right;
        opacity: 1;
        transform: scale(1);
        z-index: 9999;
    }

    .maria-window.hidden {
        opacity: 0;
        transform: scale(0);
        pointer-events: none;
    }

    /* Header */
    .maria-header {
        background: #00a65a;
        color: #fff;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .maria-header-info {
        display: flex;
        align-items: center;
    }

    .maria-header-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .maria-title {
        display: block;
        font-weight: bold;
        font-size: 16px;
    }

    .maria-status {
        display: block;
        font-size: 12px;
        opacity: 0.9;
    }

    .maria-header-actions i {
        cursor: pointer;
        font-size: 18px;
        opacity: 0.8;
        transition: opacity 0.2s;
        margin-left: 10px;
    }

    .maria-header-actions i:hover {
        opacity: 1;
    }

    /* Body */
    .maria-body {
        flex-grow: 1;
        background: #f5f7fa;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    /* Messages */
    .maria-msg {
        max-width: 80%;
        margin-bottom: 12px;
        padding: 10px 14px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
        word-wrap: break-word;
    }

    .maria-msg-bot {
        background: #fff;
        color: #333;
        border-bottom-left-radius: 4px;
        align-self: flex-start;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .maria-msg-user {
        background: #00a65a;
        color: #fff;
        border-bottom-right-radius: 4px;
        align-self: flex-end;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Footer / Input */
    .maria-footer {
        padding: 10px;
        background: #fff;
        border-top: 1px solid #eee;
    }

    .maria-input-wrapper {
        display: flex;
        align-items: center;
        background: #f0f2f5;
        border-radius: 20px;
        padding: 5px 15px;
    }

    .maria-input-wrapper input {
        flex-grow: 1;
        border: none;
        background: transparent;
        padding: 10px 5px;
        outline: none;
        font-size: 14px;
    }

    .maria-input-wrapper button {
        background: none;
        border: none;
        color: #00a65a;
        font-size: 18px;
        cursor: pointer;
        padding: 5px;
        transition: color 0.2s;
    }

    .maria-input-wrapper button:hover {
        color: #008d4c;
    }

    #maria-mic-btn.listening {
        color: #dd4b39;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Scrollbar */
    .maria-body::-webkit-scrollbar {
        width: 6px;
    }

    .maria-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .maria-body::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 3px;
    }

    /* DARK MODE STYLES */
    [data-theme="dark"] .maria-window {
        background: #222d32;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
    }

    [data-theme="dark"] .maria-header {
        background: #1a2226;
        border-bottom: 1px solid #4b646f;
    }

    [data-theme="dark"] .maria-body {
        background: #2c3b41;
    }

    [data-theme="dark"] .maria-msg-bot {
        background: #1a2226;
        color: #b8c7ce;
        box-shadow: none;
        border: 1px solid #4b646f;
    }

    [data-theme="dark"] .maria-footer {
        background: #222d32;
        border-top: 1px solid #4b646f;
    }

    [data-theme="dark"] .maria-input-wrapper {
        background: #1a2226;
    }

    [data-theme="dark"] .maria-input-wrapper input {
        color: #fff;
    }

    [data-theme="dark"] .maria-input-wrapper input::placeholder {
        color: #6c7b83;
    }
</style>

<script>
    let mariaVoiceEnabled = true;
    let recognition;

    function toggleMariaWidget() {
        const window = document.getElementById('maria-chat-window');
        if (window.classList.contains('hidden')) {
            window.classList.remove('hidden');
        } else {
            window.classList.add('hidden');
        }
    }

    function toggleMariaMute() {
        mariaVoiceEnabled = !mariaVoiceEnabled;
        const btn = document.getElementById('maria-mute-btn');
        if (mariaVoiceEnabled) {
            btn.classList.remove('fa-volume-off');
            btn.classList.add('fa-volume-up');
            window.speechSynthesis.cancel();
        } else {
            btn.classList.remove('fa-volume-up');
            btn.classList.add('fa-volume-off');
            window.speechSynthesis.cancel();
        }
    }

    function speakMaria(text) {
        if (!mariaVoiceEnabled) return;
        const cleanText = text.replace(/[*#_`]/g, '');
        const utterance = new SpeechSynthesisUtterance(cleanText);
        utterance.lang = 'es-ES';

        // Wait for voices to be loaded
        let voices = window.speechSynthesis.getVoices();
        if (voices.length === 0) {
            window.speechSynthesis.onvoiceschanged = () => {
                voices = window.speechSynthesis.getVoices();
                setVoiceAndSpeak(utterance, voices);
            };
        } else {
            setVoiceAndSpeak(utterance, voices);
        }
    }

    function setVoiceAndSpeak(utterance, voices) {
        // Log available voices for debugging
        console.log("Voces disponibles:", voices.map(v => v.name));

        // Priority list for voices
        const preferredVoice = voices.find(v =>
            v.name.includes('Google español') ||
            v.name.includes('Microsoft Sabina') ||
            v.name.includes('Microsoft Helena') ||
            v.name.includes('Paulina') ||
            (v.lang === 'es-US' && v.name.includes('Google')) ||
            (v.lang === 'es-419' && v.name.includes('Google'))
        );

        // Fallback
        const fallbackVoice = voices.find(v => v.lang.includes('es'));

        if (preferredVoice) {
            console.log("Voz seleccionada:", preferredVoice.name);
            utterance.voice = preferredVoice;
        } else if (fallbackVoice) {
            console.log("Voz fallback:", fallbackVoice.name);
            utterance.voice = fallbackVoice;
        }

        window.speechSynthesis.speak(utterance);
    }

    function startMariaVoice() {
        if (!('webkitSpeechRecognition' in window)) {
            alert('Tu navegador no soporta reconocimiento de voz.');
            return;
        }

        if (!recognition) {
            recognition = new webkitSpeechRecognition();
            recognition.lang = 'es-ES';
            recognition.continuous = false;
            recognition.interimResults = false;

            recognition.onstart = function() {
                document.getElementById('maria-mic-btn').classList.add('listening');
                document.getElementById('maria-global-input').placeholder = "Escuchando...";
            };

            recognition.onend = function() {
                document.getElementById('maria-mic-btn').classList.remove('listening');
                document.getElementById('maria-global-input').placeholder = "Escribe un mensaje...";
            };

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                document.getElementById('maria-global-input').value = transcript;
            };
        }
        recognition.start();
    }

    function handleMariaInput(e) {
        if (e.key === 'Enter') {
            sendMariaGlobalMessage();
        }
    }

    function sendMariaGlobalMessage() {
        const input = document.getElementById('maria-global-input');
        const message = input.value.trim();
        if (!message) return;

        addMariaMessage(message, 'user');
        input.value = '';

        fetch('maria_chat_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.reply) {
                    addMariaMessage(data.reply, 'bot');
                    speakMaria(data.reply);
                } else {
                    addMariaMessage('Lo siento, hubo un error al procesar tu mensaje.', 'bot');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                addMariaMessage('Error de conexión con M.A.R.Í.A.', 'bot');
            });
    }

    function addMariaMessage(text, sender) {
        const container = document.getElementById('maria-global-messages');
        const div = document.createElement('div');
        div.classList.add('maria-msg');
        div.classList.add(sender === 'user' ? 'maria-msg-user' : 'maria-msg-bot');
        div.textContent = text;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    window.askMaria = function(context, data) {
        const window = document.getElementById('maria-chat-window');
        if (window.classList.contains('hidden')) {
            toggleMariaWidget();
        }

        const prompt = `Analiza estos datos de ${context}: ${JSON.stringify(data)}. ¿Qué conclusiones puedes sacar?`;
        addMariaMessage(`Analizando datos de ${context}...`, 'user');

        fetch('maria_chat_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: prompt,
                    context: context
                })
            })
            .then(r => r.json())
            .then(d => {
                addMariaMessage(d.reply, 'bot');
                speakMaria(d.reply);
            });
    };

    window.explainTable = function(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        let data = [];
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText);
        const rows = Array.from(table.querySelectorAll('tbody tr')).slice(0, 5);

        rows.forEach(tr => {
            let rowData = {};
            Array.from(tr.querySelectorAll('td')).forEach((td, i) => {
                if (headers[i]) rowData[headers[i]] = td.innerText;
            });
            data.push(rowData);
        });

        askMaria('Tabla de Datos', data);
    };
</script>