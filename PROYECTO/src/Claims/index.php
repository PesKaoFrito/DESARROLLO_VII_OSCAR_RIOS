<?php
    //Enable error reporting
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);

    //Define the base path for includes
    define ('BASE_PATH', __DIR__.'/');

    //Include the configuration file
    require_once '../../config.php';

    //Include necessary files
    require_once '../Database.php';
    require_once BASE_PATH . 'Claim.php';
    require_once BASE_PATH . 'ClaimManager.php';

    //Create an instance of ClaimManager
    $claimManager= new ClaimManager();
    //Get action from query parameters
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';
    // Handle different actions
    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $claim=new Claim($_POST);
                $claimManager->createClaim($claim);
                //require BASE_PATH . 'views/claim_form.php';
                exit;
            }
            break;
        case 'toggle':
            $claimManager->toggleClaim($_GET['id']);
            //header('Location: ' . BASE_URL);
            break;
        case 'delete':
            $claimManager->deleteClaim($_GET['id']);
            //header('Location: ' . BASE_URL);
            break;
        default:
            $claims = $claimManager->getAllClaims();
            print_r($claims);
            //require BASE_PATH . 'views/claim_list.php';
            break;
    }
?>