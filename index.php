<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

$basePath = rtrim(dirname($scriptName), '/');

define('BASE_URL', $protocol . $host . $basePath . '/');


if (!isset($_SESSION['USER_ID']) || $_SESSION['USER_ID'] <= 0 ) {
    header('Location: ' . BASE_URL . 'views/pages/login.php');
    exit(); 
}

?>
<!doctype html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HRS</title>
    <meta name="description" content="HRS">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>images/favicon.ico">
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>images/favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css"> 
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
        b {
            display: none;
        }
        .toast {
            visibility: hidden; 
            min-width: 250px;
            margin-left: -125px; 
            background-color: #f44336; 
            color: white; 
            text-align: center; 
            border-radius: 2px; 
            padding: 16px; 
            position: fixed; 
            z-index: 1; 
            left: 50%; 
            bottom: 30px; 
            font-size: 17px; 
            transition: visibility 0s, opacity 0.5s linear; 
            opacity: 0; 
        }

        .toast.show {
            visibility: visible; 
            opacity: 1;
        }
        .error-message {
            color: red; 
            background-color: #ffe6e6; 
            border: 1px solid red; 
            border-radius: 5px; 
            padding: 15px; 
            margin: 10px 0; 
            font-family: Arial, sans-serif; 
            font-size: 16px; 
            display: flex; 
            flex-direction: row; 
        }


    </style>
</head>
<body>
    <?php include_once('views/components/sidebar.php'); ?>
    <div id="right-panel" class="right-panel">
        <?php include_once('views/components/topnav.php'); ?>
        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1><?php echo $page?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <?php 
                if(isset($content)){
                    include_once($content); 
                }else{
            ?>
                <?php include_once('views/pages/home.php');?>
            <?php } ?>
        </div>
        <?php include_once('views/components/footer.php'); ?>
        <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['USER_ID']?>"/>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
    <script src="ssets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/widgets.js"></script>
    <script src="assets/js/widgets.js"></script>

    <!-- //Date tables -->
    <script src="<?php echo BASE_URL; ?>assets/js/lib/data-table/datatables.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/lib/data-table/dataTables.buttons.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/lib/data-table/jszip.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/lib/data-table/vfs_fonts.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/lib/data-table/buttons.html5.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/lib/data-table/buttons.print.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/lib/data-table/buttons.colVis.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/init/datatables-init.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var id = '<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>';
          
            if (id === '') {
                loadCards();
            }else{
                $('#bootstrap-data-table-export').DataTable();
            }
      } );
     
      function loadCards()  {
        const userid = $('#userid').val();
        if (userid) {
            const formData = {
                userid: userid
            };

            $.ajax({
                url: 'dashboard.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log('Response from server:', response);
                        $('.row').empty();

                        response.forEach(item => {
                            const cardHtml = `
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card text-white bg-flat-color-3">
                                        <div class="card-body">
                                            <div class="card-left pt-1 float-left">
                                                <span>${item.text}</span>
                                                <p class="text-light mt-1 m-0">Leave Type</p>
                                            </div>
                                            <div class="card-right float-right text-right">
                                                <i class="icon fade-5 icon-lg pe-7s-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card text-white bg-flat-color-1">
                                        <div class="card-body">
                                            <div class="card-left pt-1 float-left">
                                                <h3 class="mb-0 fw-r">
                                                    <span class="count">${item.total}</span>
                                                </h3>
                                                <p class="text-light mt-1 m-0">Total No of Days</p>
                                            </div>
                                            <div class="card-right float-right text-right">
                                                <i class="icon fade-5 icon-lg pe-7s-cart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card text-white bg-flat-color-6">
                                        <div class="card-body">
                                            <div class="card-left pt-1 float-left">
                                                <h3 class="mb-0 fw-r">
                                                    <span class="count">${item.taken}</span>
                                                </h3>
                                                <p class="text-light mt-1 m-0">Taken</p>
                                            </div>
                                            <div class="card-right float-right text-right">
                                                <div id="flotBar1" class="flotBar1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card text-white bg-flat-color-2">
                                        <div class="card-body">
                                            <div class="card-left pt-1 float-left">
                                                <h3 class="mb-0 fw-r">
                                                    <span class="count">${item.bal}</span>
                                                </h3>
                                                <p class="text-light mt-1 m-0">Balance</p>
                                            </div>
                                            <div class="card-right float-right text-right">
                                                <div id="flotLine1" class="flotLine1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#CardRows').append(cardHtml);
                        });
                        
                    }
                });
            } else {
                alert('Please fill in all fields.');
            }
    };


  </script>
</body>
</html>
<script type="text/javascript">
    function triggerMessage(msg, o) {
        new PNotify({
            title: "Success",
            type: "success",
            text: '' + msg + '',
            animation: "fade",
            delay: 2000,
            animate_speed: 'fast',
            addclass: "stack-topright",
            stack: stack_bottomleft

        });
    }


    function triggerError(msg, o) {
        new PNotify({
            title: 'Error',
            text: '' + msg + '',
            type: 'error',
            animation: "fade",
            delay: 2000,
            animate_speed: 'fast',
            addclass: "stack-topright",
            stack: stack_bottomleft

        });
    };
</script>
