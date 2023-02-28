<?php
$pdo = new PDO("mysql:host=localhost:3306;dbname=kim_kinotower", "root", "");

if (isset($_POST["category_name"])) {
    $name = $_POST["category_name"];
    if (!empty($_POST["parent_category_id"])) {
        $category_id = $_POST["parent_category_id"];
        $sql = "INSERT INTO categories(name, parent_id) VALUES(?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $category_id]);
    } else {
        $sql = "INSERT INTO categories(name, parent_id) VALUES(?, NULL)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name]);
    }
    unset($_POST["parent_category_id"]);
    unset($_POST["category_name"]);
}

if (isset($_POST["delete_id"])) {
    $id = $_POST["delete_id"];
    $sql = "DELETE FROM categories WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
    <title>Document</title>
    <style>
        .btn__delete {
            background: none;
            border: none;
            color: red;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <form action="" method="post">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="category_name" id="floatingInput" placeholder="name@example.com">
              <label for="floatingInput">Category Name</label>
            </div>
            <div class="form-floating">
              <input type="number" class="form-control" name="parent_category_id" id="floatingPassword" placeholder="Password">
              <label for="floatingPassword">Parent Category ID</label>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">Submit</button>
            </div>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Parent Category ID</th>
                    <th>Parent Category Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php 
                if($pdo)
                    $result = $pdo->query("SELECT * FROM categories");
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)):

                ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= $row["name"] ?></td>
                    <td><?= $row["parent_id"] ?? "NULL" ?></td>
                    <td>
                        <?php 
                        if($row["parent_id"]) {
                            $parent_result = $pdo->query("SELECT name FROM categories WHERE id=" . $row["parent_id"]);
                            $parent_result_assoc = $parent_result->fetch(PDO::FETCH_ASSOC);
                            echo $parent_result_assoc["name"];
                        } else echo "NULL";
                        ?>
                    </td>
                    <td><form method="post">
                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                        <button class="btn__delete"><i class="fa-regular fa-trash-slash"></i></button>
                    </form></td>
                </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
</body>
</html>