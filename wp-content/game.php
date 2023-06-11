<?php

// Sprawdzanie, czy zmienne dla gry clicker już istnieją
if (!isset($_SESSION['clicker'])) {
    initializeClickerVariables();
}

// Funkcja inicjalizująca zmienne dla gry clicker
function initializeClickerVariables()
{
    $_SESSION['clicker'] = [
        'points' => 0,
        'clickPower' => 1,
        'autoClickers' => 0,
        'autoClickerPower' => 1
    ];
}

// Funkcja ulepszająca zdobywanie punktów
function upgradeClickPower()
{
    $clickPower = $_SESSION['clicker']['clickPower'];
    $_SESSION['clicker']['clickPower'] = $clickPower + 1;
}

// Funkcja ulepszająca automatyczne klikanie
function upgradeAutoClickerPower()
{
    $autoClickerPower = $_SESSION['clicker']['autoClickerPower'];
    $_SESSION['clicker']['autoClickerPower'] = $autoClickerPower + 1;
}

// Funkcja dodająca punkty po kliknięciu
function click()
{
    $clickPower = $_SESSION['clicker']['clickPower'];
    $points = $_SESSION['clicker']['points'];
    $_SESSION['clicker']['points'] = $points + $clickPower;
}

// Funkcja obsługująca automatyczne klikanie
function autoClick()
{
    $autoClickers = $_SESSION['clicker']['autoClickers'];
    $autoClickerPower = $_SESSION['clicker']['autoClickerPower'];
    $points = $_SESSION['clicker']['points'];
    $_SESSION['clicker']['points'] = $points + ($autoClickers * $autoClickerPower);
}

// Sprawdzenie, czy naciśnięto przycisk kliknięcia
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'click') {
        click();
    } elseif ($_POST['action'] === 'upgradeClickPower') {
        upgradeClickPower();
    } elseif ($_POST['action'] === 'upgradeAutoClickerPower') {
        upgradeAutoClickerPower();
    } elseif ($_POST['action'] === 'buyAutoClicker') {
        $autoClickers = $_SESSION['clicker']['autoClickers'];
        $_SESSION['clicker']['autoClickers'] = $autoClickers + 1;
    }
}

// Wyświetlenie interfejsu gry
?>