<?php 
$link = mysqli_connect("localhost:3306", "root", "", "kim_kinotower");

if ( !$link ) {
    echo "ERROR:"
    . mysqli_connect_error();
    die;
}

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $parent_id = $_POST['parent_id'];
    
    $check_sql = "SELECT id FROM categories WHERE id = ?";
    $check_stmt = mysqli_prepare($link, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $parent_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $insert_sql = "INSERT INTO categories(name, parent_id) VALUES (?, ?)";
        $insert_stmt = mysqli_prepare($link, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "si", $name, $parent_id);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);
        header("Location: index.php");
    } else {
        $insert_sql = "INSERT INTO categories(name, parent_id) VALUES (?, NULL)";
        $insert_stmt = mysqli_prepare($link, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "s", $name);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);
        header("Location: index.php");
    }
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM categories WHERE id=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: index.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form action="" method="post" class="ms-2">
  <div class="mb-3 col-6">
    <label class="form-label ms-2">Category Name</label>
    <input type="text" class="form-control" name="name" required>
  </div>
  <div class="mb-3 col-6">
    <label class="form-label ms-2">Parent Category ID</label>
    <input type="text" class="form-control" name="parent_id">
  </div>
      <button type="submit" class="btn btn-primary">Submit</button>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Category ID</th>
            <th>Category Name</th>
            <th>Parent Category ID</th>
            <th>Parent Catrgory Name</th>
            <th></th>
        </tr>
    </thead>
    <tbody class="table-group-divider">
<?php 
if ($link)
    $result = mysqli_query($link, "SELECT * FROM categories");
    while ($data = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $data["id"] ?></td>
            <td><?= $data["name"] ?></td>
            <td><?= $data["parent_id"] ?? "NULL" ?></td>
            <td>
                <?php 
                if($data["parent_id"]) {
                    $parent_result = mysqli_query($link, "SELECT name FROM categories WHERE id=" . $data['parent_id']);
                    $parent_data = mysqli_fetch_assoc($parent_result);
                    echo $parent_data["name"];
                } else {
                    echo "NULL";
                }
                ?>
            </td>
            <td>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?= $data['id']; ?>">
                    <button style="background: none; border: none; cursor:pointer;"> <i style="color: red;" class="fa-regular fa-trash-can-xmark fa-xl"></i> </button>
                </form>
            </td>
        </tr>

<?php endwhile; ?>
</tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>
<?php mysqli_close($link); ?>