<?php
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearFormData();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="leaveForm">
                    <div class="form-group">
                        <label for="leaveType">Select Leave Type</label>
            
                        <select id="leaveType" class="form-control select2" required onChange="showLeaveStatus();">
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
                        <input type="text" id="fromDate" class="form-control" required onchange="verifyDets('fromDate');" placeholder="dd/mm/yyyy">
                    </div>
                    <div class="form-group">
                        <label for="toDate">To Date</label>
                        <input type="text" id="toDate" class="form-control" required onchange="verifyDets('toDate');" placeholder="dd/mm/yyyy">
                    </div>
                    <div id="requested_days" class="alert alert-success mt-4" style="display: none;">
                        Number of Days Requested: <span id="count"></span>
                    </div>
                    <div id="error_requested_days" class="alert alert-danger mt-4" style="display: none;">
                        You are requesting more than the available days. You requested: 
                        <span id="message_requested_days"></span> days. 
                        Available: <span id="message_remaining_days"></span> days.
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <div class="w-100 d-flex">
                    <button type="button" class="btn btn-secondary rounded-pill" data-dismiss="modal" onclick="clearFormData();">Cancel</button>
                    <button type="button" id="confirmBtn" class="btn btn-primary rounded-pill ml-2" onclick="submitLeaveForm()" style="display: none;">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    $(function() {
        $("#fromDate").datepicker({
            dateFormat: "dd/mm/yy",
            onSelect: function(dateText) {
                verifyDets('fromDate');
            }
        });

        $("#toDate").datepicker({
            dateFormat: "dd/mm/yy",
            onSelect: function(dateText) {
                verifyDets('toDate');
            }
        });
    });
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
                    // console.log(response);
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
                        verifyDets('fromDate');
                    } else {
                        console.error('Response structure is unexpected:', response);
                    }
                }
            });
        }

        function submitLeaveForm() {
            if (confirm('Are you sure you want Request This Leave?')) {
                var myModalEl = document.getElementById('mediumModal');
                const leaveType = document.getElementById('leaveType').value;
                const fromDate = document.getElementById('fromDate').value;
                const toDate = document.getElementById('toDate').value;
                const userid = $('#userid').val();

                if (leaveType && fromDate && toDate) {
                    const finalFromDate = parseDateToISO(fromDate);
                    const finaltoDate = parseDateToISO(toDate);

                    // console.log(finalFromDate);
                    // console.log(finaltoDate);

                    // return false;
                    const formData = {
                        leaveType: leaveType,
                        fromDate: finalFromDate,
                        toDate: finaltoDate,
                        userid: userid
                    };

                    const startDate = parseDate(fromDate);
                    const endDate = parseDate(toDate);

                    const differenceInTime = endDate - startDate;
                    const differenceInDays = differenceInTime / (1000 * 3600 * 24) + 1;

                    var leaveBalance = parseInt($('#totRemaining').text(), 10);

                    if (differenceInDays > leaveBalance) {
                        alert('You are requesting more than the available days.');
                        return false;
                    } else {
                        $.ajax({
                            url: 'leaves.php',
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                console.log('Response from server:', response);
                                myModalEl.classList.remove('show');
                                myModalEl.style.display = 'none';
                                location.reload();
                            },
                            error: function(error) {
                                console.error('Error:', error);
                            }
                        });
                    }
                } else {
                    alert('Please fill in all fields.');
                }
            } else {
                console.log('Action cancelled by user.');
            }
        }

    function closeModal() {
        $('#mediumModal').modal('hide');
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

    function verifyDets(field){
        const leaveType = document.getElementById('leaveType').value;
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;
        const leaveBalance1 = $('#totRemaining').text();

        

        if( toDate && fromDate ){
            const fromDateString = document.getElementById('fromDate').value;
            const toDateString = document.getElementById('toDate').value;

            const fromDate1 = parseDate(fromDateString);
            const toDate1 = parseDate(toDateString);
        
            if (toDate1 < fromDate1) {
                alert("To Date cannot be before From Date.");
                document.getElementById('toDate').value = '';
                $('#requested_days').hide();
                $('#message_requested_days').hide();
                $('#message_remaining_days').hide();
                $('#error_requested_days').hide();
                $('#confirmBtn').hide();
                return false;
            }
        }

        
        
        if(leaveType && fromDate && toDate){

            const formattedFromDate = formatDate(fromDate);
            const formattedToDate = formatDate(toDate);

            $('#requested_days').show();
            const startDate = parseDate(fromDate);
            const endDate = parseDate(toDate);

            const differenceInTime = endDate - startDate;
            const differenceInDays = differenceInTime / (1000 * 3600 * 24) + 1;

            $('#count').text(differenceInDays);

            if( differenceInDays > leaveBalance1 ){

                $('#error_requested_days').show();
                $('#message_requested_days').text(differenceInDays);
                $('#message_remaining_days').text(leaveBalance1);
                $('#confirmBtn').hide();
            }else{
                $('#requested_days').show();
                $('#error_requested_days').hide();
                $('#confirmBtn').show();
            }
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();

        return `${day}/${month}/${year}`;
    }


    function clearFormData() {
        document.getElementById('leaveForm').reset();
        document.getElementById('leaveStatus').style.display = 'none';
        document.getElementById('requested_days').style.display = 'none';
        document.getElementById('error_requested_days').style.display = 'none';
    }

    function parseDate(dateString) {
        const [day, month, year] = dateString.split('/');
        return new Date(year, month - 1, day);
    }

    function parseDateToISO(dateString) {
        const [day, month, year] = dateString.split('/');
        const dateObj = new Date(year, month - 1, day);
        
        const formattedDate = dateObj.toISOString().split('T')[0];
        return formattedDate;
    }

</script>
