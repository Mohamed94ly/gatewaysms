<?php
    require 'config.php';
    session_start();
    if(!isset($_SESSION['user_login'])){
        header('Location: index.php');
    }

    try{
        $stmt = $db->prepare('SELECT year(create_at) s_year, month(create_at) s_month, count(*) num FROM logsms group by year(create_at),month(create_at) order by s_year, s_month;');
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
                <th>Year/Month</td>
                <th>Count Message</td>
            </tr>
            <?php if($iterator) { 
                $i = 1; 
                foreach ($iterator as $row) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row['s_year'] . '/' . $row['s_month']; ?></td>
                        <td><?php echo $row['num']; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>

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