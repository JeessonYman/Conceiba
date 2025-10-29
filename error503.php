<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<head>
<style>
/* Animaciones y efectos */
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(-4deg); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes fadeInOut {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}

.error-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    overflow: hidden;
}

/* Fondo con efecto */
.error-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 166, 90, 0.05) 0%, rgba(0, 166, 90, 0.15) 100%);
    z-index: 0;
}

body.dark-mode .error-container::before {
    background: linear-gradient(135deg, rgba(0, 166, 90, 0.1) 0%, rgba(0, 166, 90, 0.25) 100%);
}

/* Partículas decorativas */
.particle {
    position: absolute;
    background: rgba(0, 166, 90, 0.2);
    border-radius: 50%;
    pointer-events: none;
    z-index: 1;
}

.particle1 { width: 78px; height: 78px; top: 16%; right: 12%; animation: float 7.3s infinite ease-in-out; }
.particle2 { width: 92px; height: 92px; top: 64%; left: 10%; animation: float 6.7s infinite ease-in-out 1s; }
.particle3 { width: 58px; height: 58px; top: 40%; left: 15%; animation: float 8.2s infinite ease-in-out 2s; }
.particle4 { width: 98px; height: 98px; bottom: 20%; right: 18%; animation: float 9.1s infinite ease-in-out 1.5s; }

.error-content {
    text-align: center;
    position: relative;
    z-index: 2;
    max-width: 600px;
    width: 100%;
}

.error-box {
    background: rgba(42, 37, 40, 0.8);
    border-radius: 20px;
    padding: 40px 30px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 2px solid rgba(0, 166, 90, 0.2);
    transition: all 0.3s ease;
}

body.dark-mode .error-box {
    background: rgba(44, 62, 80, 0.95);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.error-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
}

.error-code {
    font-size: 72px;
    font-weight: bold;
    color: #00a65a;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    animation: pulse 2s infinite;
}

.error-title {
    font-size: 28px;
    color: #333;
    margin: 15px 0;
    font-weight: 600;
}

body.dark-mode .error-title {
    color: #ecf0f1;
}

.error-message {
    font-size: 16px;
    color: #666;
    margin: 20px 0;
    line-height: 1.6;
}

body.dark-mode .error-message {
    color: #bdc3c7;
}

.panda-image {
    max-width: 60%;
    height: auto;
    margin: 20px auto;
    animation: float 4s infinite ease-in-out;
    filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
    transition: transform 0.3s ease;
}

.panda-image:hover {
    transform: scale(1.1) rotate(4deg);
}

.btn-back {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 30px;
    background: #00a65a;
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 166, 90, 0.3);
}

.btn-back:hover {
    background: #008d4c;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 166, 90, 0.4);
    color: white;
    text-decoration: none;
}

.btn-back i {
    margin-right: 8px;
}

.tools-icon {
    font-size: 48px;
    color: #00a65a;
    opacity: 0.3;
    position: absolute;
    animation: fadeInOut 3s infinite ease-in-out;
}

.tools-icon.tools1 { top: 12%; left: 14%; }
.tools-icon.tools2 { bottom: 14%; right: 16%; animation-delay: 1.5s; }

.maintenance-badge {
    display: inline-block;
    padding: 8px 16px;
    background: rgba(0, 166, 90, 0.1);
    border: 1px solid rgba(0, 166, 90, 0.3);
    border-radius: 20px;
    color: #00a65a;
    font-size: 14px;
    font-weight: 600;
    margin-top: 10px;
}

body.dark-mode .maintenance-badge {
    background: rgba(0, 166, 90, 0.2);
    color: #5cb85c;
}

/* Responsive */
@media (max-width: 768px) {
    .error-code { font-size: 56px; }
    .error-title { font-size: 22px; }
    .error-message { font-size: 14px; }
    .error-box { padding: 30px 20px; }
    .panda-image { max-width: 70%; }
    .particle { display: none; }
    .tools-icon { font-size: 36px; }
}

@media (max-width: 480px) {
    .error-code { font-size: 48px; }
    .error-title { font-size: 20px; }
    .panda-image { max-width: 80%; }
    .btn-back { padding: 10px 20px; font-size: 14px; }
}

@media (max-height: 500px) and (orientation: landscape) {
    .error-container { min-height: auto; padding: 10px; }
    .error-box { padding: 20px 15px; }
    .panda-image { max-width: 40%; }
    .error-code { font-size: 36px; }
}
</style>
</head>
<body class="hold-transition skin-green layout-top-nav">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
     
    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="error-container">
                            <!-- Íconos decorativos -->
                            <i class="fa fa-wrench tools-icon tools1"></i>
                            <i class="fa fa-cog tools-icon tools2"></i>
                            
                            <!-- Partículas decorativas -->
                            <div class="particle particle1"></div>
                            <div class="particle particle2"></div>
                            <div class="particle particle3"></div>
                            <div class="particle particle4"></div>
                            
                            <div class="error-content">
                                <div class="error-box">
                                    <h1 class="error-code">503</h1>
                                    <h2 class="error-title">Servicio No Disponible</h2>
                                    
                                    <img src="images/panda503.png" alt="Error 503" class="panda-image">
                                    
                                    <div class="maintenance-badge">
                                        <i class="fa fa-wrench"></i> En Mantenimiento
                                    </div>
                                    
                                    <p class="error-message">
                                        El servicio está temporalmente no disponible. Estamos realizando tareas de mantenimiento 
                                        o el servidor está sobrecargado. Por favor, intenta nuevamente en unos minutos.
                                    </p>
                                    
                                    <a href="javascript:location.reload()" class="btn-back" style="margin-right: 10px;">
                                        <i class="fa fa-refresh"></i> Reintentar
                                    </a>
                                    <a href="index.php" class="btn-back">
                                        <i class="fa fa-home"></i> Volver al Inicio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-3">
                        <?php include 'includes/sidebar.php'; ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
  
    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>