<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts ($surname, $name, $patronomyc) {
    return mb_convert_case($surname . ' ' . $name . ' ' . $patronomyc, MB_CASE_TITLE);
}

function getPartsFromFullname($fullName) {
    $arr = explode(' ', $fullName);

    return ['surname' => $arr[0], 'name' => $arr[1], 'patronomyc' => $arr[2]];
}

function getShortName($fullName) {
    $fullName = getPartsFromFullname($fullName);

    return $fullName['name'] . ' ' . mb_substr($fullName['surname'], 0, 1) . '.';
}

function getGenderFromName($fullName) {
    $fullName = getPartsFromFullname($fullName);
    $gender = 0;
    // Отчество
    if (mb_substr($fullName['patronomyc'], -3) == 'вна') {
        $gender--;
    } else if (mb_substr($fullName['patronomyc'], -2) == 'ич') {
        $gender++;
    }
    // Имя
    if (mb_substr($fullName['name'], -1) == 'а') {
        $gender--;
    } else if (mb_substr($fullName['name'], -1) == 'й' || mb_substr($fullName['name'], -1) == 'н') {
        $gender++;
    }
    // Фамилия
    if (mb_substr($fullName['surname'], -2) == 'ва') {
        $gender--;
    } else if (mb_substr($fullName['surname'], -1) == 'в') {
        $gender++;
    }

    if ($gender < 0) {
        return 1;
    } else if ($gender > 0) {
        return -1;
    } else {
        return 0;
    }
}

function getGenderDescription($personArray) {
    $genderMaleCounter = 0;
    $genderFemaleCounter = 0;
    $genderindefinedCounter = 0;
    for ($i=0; $i < count($personArray); $i++) {
        $fullName = $personArray[$i]['fullname'];
        $gender = getGenderFromName($fullName);
        
        if ($gender == 1) {
            $genderFemaleCounter++;
        }

        if ($gender == -1) {
            $genderMaleCounter++;
        }
        
        if ($gender == 0) {
            $genderindefinedCounter++;
        }
    }

    echo 'Гендерный состав аудитории:' . '<br>';
    echo '------------------------------------' . '<br>';
    echo 'Мужчины - ' . round($genderMaleCounter * 100 / count($personArray), 1 ) . '%' . '<br>';
    echo 'Женщины - ' . round($genderFemaleCounter * 100 / count($personArray), 1) . '%' .  '<br>';
    echo 'Неопределенно - ' . round($genderindefinedCounter * 100 / count($personArray), 1) . '%' .  '<br>';
}

function getPerfectPartner($surname, $name, $patronomyc, $personArray){
    $fullName = getFullnameFromParts($surname, $name, $patronomyc);
    $gender = getGenderFromName($fullName);

    $counter = 0;
    while ($counter < 100) {
        $partnerIndex = rand(0, count($personArray) - 1);
        $partnerGender = getGenderFromName($personArray[$partnerIndex]['fullname']);
        if ($partnerGender != $gender && $partnerGender != 0) {
            return getShortName($fullName) . " + " . getShortName($personArray[$partnerIndex]['fullname']) . ' = ' . '<br>'
                . '&#9825' . ' Идеально на ' . round(rand(5000, 10000) / 100, 2) . '% ' . '&#9825';
        } else {
            $counter++;
        }
    }
}

echo getPerfectPartner("Иванов", "Иван", "Иванович", $example_persons_array);