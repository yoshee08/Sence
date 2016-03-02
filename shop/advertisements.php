

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/overall/header.php'; 


?>
    <!-- Bootstrap Core CSS -->
        <!-- Custom CSS -->
    <link href="css/shop-homepage.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https//senceservers.net/shop/css/carousel.css">
        <div class="row">

            <div class="col-md-3">
                <p class="lead">SenceServices</p>
                <div class="list-group">
                    <a style="color:black;" href="https://senceservers.net/shop/gfx.php" class="list-group-item">Custom Graphics</a>
                   <!-- <a style="color:black;" href="https://senceservers.net/shop/advertisements.php" class="list-group-item">Random Ad</a>-->
                    <!--<a style="color:black;" href="https://senceservers.net/shop/vip.php" class="list-group-item">VIP</a>-->
                </div>
            </div>

            <div class="col-md-9">

                <div class="row carousel-holder">

                    <div class="col-md-12" style="padding-left: 0px;">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item active">
                                <div class="item">
                                    <img class="slide-image" src="https://senceservers.net/shop/images/banner.jpg" alt="">
                                </div>
                            </div>
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="thumbnail">
                            <img src="https://senceservers.net/shop/images/Sence.png" alt="">
                            <div class="caption">
                                <h4 class="pull-right">$5.00/mo</h4>
                                <h4><a href="#">Random Ad</a>
                                </h4>
                                <p>For Just $0.16 a Day!</p>
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="sencegaming2014@gmail.com">
<input type="hidden" name="lc" value="BM">
<input type="hidden" name="item_name" value="random_ad">
<input type="hidden" name="item_number" value="01">
<input type="hidden" name="amount" value="5.00">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="tax_rate" value="6.000">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
<div class="form-group">
<input type="hidden" name="on0" value="Servers Name">Servers Name</td></tr><tr><td><input type="text" name="os0" maxlength="200"></td></tr>
<center>
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form><h5>SenceServers has a 'No Refund' Policy on all purchases</h5>
</center>


                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/overall/footer.php'; 
?>