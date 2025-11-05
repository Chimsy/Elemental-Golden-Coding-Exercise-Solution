<?php
/**
 * QUESTION 1
 *
 * Create a form with a single textarea that will sort words or phrases alphabetically separated by commas.
 * Validate that the field is not empty.
 * Clean up the string to remove any extra spaces and unnecessary commas
 * The result should be shown below the form.
 *
 * Please make sure your code runs as effectively as it can.
 *
 * The end result should look like the following:
 * apples, cars, tables and chairs, tea and coffee, zebras
 */

$sorted = '';
$error = '';
$input = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = trim(isset($_POST['to_sort']) ? $_POST['to_sort'] : '');

    if ($input === '') {
        $error = 'Please enter at least one word or phrase.';
    } else {
        $items = array_filter(array_map('trim', explode(',', $input)));

        if (empty($items)) {
            $error = 'No valid items found. Check your commas and spaces.';
        } else {
            sort($items);
            $sorted = implode(', ', $items);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sort List</title>
</head>
<body>
<h1>Sort List</h1>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="action" value="sort"/>
    <label for="to_sort">Please enter the words/phrases to be sorted separated by commas:</label><br/>

    <textarea name="to_sort" id="to_sort" style="width: 400px; height: 150px;"><?= htmlspecialchars($input) ?></textarea><br/>

    <input type="submit" value="Sort"/>
</form>

<?php if ($sorted): ?>
    <div class="result">
        <strong><?= htmlspecialchars($sorted) ?></strong>
    </div>
<?php endif; ?>

</body>
</html>