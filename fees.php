<?php include('db_connect.php'); ?>
<style>
	input[type=checkbox] {
		/* Double-sized Checkboxes */
		-ms-transform: scale(1.3);
		/* IE */
		-moz-transform: scale(1.3);
		/* FF */
		-webkit-transform: scale(1.3);
		/* Safari and Chrome */
		-o-transform: scale(1.3);
		/* Opera */
		transform: scale(1.3);
		padding: 10px;
		cursor: pointer;
	}
</style>

<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">

			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Pagos de Estudiantes </b>
						<span class="float:right"><a class="btn btn-primary col-sm-6 col-md-2 float-right" href="javascript:void(0)" id="new_fees">
								<i class="fa fa-plus"></i> Pagos
							</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">ID No.</th>
									<th class="">No. Curso</th>
									<th class="">Nombre</th>
									<th class="">Tarifa</th>
									<th class="">Pago</th>
									<th class="">Balance</th>
									<th class="text-center">Acción</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$fees = $conn->query("SELECT ef.*,s.name as sname,s.id_no FROM student_ef_list ef inner join student s on s.id = ef.student_id order by s.name asc ");
								while ($row = $fees->fetch_assoc()) :
									$paid = $conn->query("SELECT sum(amount) as paid FROM payments where ef_id=" . $row['id']);
									$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : '';
									$balance = $row['total_fee'] - $paid;
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td>
											<p> <?php echo $row['id_no'] ?></p>
										</td>
										<td>
											<p> <?php echo $row['ef_no'] ?></p>
										</td>
										<td>
											<p> <?php echo ucwords($row['sname']) ?></p>
										</td>
										<td class="text-right">
											<p><?php echo number_format($row['total_fee'], 2) ?></p>
										</td>
										<td class="text-right">
											<p><?php echo number_format($paid, 2) ?></p>

										<td class="text-right">
											<p><?php echo number_format($balance, 2) ?></p>
										</td>
										<td class="text-center">
											<button class="btn btn-primary view_payment" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-eye"></i></button>
											<button class="btn btn-info edit_fees" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-edit"></i></button>
											<button class="btn btn-danger delete_fees" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash-alt"></i></button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>

						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
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

	$('.view_payment').click(function() {
		uni_modal("Información de Pagos", "view_payment.php?ef_id=" + $(this).attr('data-id') + "&pid=0", "mid-large")

	})
	$('#new_fees').click(function() {
		uni_modal("Inscribir estudiante ", "manage_fee.php", "mid-large")

	})
	$('.edit_fees').click(function() {
		uni_modal("Administrar los detalles de inscripción del estudiante", "manage_fee.php?id=" + $(this).attr('data-id'), "mid-large")

	})
	$('.delete_fees').click(function() {
		_conf("¿Deseas eliminar estas tarifas?", "delete_fees", [$(this).attr('data-id')])
	})

	function delete_fees($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_fees',
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