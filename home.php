<?php include 'db_connect.php' ?>
<style>
    span.float-right.summary_icon {
        font-size: 3rem;
        position: absolute;
        right: 1rem;
        top: 0;
    }

    .imgs {
        margin: .5em;
        max-width: calc(100%);
        max-height: calc(100%);
    }

    .imgs img {
        max-width: calc(100%);
        max-height: calc(100%);
        cursor: pointer;
    }

    #imagesCarousel,
    #imagesCarousel .carousel-inner,
    #imagesCarousel .carousel-item {
        height: 60vh !important;
        background: black;
    }

    #imagesCarousel .carousel-item.active {
        display: flex !important;
    }

    #imagesCarousel .carousel-item-next {
        display: flex !important;
    }

    #imagesCarousel .carousel-item img {
        margin: auto;
    }

    #imagesCarousel img {
        width: auto !important;
        height: auto !important;
        max-height: calc(100%) !important;
        max-width: calc(100%) !important;
    }
</style>

<div class="containe-fluid">
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php echo "Hola hola " . $_SESSION['login_name'] . "!"  ?>
                    <hr>


                    <table class="table table-condensed table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="">Fecha</th>
                                <th class="">ID No.</th>
                                <th class="">EF No.</th>
                                <th class="">Nombre</th>
                                <th class="">Monto Pagado</th>

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
</div>

<script>
    $('#manage-records').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                resp = JSON.parse(resp)
                if (resp.status == 1) {
                    alert_toast("Datos guardados exitósamente", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 800)

                }

            }
        })
    })
    $('#tracking_id').on('keypress', function(e) {
        if (e.which == 13) {
            get_person()
        }
    })
    $('#check').on('click', function(e) {
        get_person()
    })

    function get_person() {
        start_load()
        $.ajax({
            url: 'ajax.php?action=get_pdetails',
            method: "POST",
            data: {
                tracking_id: $('#tracking_id').val()
            },
            success: function(resp) {
                if (resp) {
                    resp = JSON.parse(resp)
                    if (resp.status == 1) {
                        $('#name').html(resp.name)
                        $('#address').html(resp.address)
                        $('[name="person_id"]').val(resp.id)
                        $('#details').show()
                        end_load()

                    } else if (resp.status == 2) {
                        alert_toast("ID desconocido.", 'danger');
                        end_load();
                    }
                }
            }
        })
    }
</script>