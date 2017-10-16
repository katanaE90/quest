<?php
require_once "User_Password_etc.php";

$mysqli = new mysqli("localhost", $user, $password, $db);

if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$query = "SELECT * FROM test"; //Так как нам нужна вся информация с таблицы, извлекаем все данные.

if ($result = $mysqli->query($query)) {

    while ($row[] = $result->fetch_assoc()) {
    }
    foreach ($row as $v) {
        if (!isset($v)) continue;   // Массив данных из БД сразу же переформируем в массив необходимого нам вида.
        $value = $v["ident"];;
        $elements_db[$value]["value"] = $v["value"];
        $elements_db[$value]["version"] = $v["version"];
    }



    foreach ($_GET["ident"] as $k => $v) {
        $ident = "ident$k";
        if (!isset($elements_db[$ident])) {             //Проверяем есть ли БД уникальный ИД, который пришел из запроса.
            $array["delete"][] = $ident;                //Все то, чего нет в БД записываем в массив 'delete'.
        } else {                                        //Все остальное сравниваем по версии. Если версия в БД выше,
            if ($elements_db[$ident]["version"] >= $_GET["version"][$k]) { //записываем в массив 'update', и удаляем с
                $array["update"][$ident] = $elements_db[$ident];            // первого массива.
                unset ($elements_db[$ident]);
            }
        }

    }
    $array ["new"] = $elements_db;                      // Выводим оставшиееся элементы первого массива в массив
                                                        // 'new'.
}


$mysqli->close();

/*echo "<pre>";
print_r($array);
echo "<pre>";*/

return $array;
