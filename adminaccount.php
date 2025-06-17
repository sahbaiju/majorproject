<?php
include("adheader.php");
include("dbconnection.php");

session_start();
if (!isset($_SESSION["adminid"])) {
    echo "<script>window.location='adminlogin.php';</script>";
    exit(); // Stop further execution if not logged in
}

?>

<div class="container-fluid">
    <div class="block-header">
        <h2>Dashboard</h2>
        <small class="text-muted">Welcome to Admin Panel</small>
    </div>

    <div class="row clearfix">
        <!-- Total Patients -->
        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="info-box-4 hover-zoom-effect">
                <div class="icon">
                    <i class="zmdi zmdi-male-female col-blush"></i>
                </div>
                <div class="content">
                    <div class="text">Total Patients</div>
                    <div class="number">
                        <?php
                        $sql = "SELECT * FROM patient WHERE status='Active'";
                        $qsql = mysqli_query($con, $sql);
                        echo mysqli_num_rows($qsql);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Doctors -->
        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="info-box-4 hover-zoom-effect">
                <div class="icon">
                    <i class="zmdi zmdi-account-circle col-cyan"></i>
                </div>
                <div class="content">
                    <div class="text">Total Doctors</div>
                    <div class="number">
                        <?php
                        $sql = "SELECT * FROM doctor WHERE status='Active'";
                        $qsql = mysqli_query($con, $sql);
                        echo mysqli_num_rows($qsql);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Administrators -->
        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="info-box-4 hover-zoom-effect">
                <div class="icon">
                    <i class="zmdi zmdi-account-box-mail col-blue"></i>
                </div>
                <div class="content">
                    <div class="text">Total Administrators</div>
                    <div class="number">
                        <?php
                        $sql = "SELECT * FROM admin WHERE status='Active'";
                        $qsql = mysqli_query($con, $sql);
                        echo mysqli_num_rows($qsql);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hospital Earnings -->
        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="info-box-4 hover-zoom-effect">
                <div class="icon">
                    <i class="zmdi zmdi-money col-green"></i>
                </div>
                <div class="content">
                    <div class="text">Hospital Earnings</div>
                    <div class="number">$
                        <?php
                        $sql = "SELECT SUM(bill_amount) AS total FROM billing_records";
                        $qsql = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($qsql);
                        echo $row['total'];
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>
</div>

<?php
include("adfooter.php");
mysqli_close($con); // Optional: Close the database connection
?>