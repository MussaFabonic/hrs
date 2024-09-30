<?php
// print_r($_SESSION['leavesHistory']);die();
?>
<div class="animated fadeIn" >
    <div class="row">
        <div class="col-md-12" style="margin-bottom : 37%;">
        <div class="card">
            <div class="card-header">Details</div>
                <div class="card-body card-block">
                    <form action="#" method="post" class="">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                <input type="hidden" name="userid" id="userid1" value="<?php echo $_GET['userid']?>"/>

                                <select id="leaveType" class="form-control select2" required onChange="showLeaveStatus()">
                                    <option value="" disabled selected>Select leave type</option>
                                    <?php foreach($_SESSION['leaveTypes'] as $k=>$v){?>
                                        <option <?= ($v['id'] == $_SESSION['dets']['leaveid'] ? ' selected ' : ''); ?> value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
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
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                                <input type="date" id="fromdte" name="fromdte" placeholder="From Date" value="<?php echo $_SESSION['dets']['fromdte']?>" class="form-control" onchange="verifyDets();">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-asterisk"></i></div>
                                <input type="date" id="todte" name="todte" placeholder="To Date" value="<?php echo $_SESSION['dets']['todte']?>" class="form-control" onchange="verifyDets();">
                            </div>
                        </div>
                        <h6 style="color : green; display : none;" id="requested_days">Number of Days Requested : <span id="count"><span></h6></br>
                        <h6 style="display: none;" id="error_requested_days" class="error-message">
                        You are requesting more than the available days. You requested:&nbsp;<span id="message_requested_days"></span>&nbsp;days.&nbsp;Available:&nbsp;<span id="message_remaining_days"></span>&nbsp;days.
                        </h6>
                        <div class="form-actions form-group"><a type="submit" id="confirmBtn" class="btn btn-success btn-sm" onClick="submitLeaveForm()">Save</a>  <a type="submit" class="btn btn-danger btn-sm" onClick="back()">Back</a></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <input type="hidden" name="userid" id="userid" value="<?php echo $_GET['id']?>"/>
        <div class="modal-content">
            
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        $(document).ready(function() {
            showLeaveStatus();
        });

        function showLeaveStatus() {
            var leaveTypeId = $('#leaveType').val();
            var userid = $('#userid1').val();
            const fromDate = $('#fromdte').val();
            const toDate = $('#todte').val();
            const lrId = '<?php echo $_GET['lid'] ?>';


            const formData = {
                leaveType: leaveTypeId,
                userid: userid,
                lrid : lrId
            };

            $.ajax({
                url: 'leaves_details.php',
                type: 'POST',
                data: formData,
                success: function(response) {
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
                        verifyDets();
                    } else {
                        console.error('Response structure is unexpected:', response);
                    }
                }
            });
        }

        function submitLeaveForm() {
            const leaveType = $('#leaveType').val();
            const fromDate = $('#fromdte').val();
            const toDate = $('#todte').val();
            const userid = $('#userid1').val();
            const lid = '<?php echo $_GET['lid']?>';
            
            if (leaveType && fromDate && toDate) {
                const formData = {
                    leaveType: leaveType,
                    fromDate: fromDate,
                    toDate: toDate,
                    userid: userid,
                    id : lid
                };

            const startDate = new Date(fromDate);
            const endDate = new Date(toDate);

            const differenceInTime = endDate - startDate;
            const differenceInDays = differenceInTime / (1000 * 3600 * 24) + 1;

            var leaveBalance = $('#totRemaining').text();

            if( differenceInDays > leaveBalance ){
                alert('You are Requesting More than the available Days');
            }else{
                $.ajax({
                    url: 'leaves_updates.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert('Details Updated.');
                    }
                });
            }
            
        } else {
            alert('Please fill in all fields.');
        }
    }
    function back() {
        const userid = $('#userid1').val();
        window.location.href= '../controllers/leaves.php?id='+userid;
    }

    function verifyDets(){
        const leaveType = document.getElementById('leaveType').value;
        const fromDate = document.getElementById('fromdte').value;
        const toDate = document.getElementById('todte').value;
        const leaveBalance1 = $('#totRemaining').text();
        console.log(leaveBalance1);
        if(leaveType && fromDate && toDate){
            const startDate = new Date(fromDate);
            const endDate = new Date(toDate);
            // (leaveType);

            const differenceInTime = endDate - startDate;
            const differenceInDays = differenceInTime / (1000 * 3600 * 24) + 1;

            $('#count').text(differenceInDays);

            
            // console.log(leaveBalance1);

            if( differenceInDays > leaveBalance1 ){

                $('#requested_days').hide();
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
</script>

