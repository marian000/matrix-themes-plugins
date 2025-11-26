<br>

<button class="accordion btn btn-primary no-results ">Old Repair Orders with no Container</button>
<div class="panel">
    <div class="orders_data_old"></div>
</div>

<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }
    jQuery(document).ready(function () {
        // jQuery('.accordion.btn.btn-primary.no-results').on('click', function () {
        //     if (jQuery(".accordion.btn.btn-primary").hasClass("no-results")) {
        jQuery.ajax({
            method: "POST",
            url: "/wp-content/themes/storefront-child/ajax/older-orders-repair.php",
            data: {search: "true"},
            beforeSend: function () {
                // jQuery("body .spinner-modal").show();
            },
            complete: function () {
                // jQuery("body .spinner-modal").hide();
            },
        })
            .done(function (data) {
                // console.log("Data Saved: " + data);
                jQuery('.orders_data_old').html(data);
                var orders_fount = jQuery('.table.data-old tr').length;
                if(orders_fount > 1){
                    jQuery('.accordion.btn.btn-primary').addClass('bkg-red');
                }
            });
        //jQuery(this).removeClass('no-results');
        //     }
        // });

        jQuery('.insert_orders').on('click', function (e) {
            e.preventDefault();
            jQuery.ajax({
                method: "POST",
                url: "/wp-content/themes/storefront-child/ajax/insert-orders.php",
                data: {search: "true"},
                beforeSend: function () {
                    // jQuery("body .spinner-modal").show();
                },
                complete: function () {
                    // jQuery("body .spinner-modal").hide();
                },
            })
                .done(function (data) {
                    jQuery('.orders_data_old').html('orders inserted');
                });
        });

    });
</script>

<style>
    .orders_data_old table.table {
        width: 100%;
    }
    
    .accordion {
        color: #444;
        cursor: pointer;
        padding: 6px;
        text-align: left;
        outline: none;
        font-size: 13px;
        transition: 0.4s;
    }
    
    .panel {
        padding: 0 18px;
        display: none;
        background-color: white;
        overflow: hidden;
    }
    
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        border-top: 1px solid #ddd;
    }
    
    .bkg-red {
        background-color: indianred;
        color: #fff;
    }
</style>
