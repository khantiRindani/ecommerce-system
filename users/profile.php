<?php
if (!isset($_GET['auth'])) {
    exit();
}
session_start();
$username = $_SESSION['username'];
include("../includes/connect.php");
include("../includes/header.php");

$record = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$username'");

if (count($record) == 1) {
   $n = mysqli_fetch_array($record);
   $id = $n['id'];            
   //echo $id;
}
?>

<html> 
    <head>
        <title>Users' profile</title>
        <script>
            $(function () {

                $('#update_form').load('../includes/common-ajax.php?type=loadForm&subtype=form_db&id='+$('#update_form').attr('name'));
                
                $(document).on('click','#other',function(){
                    $('#othertext').html("<input class='form-control' name='hobbytext' id='hobbytext'>");
                });
                $(document).on('click', '#update', function (){
                    var id = $('#update_form').attr('name');
                    //alert(id);
                    var data = new FormData();

//Form data
                    var form_data = $('#myform').serializeArray();
                    $.each(form_data, function (key, input) {
                        data.append(input.name, input.value);
                    });
                    
                    $.ajax({
                        type: "POST",
                        url: "<?php echo PATH;?>/includes/common-ajax.php?type=update&subtype=form_db&id=" + id,
                        data: data,
                        processData: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        cache: false,
                        //data: $("#myform").serialize(),
                        beforeSend: function () {
                            $('#updateResponse').focus();
                            $('#updateResponse').html('<span class="text-info">Loading response...</span>');
                        },
                        success: function (data) {
                            //$('#response').focus();
                            $('#updateResponse').html('<span class="text-info" style="color:red">' + data + '</span>');
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }

                    });

                });
            });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="row" id="updateResponse"></div>
            <div id="update_form" name="<?php echo $id;?>">
            </div>
               
        </div>
<?php include("../includes/footer.php"); ?>
    </body>
</html>