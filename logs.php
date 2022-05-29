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
	<?php
	$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
	?>
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
						<b>Registros</b>
						<button class="btn btn-success btn-block btn-sm col-sm-2 float-right mr-2 mt-0" type="button" id="print_record">
							<i class="fa fa-print"></i> Imprimir</button>
					</div>
					<div class="card-body">
						<div class="row justify-content-center">
							<label for="" class="mt-2">Fecha</label>
							<div class="col-sm-3">
								<input type="date" name="date" id="date" value="<?php echo date('Y-m-d', strtotime($date)) ?>" class="form-control">
							</div>
						</div>
						<hr>
						<table class="table table-condensed table-bordered table-hover" id="log-records">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Fecha/Hora</th>
									<th class="">ID #</th>
									<th class="">Nombre</th>
									<th class="">Tipo</th>
									<th class="">Tipo de Registro</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$employee = $conn->query("SELECT * from employees  where id in (SELECT frm_id FROM logs where date(date_created) = '$date' and type='employees')");
								$student = $conn->query("SELECT * from students  where id in (SELECT frm_id FROM logs where date(date_created) = '$date' and type='students')");
								$faculty = $conn->query("SELECT * from faculty  where id in (SELECT frm_id FROM logs where date(date_created) = '$date' and type='faculty')");
								$visitor = $conn->query("SELECT * from visitors  where id in (SELECT frm_id FROM logs where date(date_created) = '$date' and type='visitor')");
								$arr_data = array();
								while ($row = $employee->fetch_array()) {
									$arr_data['employees'][$row['id']] = $row;
								}
								while ($row = $student->fetch_array()) {
									$arr_data['students'][$row['id']] = $row;
								}
								while ($row = $faculty->fetch_array()) {
									$arr_data['faculty'][$row['id']] = $row;
								}
								while ($row = $visitor->fetch_array()) {
									$arr_data['visitor'][$row['id']] = $row;
								}
								$log = $conn->query("SELECT * FROM logs where date(date_created) = '$date' order by unix_timestamp(date_created) desc ");
								if ($log->num_rows > 0) :
									while ($row = $log->fetch_assoc()) :
										if ($row['type'] == 'students')
											$type = 'Student';
										elseif ($row['type'] == 'employees')
											$type = 'Employee';
										else
											$type = ucwords($row['type']);
								?>
										<tr>
											<td class="text-center"><?php echo $i++ ?></td>
											<td>
												<p> <b><?php echo date("M d, Y h:i A", strtotime($row['date_created'])) ?></b></p>
											</td>
											<td class="">
												<p>ID Type: <b><?php echo $row['type'] == 'visitor' ? ($arr_data[$row['type']][$row['frm_id']] ? $arr_data[$row['type']][$row['frm_id']]['id_presented'] : '') : 'School ID'  ?></b></p>
												<p>ID #: <?php echo $arr_data[$row['type']][$row['frm_id']] ? $arr_data[$row['type']][$row['frm_id']]['id_no'] : '' ?></b></p>
											</td>
											<td>
												<p> <b><?php echo ucwords($arr_data[$row['type']][$row['frm_id']] ? $arr_data[$row['type']][$row['frm_id']]['name'] : '') ?></b></p>
											</td>
											<td class="">
												<p><b><?php echo $type ?></b></p>
											</td>
											<td class="text-center">
												<p><b><?php echo $row['log_type'] == 1 ? 'IN' : 'OUT' ?></b></p>
											</td>
										</tr>
									<?php endwhile; ?>
								<?php else : ?>
									<tr>
										<th colspan="6">
											<center>Sin registros que mostrar</center>
										</th>
									</tr>
								<?php endif; ?>
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
<noscript>
	<style>
		table {
			width: 100%;
			border-collapse: collapse
		}

		tr,
		td,
		th {
			border: 1px solid
		}

		td p {
			margin: unset
		}

		.text-center {
			text-align: center
		}
	</style>
	<p class="text-center"><b>Registros escolares a partir de <?php echo date('F d,Y', strtotime($date)) ?><b></p>
</noscript>

<script>
	$('#date').change(function() {
		location.replace('index.php?page=logs&date=' + $(this).val())
	})
	$('#log-records').dataTable()
	$('#print_record').click(function() {
		$('#log-records').dataTable().fnDestroy()

		var ns = $('noscript').clone()
		var data = $('#log-records').clone()
		ns.append(data)
		var nw = window.open("", "_blank", "width=900,height=800")
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
			$('#log-records').dataTable()
		}, 500);
	})
</script>