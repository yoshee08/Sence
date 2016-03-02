<?php 
include 'core/init.php';
protect_page();
include 'includes/overall/header.php'; 
require_once('core/functions/recaptchalib.php');

$data = mysql_fetch_assoc(mysql_query("SELECT `id` FROM `servers` WHERE `user_id` = '$session_user_id'"));
?>
<head>
<link rel="stylesheet" type="text/css" href="/includes/css/style.min.css"> 
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<h2>Bid for Sponsor slots!!</h2>
      <div class="section-head">
            <span class="icon server"></span>
            <h1>
                                    Sponsored Servers
                            </h1>
        </div> 
        <div class="content-area auction-text">
            <p>Sponsored servers appear above the main list on the homepage and are the very first servers potential players see when they visit the website. Sponsored servers also show at the top of relevant search results and category pages. </p>
            <p>There are a total of 5 sponsored slots available at any one time and they are open for auction every month. Payment can be made using either PayPal.</p>
            <p>Failure to pay for a bid will result in a 6 month hold placed on the server, which will exempt if from being able to be put up for auction until the ban is lifted</p>
        </div>

                
           <div id="left">
            <div class="tabs no-margin">
                <ul class="tab-links section-head">
                    <li class="tab-link-active">
                        <a href="#">
                        Top 5 Bids
                                                    </a>
                    </li>
                    <li>
                        <a href="bid.php">
                        Place your Bid
                                                    </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="top" class="tab active">
                        <table class="server-info1 top-bids top-five">
                        <tbody><tr>
                     <b>    <td class="header server">Server</td>
                            <td class="header amt">Amount</td></b>
                           <!--<td class="header amt">Pay</td></b>-->
                                                    </tr>
                                                                            
                                <?php
                                $info = mysql_query("SELECT `id`, `name`, `price` FROM `servers` WHERE price > 0 ORDER BY `price` DESC LIMIT 5");
                                while($row = mysql_fetch_array($info)){
                                echo "<tr><td>" . $row['name'] . "</td>";
                                echo "<td>$" . $row['price'] . "</td>";
                                #echo "<td><a href=' https://www.paypal.com/cgi-bin/webscr?business=sencegaming2014@gmail.com&cmd=_xclick&currency_code=USD&amount=". $row['price'] ."&item_name=Sponsored slot for " . $row['name'] . "'>Pay</a> </td><tr> ";
                          }
                                ?>
                           
                                            </tbody></table>
                    </div>
                                    </div>
            </div>
        </div>
           <div id="left">
            <div class="tabs no-margin">
                <ul class="tab-links section-head">
                    <li class="tab-link-active">
                        <a href="#">
                        Bidding Status
                                                    </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="top" class="tab active">
                        <table class="server-info1 top-bids top-five">
                        <tbody><tr>
                       <br> <td class="header server">Start Date</td>
                            <td class="header server">End Date</td>
                            <td class="header server">Status</td></b>
                                                    </tr>
                                                                            
                                <?php
                                $info = mysql_query("SELECT `startdate`, `enddate`, `status`, `bidid` FROM `bids`");
                                while($row = mysql_fetch_array($info))
                                {
                                $time = strtotime($row['startdate']);
                                $startdate = date("m/d/y g:i A", $time);
                                $time1 = strtotime($row['enddate']);
                                $enddate = date("m/d/y g:i A", $time1);
                                echo "<tr><td>" . $startdate . "</td>";
                                echo "<td>" . $enddate . "</td>";
                                echo "<td>" . $row['status'] . "</td><tr>";
                                }
                                ?>
                           
                                            </tbody></table>
                    </div>
                                    </div>
            </div>
        </div>

        
<?php include 'includes/overall/footer.php'; ?>