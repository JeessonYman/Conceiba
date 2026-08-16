<footer class="main-footer">
  <div class="container-fluid px-4 d-flex flex-wrap justify-content-between align-items-center gap-1">
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="https://www.facebook.com/conceiba.es" target="blank"><?php echo htmlspecialchars($settings['store_name'] ?? 'Conceiba'); ?></a></strong>
    <span>Todos los derechos reservados</span>
  </div>
  <?php include __DIR__ . '/maria_widget.php'; ?>
  <?php include __DIR__ . '/profile_modal.php'; ?>
</footer>