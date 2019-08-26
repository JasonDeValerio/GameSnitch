
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

    //Connect to DB
    $conn = mysqli_connect($serverName, $userName, $pass, $dbName);
    mysqli_select_db($conn, $dbName);
    if(!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }
    else
    {
        //Create Search Query String
        $sqlInput = "SELECT * FROM games WHERE gameName LIKE '%$query%' LIMIT 5";
        //Attempt Query
        $result = $conn->query($sqlInput);
        
        //If any matches are found
        if($result->num_rows > 0)
        {
            while($row=$result->fetch_assoc())
            {
                $title = $row["gameName"];
                $image = $row["imageURL"];
                echo "<div><img src=$image><h4>$title</h4></div>";
            }
        }
        //If no matches are found on the first pass
        else
        {
            $searchItem = sanitizeInput($_POST["search_bar"]);

            //Make API call to attempt a match
            $results = getGame($searchItem);
            
            //Base input query string
            $inputBase = 'INSERT INTO games (gameName, gameDesc, imageURL, releaseDate, website) VALUES (';
            
            //For each item in api call results, assemble columns from data
            foreach($results as $value)
            {
                $name = $value->name;
                $summary = 'No summary';
                if(property_exists($value, 'summary'))
                {
                    $summary = addslashes($value->summary);
                }
                $cover = 'No cover Art';
                if(property_exists($value, 'cover'))
                {
                    $cover = addslashes($value->cover->url);
                }
                $release_date = 'Not Yet Released';
                if(property_exists($value, 'first_release_date'))
                {
                    $release_date = date('m.d.y', $value->first_release_date);
                }
                $website = 'No Website';
                if(property_exists($value, 'websites'))
                {
                    $website = addslashes($value->websites[0]->url);
                }

                //Complete query string with API call result
                $query = "$inputBase" . "\"$name\", \"$summary\", \"$cover\", \"$release_date\", \"$website\");";
                $conn->query($query);
            }

            //Attempt second pass at DB query
            $sqlInput = "SELECT * FROM games WHERE gameName LIKE '%$query%' LIMIT 5";
            $result = $conn->query($sqlInput);
            
            //If matches are found
            if($result != null)
            {
                while($row=$result->fetch_assoc())
                {
                    $title = $row["gameName"];
                    $image = $row["imageURL"];
                    echo "<div><img src=$image><h4>$title</h4></div>";
                }
            echo "<script>document.getElementById('search_bar').value=\"" . sanitizeInput($_POST["search_bar"]) . "\";document.getElementById('search_form').submit();</script>";
            }
            //If DB is still lacking matches
            else
            {
                echo "0 Results Found.";
            }
        }
    }
}

//Format input for mysql query use
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    return $input;
}

//API call to IGDB for games whose title matches input param
function getGame($input)
{
    //replace spaces
    $query = urlencode($input);
    //create curl
    $curl = curl_init();
    //set url
    $url = "https://api-v3.igdb.com/games/?fields=name,summary,cover.url,first_release_date,websites.url&search=$query";
    curl_setopt($curl, CURLOPT_URL, $url);
    //set user agent
    curl_setopt($curl, CURLOPT_USERAGENT, "Devotastic");
    //set to post
    curl_setopt($curl, CURLOPT_POST, false);

    //Borrowed from online
    // Fail the cURL request if response code = 400 (like 404 errors) 
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    // Wait for 10 seconds to connect, set 0 to wait indefinitely
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    // Execute the cURL request for a maximum of 50 seconds
    curl_setopt($curl, CURLOPT_TIMEOUT, 50);
    // Do not check the SSL certificates
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    //set headers
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'user-key: b0d87f5b4f023c0d6ee4747fc28273c6',
        'Accept: application/json',
        'Content-Type: application/json',
    ));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    //Execute
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure:" . curl_error($curl));}
    curl_close($curl);

    return json_decode($result);
}
?></h1></div>
		</div>
    </div>
</div>
</body>

</html>
    