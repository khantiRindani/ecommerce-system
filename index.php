<?php
if(!isset($_SESSION))
    session_start();
$_SESSION['page']='home';
include("includes/header.php");
?>
<html lang="en">
    <head>
        <title>My design</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        

    </head>
    <body>
        <div class="container-fluid">

            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner">

                    <div class="item active">
                        <div class="row mySlides dark">
                            <div class="col-md-4 col-md-offset-1">
                                <img src="images/responsive_images/temp1.jpg" class="slide-img">
                            </div>
                            <div class="col-md-6">
                                <p class="slide-heading"><b>Lorem ipsum dolor sit amet,<br/> consectetur adipisicing elit.</b></p>
                                <br>
                                <p class="slide-content">Id, reprehenderit, consequatur neque est minima ad quos labore quibusdam molestias alias culpa obcaecati velit eos similique deleniti explicabo quis quod voluptatibus.</p>
                                <br><a class="slidebtn"><b>Read More</b></a>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="row mySlides dark">
                            <div class="col-md-4 col-md-offset-1">
                                <img src="images/responsive_images/temp2.jpg" class="slide-img">
                            </div>
                            <div class="col-md-6">
                                <p class="slide-heading"><b>Lorem ipsum dolor sit amet,<br/> consectetur adipisicing elit.</b></p>
                                <br>
                                <p class="slide-content">Id, reprehenderit, consequatur neque est minima ad quos labore quibusdam molestias alias culpa obcaecati velit eos similique deleniti explicabo quis quod voluptatibus.</p>
                                <br><a class="slidebtn"><b>Read More</b></a>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="row mySlides dark">
                            <div class="col-md-4 col-md-offset-1">
                                <img src="images/responsive_images/temp3.jpg" class="slide-img">
                            </div>
                            <div class="col-md-6">
                                <p class="slide-heading"><b>Lorem ipsum dolor sit amet,<br/> consectetur adipisicing elit.</b></p>
                                <br>
                                <p class="slide-content">Id, reprehenderit, consequatur neque est minima ad quos labore quibusdam molestias alias culpa obcaecati velit eos similique deleniti explicabo quis quod voluptatibus.</p>
                                <br><a class="slidebtn"><b>Read More</b></a>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div>



            <div class="row"></div>

            <div class="row" style="margin-top: 40px;">
                <div class="col-md-7 col-md-offset-1">
                    <span style="font-size: 18pt!important">Welcome to our WEBSITE!</span>

                    <div id='welcome-deco'></div>
                    <div id='welcome-deco2'></div>
                    <p>Simple Gray is a professional XHTML/CSS layout provided by www.example.com for free of charge.
                        <br/>You can use this template for any purpose.</p>
                    <br/><br/>
                    <div class="news">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minus, officiis aspernatur nam at accusantium quos recusandae esse alias maxime! Dolore, voluptatum, illum eum ipsam tenetur unde reiciendis saepe non aspernatur.</p>
                        <br/>
                        <ul>
                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut, eos, quae a quibusdam culpa vero quasi eligendi velit quo eveniet ratione iusto iste eum laudantium deserunt itaque mollitia. Consectetur, quo?</li>
                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, molestias, soluta, quidem, accusantium repudiandae qui quis vel dolores saepe itaque tempore eaque nulla perspiciatis asperiores dolor delectus libero non beatae?</li>
                        </ul>
                        <br><br>
                        <p id='welcome-verticle'>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, quisquam, recusandae, mollitia ea laborum quas repudiandae perspiciatis impedit modi eligendi laboriosam animi dicta in similique iure magnam at! Qu. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non, animi, rem porro pariatur iure repellendus ab obcaecati tempora consequatur cum aliquid cupiditate voluptate excepturi molestiae numquam ipsum cumque hic at!</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row" id="heading">
                        Latest News
                    </div>
                    <div class="row light-gray">
                        <div class="col-md-4">
                            <img src='images/responsive_images/flower4.jpg' width="100px" height="100px"/>
                        </div>
                        <div class="col-md-8">
                            <span class="news-date"><?php echo date('M d,Y'); ?><br/></span>
                            <a class="news-link"><b>Lorem ipsum dolor sit </b></a><br/>
                            <span class="news">consectetur adipisicing elit. Doloribus, nostrum, itaque, accusamus repellendus</span>
                        </div>
                    </div>
                    <div class="row dark-gray">
                        <div class="col-md-4">
                            <img src='images/responsive_images/flower1.jpg'  width="100px" height="100px"/>
                        </div>
                        <div class="col-md-8">
                            <span class="news-date"><?php echo date('M d,Y'); ?><br/></span>
                            <a class="news-link"><b>Lorem ipsum dolor sit </b></a><br/>
                            <span class="news">consectetur adipisicing elit. Doloribus, nostrum, itaque, accusamus repellendus</span>
                        </div>
                    </div>
                    <div class="row light-gray">
                        <div class="col-md-4">
                            <img src='images/responsive_images/flower3.jpg' width="100px" height="100px"/>
                        </div>
                        <div class="col-md-8">
                            <span class="news-date"><?php echo date('M d,Y'); ?><br/></span>
                            <a class="news-link"><b>Lorem ipsum dolor sit </b></a><br/>
                            <span class="news">consectetur adipisicing elit. Doloribus, nostrum, itaque, accusamus repellendus</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("includes/footer.php"); ?>
        </div>
       
       
    </body>
</html>

