<!-- Ingreso Modal -->
<div class="modal fade" id="inputsdetailss" style="border-radius:20px;">
  <div class="modal-dialog" style="border-radius:20px;">
      <div class="modal-content" style="border-radius:20px;">
          <div class="modal-header" style="border-radius:20px;">
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" style='border-radius:10px;'>
              <b>Detalles completos del ingreso</b>
            </h4>
          </div>
          <div class="modal-body" style='border-radius:10px;'>
            <p>Date: 
              <span id="date"></span>
              <span class="float-end">Código#: <span id="inputsid">
              </span></span> 
            </p>
            <table class="table table-bordered" style="border-radius:20px;">
              <thead>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
              </thead>
              <tbody id="detail" style='border-radius:10px;'>
                <tr>
                  <td colspan="3" align="right" style='border-radius:10px;'><b>Total</b></td>
                  <td><span id="total"></span></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer" style='border-radius:20px;'>
            <button type="button" class="btn btn-default btn-flat float-start" style='border-radius:20px;' data-bs-dismiss="modal">
              <i class="fa fa-close"></i> Close
            </button>
          </div>
      </div>
  </div>
</div>
