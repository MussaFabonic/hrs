<?php
// print_r($_SESSION['leavesHistory']);die();
?>
<div class="animated fadeIn" >
    <div class="row">
        <div class="col-md-12" style="margin-bottom : 37%;">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Leaves List

                    <button type="button" class="btn btn-info" style="float : right" data-toggle="modal" data-target="#mediumModal">Request Leave</button>
                    </strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Employee Name</th>
                                    <th>Leave Type</th>
                                    <th>No of Days</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Leave Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <? 
                                    $no = 0; foreach($_SESSION['leavesHistory'] as $key=>$value){?>
                                        <tr>
                                            <td><?php echo $no+1;?></td>
                                            <td><?php echo $value['sname'];?></td>
                                            <td><?php echo $value['lname'];?></td>
                                            <td><?php echo $value['noofdays'];?></td>
                                            <td>
                                                <?php
                                                    $date = new DateTime($value['fromdte']);
                                                    echo $date->format('d.M.Y');
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $date = new DateTime($value['todte']);
                                                    echo $date->format('d.M.Y');
                                                ?>
                                            </td>
                                            <td><?php echo $value['status'];?></td>
                                            <td style="cursor: pointer;">
                                                <?php if ($value['status'] === 'Requested'){ ?>
                                                    <i class="fa fa-edit" title="Edit Leave" onclick="editLeave(<?php echo $value['id']; ?>, <?php echo $_GET['id']; ?>)"></i>

                                                    <i class="fa fa-trash" title="Delete Leave" onclick="deleteLeave(<?php echo $value['id']; ?>)"></i>

                                                <?php }else{ ?>
                                                    <span title="Editing not allowed"><i class="fa fa-edit" style="color: gray;"></i></span>
                                                <?php } ?>
                                            </td>

                                        </tr>
                                    <? $no++;}
                                ?>
                            </tbody> 
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <input type="hidden" name="userid" id="userid" value="<?php echo $_GET['id']?>"/>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Apply For Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="leaveForm">
                    <div class="form-group">
                        <label for="leaveType">Select Leave Type</label>
                        <select id="leaveType" class="form-control select2" required onChange="showLeaveStatus()">
                            <option value="" disabled selected>Select leave type</option>
                            <?php foreach($_SESSION['leaveTypes'] as $k=>$v){?>
                                <option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
                            <?}?>

                        </select>
                    </div>
                    <table class="table" id="leaveStatus" style="display:none;">
                        <thead>
                            <tr>
                                <th scope="col">Leave Name</th>
                                <th scope="col">Total Allowed</th>
                                <th scope="col">Taken</th>
                                <th scope="col">Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="lname"></td>
                                <td id="totAllowed"></td>
                                <td id="totTaken"></td>
                                <td id="totRemaining"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <label for="fromDate">From Date</label>
                        <input type="date" id="fromDate" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="toDate">To Date</label>
                        <input type="date" id="toDate" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="submitLeaveForm()">Confirm</button>
            </div>
        </div>
    </div>
</div>


<script>
        function showLeaveStatus() {
            var leaveTypeId = $('#leaveType').val();
            var userid = $('#userid').val();
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;

            const formData = {
                leaveType: leaveTypeId,
                userid: userid
            };

            $.ajax({
                url: 'leaves_details.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#leaveStatus').hide();
                    console.log(response);
                    if (typeof response === 'string') {
                        try {
                            response = JSON.parse(response);
                        } catch (error) {
                            console.error('Parsing error:', error);
                            return;
                        }
                    }
                    if (response.success && response.details && response.details.length > 0) {
                        const leaveDetails = response.details[0];

                        document.getElementById('lname').innerText = leaveDetails.text;
                        document.getElementById('totAllowed').innerText = leaveDetails.total;
                        document.getElementById('totTaken').innerText = leaveDetails.taken;
                        document.getElementById('totRemaining').innerText = leaveDetails.bal;
                        document.getElementById('leaveStatus').style.display = 'table';
                        $('#leaveStatus').show();
                    } else {
                        console.error('Response structure is unexpected:', response);
                    }
                }
            });
        }
        
        function submitLeaveForm() {
            var myModalEl = document.getElementById('mediumModal');
            const leaveType = document.getElementById('leaveType').value;
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            const userid = $('#userid').val();
        
            if (leaveType && fromDate && toDate) {
                const formData = {
                    leaveType: leaveType,
                    fromDate: fromDate,
                    toDate: toDate,
                    userid: userid
                };

                const startDate = new Date(fromDate);
                const endDate = new Date(toDate);

                const differenceInTime = endDate - startDate;
                const differenceInDays = differenceInTime / (1000 * 3600 * 24);

                var leaveBalance = $('#totRemaining').text();

                // console.log(differenceInDays);
                // console.log('_');
                // console.log(leaveBalance);

                if( differenceInDays > leaveBalance ){
                    alert('You are Requesting More than the available Days');
                    return false;
                }else{
                    $.ajax({
                        url: 'leaves.php',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            console.log('Response from server:', response);
                            myModalEl.classList.remove('show');
                            myModalEl.style.display = 'none';
                            location.reload();
                        }
                    });
                }
                
            } else {
                alert('Please fill in all fields.');
            }
    }

    function editLeave(id) {
        const userid = $('#userid').val();
        window.location.href= '../controllers/leaves_edit.php?userid='+userid+'&lid='+id;
    }

    function deleteLeave(id) {
        const formData = {
            id: id
        };
        
        if (confirm('Are you sure you want to delete this row?')) {
            $.ajax({
                url: 'delete.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response) {
                        location.reload();
                    } else {
                        console.error('Response structure is unexpected:', response);
                    }
                }
            });
        } else {
            console.log('Delete action canceled.');
        }
    }
</script>
