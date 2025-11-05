<?php global $con;
/**
 * QUESTION 3
 *
 * For each month that had sales show a list of customers ordered by who spent the most to who spent least.
 * If the totals are the same then sort by customer.
 * If a customer has multiple products then order those products alphabetical.
 * Months with no sales should not show up.
 * Show the name of the customer, what products they bought and the total they spent.
 * Only show orders with the "Payment received" and "Dispatched" status.
 * If there are no results, then it should just say "There are no results available."
 *
 * Please make sure your code runs as effectively as it can.
 *
 * See test3.html for desired result.
 */
?>
<?php
//$con holds the connection
require_once('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Test3</title>
</head>
<body>
<h1>Top Customers per Month</h1>

<?php
$sql = "
    SELECT
        DATE_FORMAT(O.order_date, '%Y-%m') AS year_month_sort,
        DATE_FORMAT(O.order_date, '%M %Y') AS month_name,
        CONCAT(U.first_name, ' ', U.last_name) AS customer_name,
        SUM(P.price) AS total_spent,
        GROUP_CONCAT(P.product ORDER BY P.product ASC SEPARATOR '|||') AS product_list
    FROM
        orders O
    JOIN
        users U ON O.user_id = U.id
    JOIN
        order_items OI ON O.id = OI.order_id
    JOIN
        products P ON OI.product_id = P.id
    WHERE
        O.order_status_id IN (2, 3) 
    GROUP BY
        year_month_sort,
        month_name,
        O.user_id,
        customer_name
    ORDER BY
        year_month_sort ASC,
        total_spent DESC,
        customer_name ASC
";

$result = mysqli_query($con, $sql);


if (mysqli_num_rows($result) == 0) {
    echo "<p>There are no results available.</p>";
} else {
    $current_month = null;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['month_name'] != $current_month) {
            if ($current_month !== null) {
                echo "</tbody></table>";
            }

            $current_month = $row['month_name'];

            echo "<h2>" . $current_month . "</h2>";
            echo "<table width=\"800\">";
            echo "<tbody><tr>";
            echo "<th width=\"200\" align=\"left\">Customer</th>";
            echo "<th width=\"400\" align=\"left\">Products Bought</th>";
            echo "<th width=\"200\" align=\"right\">Total</th>";
            echo "</tr>";
        }

        echo "<tr>";
        echo "<td valign=\"top\">" . htmlspecialchars($row['customer_name']) . "</td>";

        echo "<td valign=\"top\">";
        $products = explode('|||', $row['product_list']);

        echo implode("<br>", array_map('htmlspecialchars', $products));
        echo "</td>";

        echo "<td valign=\"bottom\" align=\"right\">R " . number_format($row['total_spent'], 2, '.', ',') . "</td>";
        echo "</tr>";
    }

    if ($current_month !== null) {
        echo "</tbody></table>";
    }
}

mysqli_close($con);
?>

</body>
</html>