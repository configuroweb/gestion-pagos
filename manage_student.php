<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM student where id= " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-student">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class="form-group"></div>
        <div class="form-group">
            <label for="" class="control-label">Id No.</label>
            <input type="text" class="form-control" name="id_no" value="<?php echo isset($id_no) ? $id_no : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Nombre</label>
            <input type="text" class="form-control" name="name" value="<?php echo isset($name) ? $name : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Contacto</label>
            <input type="text" class="form-control" name="contact" value="<?php echo isset($contact) ? $contact : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Correo</label>
            <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Dirección</label>
            <textarea name="address" id="" cols="30" rows="3" class="form-control" required=""><?php echo isset($address) ? $address : '' ?></textarea>
        </div>
    </form>
</div>

<script>
    $('#manage-student').on('reset', function() {
        $('#msg').html('')
        $('input:hidden').val('')
    })
    $('#manage-student').submit(function(e) {
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url: 'ajax.php?action=save_student',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Datos guardados exitósamente", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                } else if (resp == 2) {
                    $('#msg').html('<div class="alert alert-danger mx-2">ID existe actualmente </div>')
                    end_load()
                }
            }
        })
    })

    $('.select2').select2({
        placeholder: "Porfavor selecciona aquí",
        width: '100%'
    })
</script>