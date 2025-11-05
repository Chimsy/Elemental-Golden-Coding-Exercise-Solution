<?php global $con;
/**
 * QUESTION 2
 *
 * Using the data stored in the database
 * show a list of products with their prices
 * grouped by category.
 * The categories should be listed in alphabetical order.
 * The products within those categories should also be listed in alphabetical order.
 * Products with no category will be categorized as "Uncategorized".
 * If there are no results, then it should just say, "There are no results available."
 *
 * Please make sure your code runs as effectively as it can.
 *
 * See test2.html for desired result.
 */
?>
<?php
//$con holds the connection
require_once('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Test2</title>
</head>
<body>
<h1>Products</h1>

<?php
$sql = "
    SELECT
        p.product,
        p.price,
        IFNULL(c.category, 'Uncategorized') AS category_name
    FROM
        products p
    LEFT JOIN
        categories c ON p.category_id = c.id
    ORDER BY
        category_name ASC,
        p.product ASC
";

$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<p>There are no results available.</p>";
} else {
    $current_category = null;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['category_name'] != $current_category) {

            if ($current_category !== null) {
                echo "    </tbody>\n</table>\n";
            }

            $current_category = $row['category_name'];

            echo "<h2>" . htmlspecialchars($current_category) . "</h2>\n";
            echo "<table width=\"400\">\n";
            echo "    <tbody>\n";
            echo "    <tr>\n";
            echo "        <th align=\"left\">Product</th>\n";
            echo "        <th align=\"right\">Price</th>\n";
            echo "    </tr>\n\n";
        }

        echo "    <tr>\n";
        echo "        <td>" . htmlspecialchars($row['product']) . "</td>\n";
        echo "        <td align=\"right\">R " . number_format($row['price'], 2, '.', '') . "</td>\n";
        echo "    </tr>\n\n";
    }

    if ($current_category !== null) {
        echo "    </tbody>\n</table>\n";
    }
}

mysqli_close($con);
?>

</body>
</html>