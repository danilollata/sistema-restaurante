<?php
session_start();
if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
  require_once "../conexion.php";
  $id_user = $_SESSION['idUser'];
  $query = mysqli_query($conexion, "SELECT p.*, s.nombre AS sala, u.nombre FROM pedidos p INNER JOIN salas s ON p.id_sala = s.id INNER JOIN usuarios u ON p.id_usuario = u.id");
  include_once "includes/header.php";
?>

  <div class="card">
    <div class="card-header">
      Historial pedidos
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped" id="tbl">
          <thead>
            <tr>
              <th>#</th>
              <th>Sala</th>
              <th>Mesa</th>
              <th>Fecha</th>
              <th>Total</th>
              <th>Usuario</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php while ($row = mysqli_fetch_assoc($query)) {
            if ($row['estado'] == 'PENDIENTE') {
              $estado = '<span class="badge badge-danger">Pendiente</span>';
              $boton = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTarjeta" data-id="' . $row['id'] . '">PAGAR</button>';
            } else {
              $estado = '<span class="badge badge-success">Completado</span>';
              $boton = ''; 
            }
          ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['sala']; ?></td>
              <td><?php echo $row['num_mesa']; ?></td>
              <td><?php echo $row['fecha']; ?></td>
              <td><?php echo $row['total']; ?></td>
              <td><?php echo $row['nombre']; ?></td>
              <td>
                <?php echo $estado; ?>
                <?php echo $boton; ?>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal de Pago con Stripe -->
  <div class="modal fade" id="modalTarjeta" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Agregar Tarjeta de Crédito</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="procesar_pago.php" method="POST" id="payment-form">
          <div class="modal-body">
            <input type="hidden" id="pedidoId" name="pedidoId">
            <input type="hidden" name="total" id="total" value="">

            <div class="form-group">
              <label for="card-element">Número de Tarjeta</label>
              <div id="card-element"></div> <!-- Stripe.js inserta aquí el campo de la tarjeta -->
            </div>

            <div id="card-errors" role="alert"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Procesar Pago</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
  include_once "includes/footer.php";
} else {
  header('Location: permisos.php');
}
?>


<script src="https://js.stripe.com/v3/"></script>
<script>
  var stripe = Stripe('pk_test_51Q2K3C2KRnMXszkB0FtP0gAxiTes5eC51glLT43QGRozfsfCDQPhpDN4HX0FUAVhszvFJGlK0kIgUP8XVHwMJOUf007XKhEovz');
  var elements = stripe.elements();
  var card = elements.create('card');
  card.mount('#card-element');

  card.on('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
      displayError.textContent = event.error.message;
    } else {
      displayError.textContent = '';
    }
  });

  var form = document.getElementById('payment-form');
  form.addEventListener('submit', function(event) {
    event.preventDefault();

    stripe.createToken(card).then(function(result) {
      if (result.error) {
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = result.error.message;
      } else {
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', result.token.id);
        form.appendChild(hiddenInput);

        form.submit();

        reiniciarFormularioPago();
      }
    });
  });

  function reiniciarFormularioPago() {
    card.clear(); 
    form.reset(); 
  }


  $('#modalTarjeta').on('hidden.bs.modal', function() {
    card.destroy(); 
    card = elements.create('card'); 
    card.mount('#card-element'); 
  });


  $('#modalTarjeta').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var pedidoId = button.data('id');
    var total = button.closest('tr').find('td:nth-child(5)').text();

    var modal = $(this);
    modal.find('.modal-body #pedidoId').val(pedidoId);
    modal.find('.modal-body #total').val(total * 100); 
  });
</script>
