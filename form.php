<?php
$all_errors = '';
$data = array(
    'name'    => htmlspecialchars($_POST['name']),
    'phone'   => htmlspecialchars($_POST['phone']),
    'message' => htmlspecialchars($_POST['message']),
    //'agree'   => htmlspecialchars($_POST['agree']),
);

if(isset($_FILES['photo'])) {
    $errors     = array();
    $maxsize    = 100097152;
    $acceptable = array(
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    );

    if($_FILES['photo']['size'] >= $maxsize) {
        $errors[] = 'Файл больше 100мб';
    }

    if(!in_array($_FILES['photo']['type'], $acceptable) && (!empty($_FILES["photo"]["type"]))) {
        $errors[] = 'Разрешены слудующие форматы для загрузки PDF, JPEG, JPG, GIF, PNG';
    }

    if(count($errors) === 0) {
        move_uploaded_file($_FILES['photo']['tmp_name'], '2mNndmobygevVjhbe68H/' . $_FILES['photo']['name']);
    } else {
        foreach($errors as $error) {
            $all_errors .= $error.'<br>';
        }
        die($all_errors);
    }
}

include "libmail.php"; // вставляем файл с классом
$m= new Mail; // начинаем
$m->From( "mail@partner.palazo.ru" ); // от кого отправляется почта
$m->To( "dmitriiperkov@gmail.com" ); // кому адресованно
$m->Cc( "unityspace@yandex.ru"); // копия письма отправится по этому адресу
$m->Subject( "Форма обратной связи" );
$m->Body( "Имя: ".$data['name']."\r\nТелефон: ".$data['phone']."\r\nСообщение: ".$data['message'] );
//$m->Bcc( "bcopy@asd.com"); // скрытая копия отправится по этому адресу
//$m->Priority(3) ; // приоритет письма
if(isset($_FILES['photo'])) {
    $m->Attach( "2mNndmobygevVjhbe68H/" . $_FILES['photo']['name'], "attach", $_FILES['photo']['type'] ) ; // прикрепленный файл
}
//$m->smtp_on( "smtp.asd.com", "login", "password" ) ; // если указана эта команда, отправка пойдет через SMTP
$m->Send(); // а теперь пошла отправка
echo 'Сообщение успешно отправлено';