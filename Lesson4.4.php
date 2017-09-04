<?php
$pdo = new PDO("mysql:host=localhost;dbname=Lesson4.4;charset=utf8", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$sqlTables = "SHOW TABLES";
$stmTables = $pdo->query($sqlTables);
if (!empty($_GET['tables'])) {
    $sqlTable = "DESCRIBE {$_GET['tables']}";
    $stmtTable = $pdo->prepare($sqlTable);
    $stmtTable->execute();
}
if (!empty($_POST['create_table']) && !empty($_POST['table_name'])) {
    $sqlCreateTable = "CREATE TABLE {$_POST['table_name']} (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(50) NOT NULL,
      PRIMARY KEY (`id`)
      ) ENGINE = InnoDB DEFAULT CHARSET = utf8";
    $stmtCreateTable = $pdo->prepare($sqlCreateTable);
    $stmtCreateTable->execute();
}

if (!empty($_GET['tables']) && !empty($_POST['create_fields']) && !empty($_POST['new_field_name']) && !empty($_POST['data_type'])) {
    $sqlAddField = "ALTER TABLE {$_GET['tables']} ADD {$_POST['new_field_name']} {$_POST['data_type']}";
    $stmtAddField = $pdo->prepare($sqlAddField);
    $stmtAddField->execute();
}

if (!empty($_GET['tables']) && !empty($_POST['delete_fields']) && !empty($_POST['new_field_name'])) {
    $sqlDelete = "ALTER TABLE {$_GET['tables']} DROP COLUMN {$_POST['new_field_name']}";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute();
}

if (!empty($_GET['tables']) && !empty($_POST['update_fields']) && !empty($_POST['name_field']) && !empty($_POST['rename_field']) && !empty($_POST['data_typ'])) {
    $sqlChange = "ALTER TABLE {$_GET['tables']} CHANGE {$_POST['name_field']} {$_POST['rename_field']} {$_POST['data_typ']}";
    $stmtChange = $pdo->prepare($sqlChange);
    $stmtChange->execute();
}

?>

<!DOCTYPE html>
<body>
<style>
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }
    table td, table th {
        border: 3px solid #c0ccb4;
        padding: 10px;
    }
    table th {
        background: #ddeed5;
    }
</style>
 <div>
     <h3>Добавить таблицу</h3>
      <form method="POST">
         <input type="text" name="table_name" placeholder="Название таблицы">
         <input type="submit" name="create_table" value="Создать">
      </form>
 </div>
     <h3>Выбрать таблицу</h3>
      <ul>
        <?php foreach ($stmTables as $row) { ?>
          <li>
            <a href="?tables=<?php echo htmlspecialchars($row[0]); ?>"><?php echo htmlspecialchars($row[0]); ?></a>
          </li>
            <?php } ?>
      </ul>
    <?php if (!empty($_GET['tables'])) { ?>
        <form method="POST">
            <table>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                    <th>Default</th>
                    <th>Extra</th>
                </tr>
                <?php while ($rowTable = $stmtTable->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rowTable['Field']); ?></td>
                        <td><?php echo htmlspecialchars($rowTable['Type']); ?></td>
                        <td><?php echo htmlspecialchars($rowTable['Null']); ?></td>
                        <td><?php echo htmlspecialchars($rowTable['Key']); ?></td>
                        <td><?php echo htmlspecialchars($rowTable['Default']); ?></td>
                        <td><?php echo htmlspecialchars($rowTable['Extra']); ?></td>
                    </tr>
                <?php } ?>
            </table>
            <div>
                <h3>Добавить/Удалить поле</h3>
                <input type="text" name="new_field_name" placeholder="Имя поля">
                <input type="text" name="data_type" placeholder="Тип данных">
                <input type="submit" name="create_fields" value="Добавить">
                <input type="submit" name="delete_fields" value="Удалить">
                <h3>Изменить поле</h3>
                <input type="text" name="name_field" placeholder="Имя поля">
                <input type="text" name="rename_field" placeholder="Новое имя">
                <input type="text" name="data_typ" placeholder="Тип данных">
                <input type="submit" name="update_fields" value="Применить">
            </div>
        </form>
    <?php } ?>
</body>
</html>

