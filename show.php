<?php
    require 'config.php';
    session_start();
    if(!isset($_SESSION['user_login'])){
        header('Location: index.php');
    }

    try{
        $total = $db->query("SELECT COUNT(*) FROM logsms")->fetchColumn();

        // How many items to list per page
        $limit = 25;

        // How many pages will there be
        $pages = ceil($total / $limit);

        // What page are we currently on?
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));

        // Calculate the offset for the query
        $offset = ($page - 1)  * $limit;
    
        // Some information to display to the user
        $start = $offset + 1;
        $end = min(($offset + $limit), $total);

        // The "back" link
        $prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
    
        // The "forward" link
        $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';
    
        // Display the paging information
        // echo '<div class="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';
    
        // Prepare the paged query
        $stmt = $db->prepare('SELECT * FROM logsms ORDER BY create_at DESC LIMIT :limit OFFSET :offset');
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        // Do we have any results?
        if ($stmt->rowCount() > 0) {
            // Define how we want to fetch the results
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $iterator = new IteratorIterator($stmt);
    
            // Display the results
    
        } else {
            echo '<p>No results could be displayed.</p>';
        }
    }catch (Exception $e) {
        // echo '<p>', $e->getMessage(), '</p>';
    }
?>
<html>
    <head>
        <title>Message</title>

        <style>
            body{
                font-family: sans-serif;
            }
            .message {
                margin: auto;
                font-family: sans-serif;
                border-collapse: collapse;
                width: 80%;
            }

            .message td, .message th {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: center;
                direction: rtl;
            }

            .message tr:nth-child(even){background-color: #f2f2f2;}

            .message tr:hover {background-color: #ddd;}

            .message th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: center;
                background-color: #04AA6D;
                color: white;
            }

            .paging{
                text-align: center;
            }

            .paging a, .paging span {
                color: black;
                padding: 8px 16px;
                text-decoration: none;
            }

            .paging a.active {
                background-color: #4CAF50;
                color: white;
            }

            .paging a:hover:not(.active) {background-color: #ddd;}

            .disabled{
                display: none;
            }

            .copyright{
                margin-top: 25px;
                width: 100%;
                text-align: center;
                line-height: 1.5;
            }
        </style>
    </head>
    <body>
        
        <table class="message">
            <tr>
                <th>#</td>
                <th>Create Date</td>
                <th>Phone</td>
                <th>Message</td>
            </tr>
            <?php 
                if($_GET['page'] == 1 || !isset($_GET['page']))
                    $i = 1; 
                else
                    $i = 25 * ($_GET['page'] - 1) + 1; 
            
            ?>
            <?php if($iterator) { ?>
                <?php foreach ($iterator as $row) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row['create_at']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['msg']; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>

        <div class="paging">
            <p>
                <?php 
                    echo $prevlink;

                    if($_GET['page'] - 4 > 1)
                        $startPage = $_GET['page'] - 4;
                    else
                        $startPage = 0;

                    $range_pages = $startPage + 6;

                    for($i=$startPage; $i < $range_pages and $i < $pages; $i++)
                    {
                        if($i + 1 == $_GET['page'])
                            echo ' <a class="active" href="?page=',$i + 1,'">', $i + 1, '</a> ';
                        else
                            echo ' <a href="?page=',$i + 1,'">', $i + 1, '</a> ';
                    }
                    // echo ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ';
                    echo $nextlink; 
                ?>
            </p>
        </div>

        <div class="copyright">
            <a href="show.php">Home</a>&#9 | &#9
            <a href="statistics.php">Statistics</a>&#9 | &#9
            <a href="logout.php">Logout</a><br>
            Copyright &copy;<?php echo date("Y"); ?>. <a href="http://codelab.ly">CodeLab</a>
        </div>
        <h4></h4>
        <h4></h4>
    </body>
</html>