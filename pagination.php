<?php 
    $limit = $settings['pagination']; 
	$targetpage = "index.php";
	$add = "";	
	
	if(isset($_GET['version'])) $add = "&version=".$_GET['version'];
	if(isset($_GET['country'])) $add = "&country=".$_GET['country'];
	if(isset($_GET['category'])) $add = "&category=".$_GET['category'];
	if(isset($_GET['sort'])) $add = "&sort=".$_GET['sort'];
	
    $query = "SELECT COUNT(*) as num FROM `servers` WHERE `disabled` = 0 {$offline_where} {$category_filter} {$country_filter} {$version_filter}"; 
    $total_pages = mysql_fetch_array(mysql_query($query)); 
    $total_pages = $total_pages['num']; 

    $stages = 3; 

    if (!isset($_GET['page'])) { $_GET['page'] = 0;} else {$page = $_GET['page'];} 
    $page = mysql_real_escape_string($_GET['page']); 
    if($page){ 
        $start = ($page - 1) * $limit; 
    }else{ 
        $start = 0; 
        } 

    // Get page data 
    $query1 = "SELECT * FROM `servers` LIMIT $limit"; 
    $result = mysql_query($query1); 

    // Initial page num setup 
    if ($page == 0){$page = 1;} 
    $prev = $page - 1; 
    $next = $page + 1; 
    $lastpage = ceil($total_pages/$limit); 
    $LastPagem1 = $lastpage - 1;         

if ($page == '1' && $total_pages <= $settings['pagination'] ){

} else {
    echo  "<ul class='pagination'>"; 
    $paginate = ''; 
   // if($lastpage > 1) 
   // {
        // Previous 
        if ($page > 1){ 
            echo  "<li><a href='$targetpage?page=$prev" . $add . "'><</a></li>"; 
        } 
        else{ 
            echo  "<li class='disabled'><a href='#'><</a></li>";    } 

        // Pages 
        if ($lastpage < 7 + ($stages * 2))    // Not enough pages to breaking it up 
        { 
            for ($counter = 1; $counter <= $lastpage; $counter++) 
            { 
                if ($counter == $page){ 
                    echo  "<li class='active'><a href='#'>$counter</a></li>"; 
                }else{ 
                    echo  "<li><a href='$targetpage?page=$counter" . $add . "'>$counter</a></li>";} 
            } 
        } 
        elseif($lastpage > 5 + ($stages * 2))    // Enough pages to hide a few? 
        { 
        // Beginning only hide later pages 
            if($page < 1 + ($stages * 2)) 
            { 
                for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) 
                { 
                    if ($counter == $page){ 
                        echo  "<li class='active'><a href='#'>$counter</a></li>"; 
                    }else{ 
                        echo  "<li><a href='$targetpage?page=$counter" . $add . "'>$counter</a></li>";} 
                } 
                echo  "<li><a href='#'>...</a></li>"; 
                echo  "<li><a href='$targetpage?page=$LastPagem1" . $add . "'>$LastPagem1</a></li>"; 
                echo  "<li><a href='$targetpage?page=$lastpage" . $add . "'>$lastpage</a></li>"; 
            } 
        // Middle hide some front and some back 
            elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) 
            { 
                echo  "<li><a href='$targetpage?page=1" . $add . "'>1</a></li>"; 
                echo  "<li><a href='$targetpage?page=2" . $add . "'>2</a></li>"; 
                echo  "<li><a href='#'>...</a><li>"; 
                for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) 
                { 
                    if ($counter == $page){ 
                        echo  "<li class='active'><a href='#'>$counter</a></li>"; 
                    }else{ 
                        echo  "<li><a href='$targetpage?page=$counter" . $add . "'>$counter</a></li>";} 
                } 
                echo  "..."; 
                echo  "<li><a href='$targetpage?page=$LastPagem1" . $add . "'>$LastPagem1</a></li>"; 
                echo  "<li><a href='$targetpage?page=$lastpage" . $add . "'>$lastpage</a></li>";         
            } 
        // End only hide early pages 
            else 
            { 
                echo  "<li><a class='button' href='$targetpage?page=1" . $add . "'>1</a></li>"; 
                echo  "<li><a class='button' href='$targetpage?page=2" . $add . "'>2</a><li>"; 
                echo  "<li><a href='#'>...</a></li>"; 
                for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) 
                { 
                    if ($counter == $page){ 
                        echo  "<li class='active'><a href='#'>$counter</a></li>"; 
                    }else{ 
                        echo  "<li><a href='$targetpage?page=$counter" . $add . "'>$counter</a></li>";} 
                } 
            } 
        } 
        // Next 
            if ($page < $counter - 1){
                echo  "<li><a href='$targetpage?page=$next" . $add . "'>></a></li>"; 
            }else{
				echo  "<li class='disabled'><a href='#'>></a></li>"; 
            } 
			          //  } 
 // Pagination 
echo  "</ul>"; 
}
?>