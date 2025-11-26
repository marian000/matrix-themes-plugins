<!-- Bootstrap3 -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<br>
<h2>Search Info</h2>
<div class="container">


    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#menu1">Compare Users by Years</a>
        </li>
        <li>
            <a data-toggle="tab" href="#home">Total User Activity</a>
        </li>
        <li>
            <a data-toggle="tab" href="#battens">Users Placed Orders</a>
        </li>
    </ul>

    <div class="tab-content">

        <div id="menu1" class="tab-pane fade in active">
            <?php
            include_once __DIR__ . '/users-orders/users-compare.php';
            ?>
        </div>
        <div id="home" class="tab-pane fade">
            <?php
            include_once __DIR__ . '/users-orders/users-activity.php';
            ?>
        </div>
        <div id="battens" class="tab-pane fade">
            <?php
            include_once __DIR__ . '/users-orders/users-placed-orders.php';
            ?>
        </div>
    </div>
</div>

<!-- *********************************************************************************************
End Total user sqm
*********************************************************************************************	-->
