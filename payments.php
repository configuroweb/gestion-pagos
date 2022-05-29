<?php include 'db_connect.php'; ?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">

			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Pagps </b>
						<span class="float:right"><a class="btn btn-primary col-md-1 col-sm-6 float-right" href="javascript:void(0)" id="new_payment">
								<i class="fa fa-plus"></i> Pago
							</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Fecha</th>
									<th class="">ID No.</th>
									<th class="">No Curso</th>
									<th class="">Nombre</th>
									<th class="">Monto Pagado</th>
									<th class="text-center">Acción</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$payments = $conn->query("SELECT p.*,s.name as sname, ef.ef_no,s.id_no FROM payments p inner join student_ef_list ef on ef.id = p.ef_id inner join student s on s.id = ef.student_id order by unix_timestamp(p.date_created) desc ");
								if ($payments->num_rows > 0) :
									while ($row = $payments->fetch_assoc()) :
										$paid = $conn->query("SELECT sum(amount) as paid FROM payments where ef_id=" . $row['id']);
										$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : '';
								?>
										<tr>
											<td class="text-center"><?php echo $i++ ?></td>
											<td>
												<p><?php echo date("M d,Y H:i A", strtotime($row['date_created'])) ?></p>
											</td>
											<td>
												<p><?php echo $row['id_no'] ?></p>
											</td>
											<td>
												<p><?php echo $row['ef_no'] ?></p>
											</td>
											<td>
												<p><?php echo ucwords($row['sname']) ?></p>
											</td>
											<td class="text-right">
												<p><?php echo number_format($row['amount'], 2) ?></p>
											</td>
											<td class="text-center">
												<button class="btn btn-primary view_payment" type="button" data-id="<?php echo $row['id'] ?>" data-ef_id="<?php echo $row['ef_id'] ?>"><i class="fa fa-eye"></i></button>
												<button class="btn btn-info edit_payment" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
												<button class="btn btn-danger delete_payment" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash-alt"></i></button>
											</td>
										</tr>
									<?php
									endwhile;
								else :
									?>
									<tr>
										<th class="text-center" colspan="7">Sin datos que mostrar.</th>
									</tr>
								<?php
								endif;

								?>
							</tbody>

						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset
	}

	img {
		max-width: 100px;
		max-height: :150px;
	}
</style>

<script>
	$(document).ready(function() {
		$('table').dataTable()
	})

	$('#new_payment').click(function() {
		uni_modal("Nuevo Pago ", "manage_payment.php", "mid-large")

	})

	$('.view_payment').click(function() {
		uni_modal("Información de Pago", "view_payment.php?ef_id=" + $(this).attr('data-ef_id') + "&pid=" + $(this).attr('data-id'), "mid-large")

	})
	$('.edit_payment').click(function() {
		uni_modal("Gestionar Pago", "manage_payment.php?id=" + $(this).attr('data-id'), "mid-large")

	})
	$('.delete_payment').click(function() {
		_conf("¿Deseas eliminar este pago?", "delete_payment", [$(this).attr('data-id')])
	})

	function delete_payment($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_payment',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Datos eliminados exitósamente", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>