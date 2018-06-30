<?php
/*---------------------------------------------------------------------------------------------------------------------------------
 *                                                  This is the side-bar(showing categories) for displaying products
 */
if (!isset($_SESSION))
    session_start();
?>
<style>
    .active{
        background: darkslategray;
        color: greenyellow;
    }
    li{
        cursor: pointer  
    }

</style>
<script>
    $(function(){
        $("#mybar").children().click(function(){
                
                $("#mybar").children().removeClass("active");
                $(this).addClass("active");
            });
    });
</script>
<div>
    <nav>
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#right_sidebar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                        
                </button>
                <a class="navbar-brand" style="font-size:16pt;text-align: center;padding: 10px;margin: 10px;"><b>Categories</b></a>
            </div>

            <div class="collapse navbar-collapse" id="right_sidebar">
                <ul class="nav nav-pills" id="mybar">
                    <li id="electronics" class="active"><a >Electronics</a></li>
                    <li id="clothing"><a >Clothing</a></li>
                    <li><a href="#">Grocery</a></li>
                    <li><a href="#">Toys</a></li>

                </ul>
            </div>
        </div>
    </nav>
</div>