
<!DOCTYPE html>
<html>
<body>
<div id="results_page" style="display: block;">
    <div class="HeaderWrapper">
			<form id="search_form" class="ResultInput" action="query.php" method="POST">
				<input type="text" id="search_bar" name="search_bar" placeholder="Enter Text" size="50" onfocus="this.value=''" class="MainInput" />
				<input type="submit" value="SNITCH!" />
			</form>
	</div>
	<div class="MiddleWrapper">
		<div id="result_area" class="result_area">
				<div id="dummy">
                <h1><?php
$title = "";
if (empty($_POST["search_bar"])){
    echo "Search bar is empty! Please type the name of the game you want to
    add to the database!";
}
else
{
    $query = sanitizeInput($_POST["search_bar"]);
    $serverName = "localhost";
    $userName = "root";
    $pass = "";
    $dbName = "GamesDB";

    $conn = mysqli_connect($serverName, $userName, $pass, "sys");
    mysqli_select_db($conn, $dbName);
    if(!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }
    else{
        $sqlInput = "SELECT * FROM games WHERE gameName LIKE '%$query%' LIMIT 5";
        $result = $conn->query($sqlInput);
        if($result->num_rows > 0)
        {
            while($row=$result->fetch_assoc())
            {
                $title = $row["gameName"];
            }
        }
        else
        {
            $title = "0 results";
        }
    }
    echo $title;
}

function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    return $input;
}
?></h1></div>
		</div>
    </div>
</div>
</body>

</html>
    