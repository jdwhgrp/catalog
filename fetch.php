<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'catalog');

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data based on search query and filtering criteria
if(isset($_POST['query'])){
    $search = $_POST['query'];
    $criteria = $_POST['criteria'];

    $sql = "SELECT * FROM catalogfinal WHERE 
            (Subject LIKE '%$search%' OR 
            `Book Title` LIKE '%$search%' OR 
            Author LIKE '%$search%' OR 
            Publisher LIKE '%$search%' OR 
            Place LIKE '%$search%' OR 
            ISBN LIKE '%$search%')";

    if (!empty($criteria['subject'])) {
        $sql .= " AND Subject LIKE '%{$criteria['subject']}%'";
    }

    if (!empty($criteria['author'])) {
        $sql .= " AND Author LIKE '%{$criteria['author']}%'";
    }

    if (!empty($criteria['publisher'])) {
        $sql .= " AND Publisher LIKE '%{$criteria['publisher']}%'";
    }

    $result = $conn->query($sql);

    $books = array(); // Initialize an empty array to store books

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            // Append each book as an associative array to the $books array
            $books[] = array(
                'Title' => $row['Book Title'],
                'Author' => $row['Author'],
                'Publisher' => $row['Publisher'],
                'Place' => $row['Place'],
                'ISBN' => $row['ISBN']
            );
        }
    }

    // Encode the $books array as JSON and output it
    echo json_encode($books);
} else {
    echo json_encode(array()); // If no query is provided, output an empty array
}

$conn->close();
?>
