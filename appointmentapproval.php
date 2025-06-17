<?php
include("adheader.php");
include("dbconnection.php");

if(isset($_POST['submit'])) {
    $patientid = mysqli_real_escape_string($con, $_POST['select4']);
    $appointmentid = isset($_GET['editid']) ? mysqli_real_escape_string($con, $_GET['editid']) : null;
    $appointmentType = mysqli_real_escape_string($con, $_POST['apptype']);
    $departmentId = mysqli_real_escape_string($con, $_POST['select5']);
    $doctorId = mysqli_real_escape_string($con, $_POST['select6']);
    $appointmentDate = mysqli_real_escape_string($con, $_POST['appointmentdate']);
    $appointmentTime = mysqli_real_escape_string($con, $_POST['time']);
    $roomid = mysqli_real_escape_string($con, $_POST['select3']);

    if($appointmentid) {
        $sqlUpdatePatient = "UPDATE patient SET status='Active' WHERE patientid='$patientid'";
        mysqli_query($con, $sqlUpdatePatient);

        $sqlUpdateAppointment = "UPDATE appointment SET 
                                    appointmenttype='$appointmentType',
                                    departmentid='$departmentId',
                                    doctorid='$doctorId',
                                    status='Approved',
                                    appointmentdate='$appointmentDate',
                                    appointmenttime='$appointmentTime'
                                  WHERE appointmentid='$appointmentid'";

        if(mysqli_query($con, $sqlUpdateAppointment)) {
            include("insertbillingrecord.php");                
            echo "<script>alert('Appointment record updated successfully...');</script>";                
            echo "<script>window.location='patientreport.php?patientid=$patientid&appointmentid=$appointmentid';</script>";
        } else {
            echo mysqli_error($con);
        }    
    } else {
        $sqlUpdatePatient = "UPDATE patient SET status='Active' WHERE patientid='$patientid'";
        mysqli_query($con, $sqlUpdatePatient);

        $sqlInsertAppointment = "INSERT INTO appointment
                                  (appointmenttype, patientid, roomid, departmentid, appointmentdate, appointmenttime, doctorid, status)
                                  VALUES
                                  ('$appointmentType', '$patientid', '$roomid', '$departmentId', '$appointmentDate', '$appointmentTime', '$doctorId', '$_POST[select]')";

        if(mysqli_query($con, $sqlInsertAppointment)) {
            echo "<script>alert('Appointment record inserted successfully...');</script>";
        } else {
            echo mysqli_error($con);
        }
    }
}

if(isset($_GET['editid'])) {
    $editid = mysqli_real_escape_string($con, $_GET['editid']);
    $sql = "SELECT * FROM appointment WHERE appointmentid='$editid'";
    $qsql = mysqli_query($con, $sql);
    $rsedit = mysqli_fetch_array($qsql);
}
?>

<div class="card">
    <div class="block-header">
        <h2 class="text-center">Appointment Approval Process</h2>
    </div>
    <form method="post" action="" name="frmappnt" onsubmit="return validateform()">
        <table class="table table-striped">                
            <tr>
                <td>Patient</td>
                <td>
                    <?php
                    if(isset($_GET['patientid'])) {
                        $patientid = mysqli_real_escape_string($con, $_GET['patientid']);
                        $sqlpatient = "SELECT * FROM patient WHERE patientid='$patientid'";
                        $qsqlpatient = mysqli_query($con, $sqlpatient);
                        $rspatient = mysqli_fetch_array($qsqlpatient);
                        echo htmlspecialchars($rspatient['patientname'], ENT_QUOTES, 'UTF-8') . " (Patient ID - " . htmlspecialchars($rspatient['patientid'], ENT_QUOTES, 'UTF-8') . ")";
                    } else {
                        $sqlpatient = "SELECT * FROM patient WHERE status='Active'";
                        $qsqlpatient = mysqli_query($con, $sqlpatient);
                        while($rspatient = mysqli_fetch_array($qsqlpatient)) {
                            $selected = ($rspatient['patientid'] == $rsedit['patientid']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($rspatient['patientid'], ENT_QUOTES, 'UTF-8')."' $selected>".htmlspecialchars($rspatient['patientname'], ENT_QUOTES, 'UTF-8')." (Patient ID - ".htmlspecialchars($rspatient['patientid'], ENT_QUOTES, 'UTF-8').")</option>";
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Department</td>
                <td>
                    <select name="select5" id="select5" class="form-control show-tick">
                        <option value="">Select</option>
                        <?php
                        $sqldepartment = "SELECT * FROM department WHERE status='Active'";
                        $qsqldepartment = mysqli_query($con, $sqldepartment);
                        while($rsdepartment = mysqli_fetch_array($qsqldepartment)) {
                            $selected = ($rsdepartment['departmentid'] == $rsedit['departmentid']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($rsdepartment['departmentid'], ENT_QUOTES, 'UTF-8')."' $selected>".htmlspecialchars($rsdepartment['departmentname'], ENT_QUOTES, 'UTF-8')."</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Doctor</td>
                <td>
                    <select name="select6" id="select6" class="form-control show-tick">
                        <option value="">Select</option>
                        <?php
                        $sqldoctor = "SELECT * FROM doctor INNER JOIN department ON department.departmentid=doctor.departmentid WHERE doctor.status='Active'";
                        $qsqldoctor = mysqli_query($con, $sqldoctor);
                        while($rsdoctor = mysqli_fetch_array($qsqldoctor)) {
                            $selected = ($rsdoctor['doctorid'] == $rsedit['doctorid']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($rsdoctor['doctorid'], ENT_QUOTES, 'UTF-8')."' $selected>".htmlspecialchars($rsdoctor['doctorname'], ENT_QUOTES, 'UTF-8')." ( ".htmlspecialchars($rsdoctor['departmentname'], ENT_QUOTES, 'UTF-8')." )</option>";                
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Appointment Date</td>
                <td><input class="form-control" type="date" name="appointmentdate" id="appointmentdate" value="<?php echo htmlspecialchars($rsedit['appointmentdate'], ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
            <tr>
                <td>Appointment Time</td>
                <td><input class="form-control" type="time" name="time" id="time" value="<?php echo htmlspecialchars($rsedit['appointmenttime'], ENT_QUOTES, 'UTF-8'); ?>" /></td>
            </tr>
            <tr>
                <td>Appointment Reason</td>
                <td><input class="form-control" name="appreason" id="appreason" value="<?php echo htmlspecialchars($rsedit['app_reason'], ENT_QUOTES, 'UTF-8'); ?>"/></td>         
            </tr>
            <tr>
                <td colspan="2" align="center"><input class="btn btn-default" type="submit" name="submit" id="submit" value="Submit" /></td>
            </tr>
        </table>
    </form>
    <p>&nbsp;</p>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
include("adfooter.php");
?>
<script type="application/javascript">
function validateform() {
    if(document.frmappnt.select4 && document.frmappnt.select4.value == "") {
        alert("Patient name should not be empty.");
        document.frmappnt.select4.focus();
        return false;
    } else if(document.frmappnt.select3 && document.frmappnt.select3.value == "") {
        alert("Room type should not be empty.");
        document.frmappnt.select3.focus();
        return false;
    } else if(document.frmappnt.select5.value == "") {
        alert("Department name should not be empty.");
        document.frmappnt.select5.focus();
        return false;
    } else if(document.frmappnt.appointmentdate.value == "") {
        alert("Appointment date should not be empty.");
        document.frmappnt.appointmentdate.focus();
        return false;
    } else if(document.frmappnt.time.value == "") {
        alert("Appointment time should not be empty.");
        document.frmappnt.time.focus();
        return false;
    } else if(document.frmappnt.select6.value == "") {
        alert("Doctor name should not be empty.");
        document.frmappnt.select6.focus();
        return false;
    } else if(document.frmappnt.select && document.frmappnt.select.value == "") {
        alert("Kindly select the status.");
        document.frmappnt.select.focus();
        return false;
    } else {
        return true;
    }
}

$('.out_patient').hide();
$('#apptype').change(function() {
    var apptype = $('#apptype').val();
    if(apptype == 'InPatient') {
        $('.out_patient').show();
    } else {
        $('.out_patient').hide();
    }
});
</script>
