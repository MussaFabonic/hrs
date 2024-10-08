<?php
if (isset($_SESSION['dets']['fromdte'])) {
    $fromDate = new DateTime($_SESSION['dets']['fromdte']);
} 
if (isset($_SESSION['dets']['todte'])) {
    $toDate = new DateTime($_SESSION['dets']['todte']);
} 
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
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" id="fromdte" name="fromdte" placeholder="From Date" value="<?php echo $fromDate->format('d/m/Y')?>" class="form-control" onchange="verifyDets('fromDate');">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" id="todte" name="todte" placeholder="To Date" value="<?php echo $toDate->format('d/m/Y')?>" class="form-control" onchange="verifyDets('toDate');">
                            </div>
                        </div>
                        <div id="requested_days" style="display:none;" class="alert alert-success mt-4">
                            Number of Days Requested: <span id="count"></span>
                        </div>
                        <div id="error_requested_days" style="display:none;" class="alert alert-danger mt-4">
                            You are requesting more than the available days. You requested: <span id="message_requested_days"></span> days. Available: <span id="message_remaining_days"></span> days.
                        </div>
                        <div class="form-actions form-group"><button type="button" id="confirmBtn" class="btn btn-primary" onClick="submitLeaveForm()">Confirm</button>  <button type="button" class="btn btn-secondary" onClick="back()">Back</button></div>
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


            $("#fromdte").datepicker({
                dateFormat: "dd/mm/yy",
                onSelect: function(dateText) {
                    verifyDets('fromdte');
                }
            });

            $("#todte").datepicker({
                dateFormat: "dd/mm/yy",
                onSelect: function(dateText) {
                    verifyDets('toDate');
                 }
                });
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
                        verifyDets('fromDate');
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
                const finalFromDate = parseDateToISO(fromDate);
                const finaltoDate = parseDateToISO(toDate);

                const formData = {
                    leaveType: leaveType,
                    fromDate: finalFromDate,
                    toDate: finaltoDate,
                    userid: userid,
                    id : lid
                };

            const startDate = parseDate(fromDate);
            const endDate = parseDate(toDate);

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

    function verifyDets(field){
        const leaveType = document.getElementById('leaveType').value;
        const fromDate = document.getElementById('fromdte').value;
        const toDate = document.getElementById('todte').value;
        const leaveBalance1 = $('#totRemaining').text();

        if( toDate && fromDate ){
            const fromDateString = document.getElementById('fromdte').value;
            const toDateString = document.getElementById('todte').value;

            const fromDate1 = parseDate(fromDateString);
            const toDate1 = parseDate(toDateString);
        
            if (toDate1 < fromDate1) {
                alert("To Date cannot be before From Date.");
                document.getElementById('todte').value = '';
                $('#requested_days').hide();
                $('#message_requested_days').hide();
                $('#message_remaining_days').hide();
                $('#error_requested_days').hide();
                $('#confirmBtn').hide();
                return false;
            }else{
                $('#confirmBtn').show();
            }
        }


        if(leaveType && fromDate && toDate){

            const formattedFromDate = formatDate(fromDate);
            const formattedToDate = formatDate(toDate);

            const startDate = parseDate(fromDate);
            const endDate = parseDate(toDate);

            const differenceInTime = endDate - startDate;
            const differenceInDays = differenceInTime / (1000 * 3600 * 24) + 1;

            $('#count').text(differenceInDays);

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

    function parseDate(dateString) {
        const [day, month, year] = dateString.split('/');
        return new Date(year, month - 1, day);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();

        return `${day}/${month}/${year}`;
    }

    function parseDateToISO(dateString) {
        const [day, month, year] = dateString.split('/');
        const dateObj = new Date(year, month - 1, day);
        
        const formattedDate = dateObj.toISOString().split('T')[0];
        return formattedDate;
    }
</script>

