<?php

include_once dirname(__FILE__) . '/' . 'components/application.php';
include_once dirname(__FILE__) . '/' . 'components/page/page.php';
include_once dirname(__FILE__) . '/' . 'components/security/permission_set.php';
include_once dirname(__FILE__) . '/' . 'components/security/user_authentication/hard_coded_user_authentication.php';
include_once dirname(__FILE__) . '/' . 'components/security/grant_manager/hard_coded_user_grant_manager.php';
include_once dirname(__FILE__) . '/' . 'components/security/recaptcha.php';
include_once dirname(__FILE__) . '/' . 'components/security/user_identity_storage/user_identity_session_storage.php';

$users = array('admin' => 'admin');

$grants = array('guest' => 
        array()
    ,
    'defaultUser' => 
        array('clients' => new PermissionSet(false, false, false, false),
        'air_baloons' => new PermissionSet(false, false, false, false),
        'cars' => new PermissionSet(false, false, false, false),
        'cars_driver' => new PermissionSet(false, false, false, false),
        'clients_pilots_air' => new PermissionSet(false, false, false, false),
        'driver' => new PermissionSet(false, false, false, false),
        'pilots' => new PermissionSet(false, false, false, false))
    ,
    'guest' => 
        array('clients' => new PermissionSet(false, false, false, false),
        'air_baloons' => new PermissionSet(false, false, false, false),
        'cars' => new PermissionSet(false, false, false, false),
        'cars_driver' => new PermissionSet(false, false, false, false),
        'clients_pilots_air' => new PermissionSet(false, false, false, false),
        'driver' => new PermissionSet(false, false, false, false),
        'pilots' => new PermissionSet(false, false, false, false))
    ,
    'admin' => 
        array('clients' => new PermissionSet(false, false, false, false),
        'air_baloons' => new PermissionSet(false, false, false, false),
        'cars' => new PermissionSet(false, false, false, false),
        'cars_driver' => new PermissionSet(false, false, false, false),
        'clients_pilots_air' => new PermissionSet(false, false, false, false),
        'driver' => new PermissionSet(false, false, false, false),
        'pilots' => new PermissionSet(false, false, false, false))
    );

$appGrants = array('guest' => new PermissionSet(false, false, false, false),
    'defaultUser' => new PermissionSet(true, false, false, false),
    'guest' => new PermissionSet(false, false, false, false),
    'admin' => new AdminPermissionSet());

$dataSourceRecordPermissions = array();

$tableCaptions = array('clients' => 'Clients',
'air_baloons' => 'Air Baloons',
'cars' => 'Cars',
'cars_driver' => 'Cars Driver',
'clients_pilots_air' => 'Clients Pilots Air',
'driver' => 'Driver',
'pilots' => 'Pilots');

function GetReCaptcha($formId) {
    return null;
}

function SetUpUserAuthorization()
{
    global $users;
    global $grants;
    global $appGrants;
    global $dataSourceRecordPermissions;

    $hasher = GetHasher('');
    $userAuthentication = new HardCodedUserAuthentication(new UserIdentitySessionStorage(), false, $hasher, $users);
    $grantManager = new HardCodedUserGrantManager($grants, $appGrants);

    GetApplication()->SetUserAuthentication($userAuthentication);
    GetApplication()->SetUserGrantManager($grantManager);
    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}
