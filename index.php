<?php
// JSONPlaceholder API endpoint for posts
$api_url = "https://jsonplaceholder.typicode.com/posts";

// per page limit
$posts_per_page = 10;

try {
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;

    $start_index = ($page - 1) * $posts_per_page;

    $request_url =
        $api_url . "?_start=" . $start_index . "&_limit=" . $posts_per_page;

    // Add search functionality
    if (isset($_GET["search"]) && !empty($_GET["search"])) {
        $search_query = urlencode($_GET["search"]);
        $request_url .= "&q=" . $search_query;
    }

    // Add sorting options
    if (isset($_GET["sort"])) {
        $sort_by = in_array($_GET["sort"], ["title", "userId"])
            ? $_GET["sort"]
            : "id";
        $request_url .= "&_sort=" . $sort_by;

        // Check for sorting order (ascending or descending)
        $sort_order =
            isset($_GET["order"]) && $_GET["order"] === "desc" ? "desc" : "asc";
        $request_url .= "&_order=" . $sort_order;
    }

    $response = file_get_contents($request_url);

    if ($response === false) {
        throw new Exception("Error fetching posts from JSONPlaceholder API");
    }

    // Decode the JSON response
    $posts = json_decode($response, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception(
            "Error decoding JSON response from JSONPlaceholder API"
        );
    }

    // Display pagination
    $total_posts = count(json_decode(file_get_contents($api_url)));
    $total_pages = ceil($total_posts / $posts_per_page);
} catch (Exception $e) {
    // Display error message to the user
    echo "An error occurred: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web App - index </title>
    <link rel="stylesheet" href="/public/index.css">
</head>
<body>
   <header class="header">
      <div>
         <h3>Web App</h3>
      </div>
      <div class="sort">
         <?php echo '<p>Sort by: <a href="?sort=title&order=' . (isset($sort_by) === 'title' && $sort_order === 'asc' ? 'desc' : 'asc') . '">Title</a> | <a href="?sort=userId&order=' . (isset($sort_by) === 'userId' && $sort_order === 'asc' ? 'desc' : 'asc') . '">Author</a></p>'; ?>
      </div>
      <div class="search">
         <form method="GET" action="">
            <label for="search">Search:</label>
            <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
         </form>
      </div>
   </header>
   <main>
      <div class="content">
         <?php foreach ($posts as $post) { ?>
         <div class="post">
            <h3>Id : <?php echo htmlspecialchars($post['id']) ?></h3>
            <br>
            <h3>Titel : <?php echo htmlspecialchars($post['title']) ?></h3>
            <br>
            <h4>Body: <?php echo htmlspecialchars($post['body']) ?></h4>
            <br>
            <h3>Author: <?php echo htmlspecialchars($post['userId']) ?> </h3>
         </div>
         <?php }?>   
      </div>
      <div class="pagination">
         <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
         <?php echo '<a href="?page=' . $i . '">' . $i . '</a>'?>
         <?php } ?>
      </div>
   </main>
   <footer></footer>
</body>
</html>
