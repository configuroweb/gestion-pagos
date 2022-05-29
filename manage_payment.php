<?php include 'db_connect.php' ?>
<?php
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM payments where id = {$_GET['id']} ");
	foreach ($qry->fetch_array() as $k => $v) {
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form id="manage-payment">
		<div id="msg"></div>
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="" class="control-label">No Curso/Estudiante</label>
			<select name="ef_id" id="ef_id" class="custom-select input-sm select2">
				<option value=""></option>
				<?php
				$fees = $conn->query("SELECT ef.*,s.name as sname,s.id_no FROM student_ef_list ef inner join student s on s.id = ef.student_id order by s.name asc ");
				while ($row = $fees->fetch_assoc()) :
					$paid = $conn->query("SELECT sum(amount) as paid FROM payments where ef_id=" . $row['id'] . (isset($id) ? " and id!=$id " : ''));
					$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : '';
					$balance = $row['total_fee'] - $paid;
				?>
					<option value="<?php echo $row['id'] ?>" data-balance="<?php echo $balance ?>" <?php echo isset($ef_id) && $ef_id == $row['id'] ? 'selected' : '' ?>><?php echo  $row['ef_no'] . ' | ' . ucwords($row['sname']) ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Saldo Pendiente</label>
			<input type="text" class="form-control text-right" id="balance" value="" required readonly>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Monto</label>
			<input type="text" class="form-control text-right" name="amount" value="<?php echo isset($amount) ? number_format($amount) : 0 ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Observaciones</label>
			<textarea name="remarks" id="" cols="30" rows="3" class="form-control" required=""><?php echo isset($remarks) ? $remarks : '' ?></textarea>
		</div>
	</form>
</div>

<script>
	$('.select2').select2({
		placeholder: 'Porfavor selecciona aquí',
		width: '100%'
	})
	$('#ef_id').change(function() {
		var amount = $('#ef_id option[value="' + $(this).val() + '"]').attr('data-balance')
		$('#balance').val(parseFloat(amount).toLocaleString('en-US', {
			style: 'decimal',
			maximumFractionDigits: 2,
			minimumFractionDigits: 2
		}))
	})
	$('#manage-payment').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_payment',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				console.log(err)
				end_load()
			},
			success: function(resp) {
				resp = JSON.parse(resp)
				if (resp.status == 1) {
					alert_toast("Datos guardados con éxito.", 'success')
					setTimeout(function() {
						var nw = window.open('receipt.php?ef_id=' + resp.ef_id + '&pid=' + resp.pid, "_blank", "width=900,height=600")
						setTimeout(function() {
							nw.print()
							setTimeout(function() {
								nw.close()
								location.reload()
							}, 500)
						}, 500)
					}, 500)
				}
			}
		})
	})
</script>