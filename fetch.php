<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'catalog');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data based on search query and filtering criteria
if (isset($_POST['query'])) {
    // Retrieve search query and criteria from POST data
    $search = $_POST['query'];
    $criteria = $_POST['criteria'];

    // Construct SQL query
    $sql = "SELECT * FROM catalogfinal WHERE 
            (Subject LIKE '%$search%' OR 
            `Book Title` LIKE '%$search%' OR 
            Author LIKE '%$search%' OR 
            Publisher LIKE '%$search%' OR 
            Place LIKE '%$search%' OR 
            ISBN LIKE '%$search%')";

    // Append additional filtering conditions based on criteria
    if (!empty($criteria['subject'])) {
        $sql .= " AND Subject LIKE '%{$criteria['subject']}%'";
    }

    if (!empty($criteria['author'])) {
        $sql .= " AND Author LIKE '%{$criteria['author']}%'";
    }

    if (!empty($criteria['publisher'])) {
        $sql .= " AND Publisher LIKE '%{$criteria['publisher']}%'";
    }

    // Execute the SQL query
    $result = $conn->query($sql);

    // Initialize an array to store books
    $books = array();

    // Fetch and format the results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format each book data into an associative array
            $book = array(
                'Title' => $row['Book Title'],
                'Author' => $row['Author'],
                'Publisher' => $row['Publisher'],
                'Place' => $row['Place'],
                'ISBN' => $row['ISBN']
            );

            // Add the formatted book data to the books array
            $books[] = $book;
        }
    }

    // Encode the books array as JSON and output it
    echo json_encode($books);
} else {
    // If no query is provided, output an empty array
    echo json_encode(array());
}

// Close the database connection
$conn->close();
?>
