<?php
include 'db_connect.php';
$fees = $conn->query("SELECT ef.*,s.name as sname,s.id_no,concat(c.course,' - ',c.level) as `class` FROM student_ef_list ef inner join student s on s.id = ef.student_id inner join courses c on c.id = ef.course_id  where ef.id = {$_GET['ef_id']}");
foreach ($fees->fetch_array() as $k => $v) {
	$$k = $v;
}
$payments = $conn->query("SELECT * FROM payments where ef_id = $id ");
$pay_arr = array();
while ($row = $payments->fetch_array()) {
	$pay_arr[$row['id']] = $row;
}
?>

<style>
	.flex {
		display: inline-flex;
		width: 100%;
	}

	.w-50 {
		width: 50%;
	}

	.text-center {
		text-align: center;
	}

	.text-right {
		text-align: right;
	}

	table.wborder {
		width: 100%;
		border-collapse: collapse;
	}

	table.wborder>tbody>tr,
	table.wborder>tbody>tr>td {
		border: 1px solid;
	}

	p {
		margin: unset;
	}
</style>
<div class="container-fluid">
	<p class="text-center"><b><?php echo $_GET['pid'] == 0 ? "Factura ConfiguroWeb" : 'Recibo de Pago' ?></b></p>
	<hr>
	<div class="flex">
		<div class="w-50">
			<p>No de Curso: <b><?php echo $ef_no ?></b></p>
			<p>Estudiante: <b><?php echo ucwords($sname) ?></b></p>
			<p>Curso/Nivel: <b><?php echo $class ?></b></p>
		</div>
		<?php if ($_GET['pid'] > 0) : ?>
			<div class="w-50">
				<p>Fecha de Pago: <b><?php echo isset($pay_arr[$_GET['pid']]) ? date("M d,Y", strtotime($pay_arr[$_GET['pid']]['date_created'])) : '' ?></b></p>
				<p>Monto de Pago: <b><?php echo isset($pay_arr[$_GET['pid']]) ? number_format($pay_arr[$_GET['pid']]['amount'], 2) : '' ?></b></p>
				<p>Observación: <b><?php echo isset($pay_arr[$_GET['pid']]) ? $pay_arr[$_GET['pid']]['remarks'] : '' ?></b></p>
			</div>
		<?php endif; ?>
	</div>
	<hr>
	<p><b>Resumen de Pago</b></p>
	<table class="wborder">
		<tr>
			<td width="50%">
				<p><b>Detalles de la tarifa</b></p>
				<hr>
				<table width="100%">
					<tr>
						<td width="50%">Tipo de tarifa</td>
						<td width="50%" class='text-right'>Monto</td>
					</tr>
					<?php
					$cfees = $conn->query("SELECT * FROM fees where course_id = $course_id");
					$ftotal = 0;
					while ($row = $cfees->fetch_assoc()) {
						$ftotal += $row['amount'];
					?>
						<tr>
							<td><b><?php echo $row['description'] ?></b></td>
							<td class='text-right'><b><?php echo number_format($row['amount']) ?></b></td>
						</tr>
					<?php
					}
					?>
					<tr>
						<th>Total</th>
						<th class='text-right'><b><?php echo number_format($ftotal) ?></b></th>
					</tr>
				</table>
			</td>
			<td width="50%">
				<p><b>Información de Pago</b></p>
				<table width="100%" class="wborder">
					<tr>
						<td width="50%">Fecha</td>
						<td width="50%" class='text-right'>Monto</td>
					</tr>
					<?php
					$ptotal = 0;
					foreach ($pay_arr as $row) {
						if ($row["id"] <= $_GET['pid'] || $_GET['pid'] == 0) {
							$ptotal += $row['amount'];
					?>
							<tr>
								<td><b><?php echo date("Y-m-d", strtotime($row['date_created'])) ?></b></td>
								<td class='text-right'><b><?php echo number_format($row['amount']) ?></b></td>
							</tr>
					<?php
						}
					}
					?>
					<tr>
						<th>Total</th>
						<th class='text-right'><b><?php echo number_format($ptotal) ?></b></th>
					</tr>
				</table>
				<table width="100%">
					<tr>
						<td>Tarifa total a pagar</td>
						<td class='text-right'><b><?php echo number_format($ftotal) ?></b></td>
					</tr>
					<tr>
						<td>Total Pagado</td>
						<td class='text-right'><b><?php echo number_format($ptotal) ?></b></td>
					</tr>
					<tr>
						<td>Balance</td>
						<td class='text-right'><b><?php echo number_format($ftotal - $ptotal) ?></b></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>