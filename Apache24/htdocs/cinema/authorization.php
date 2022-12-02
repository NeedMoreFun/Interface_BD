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
        array('category_of_places' => new PermissionSet(false, false, false, false),
        'clients' => new PermissionSet(false, false, false, false),
        'films' => new PermissionSet(false, false, false, false),
        'halls' => new PermissionSet(false, false, false, false),
        'places_and_rows' => new PermissionSet(false, false, false, false),
        'price' => new PermissionSet(false, false, false, false),
        'sessions' => new PermissionSet(false, false, false, false),
        'tickets' => new PermissionSet(false, false, false, false))
    ,
    'guest' => 
        array('category_of_places' => new PermissionSet(false, false, false, false),
        'clients' => new PermissionSet(false, false, false, false),
        'films' => new PermissionSet(false, false, false, false),
        'halls' => new PermissionSet(false, false, false, false),
        'places_and_rows' => new PermissionSet(false, false, false, false),
        'price' => new PermissionSet(false, false, false, false),
        'sessions' => new PermissionSet(false, false, false, false),
        'tickets' => new PermissionSet(false, false, false, false))
    ,
    'admin' => 
        array('category_of_places' => new PermissionSet(false, false, false, false),
        'clients' => new PermissionSet(false, false, false, false),
        'films' => new PermissionSet(false, false, false, false),
        'halls' => new PermissionSet(false, false, false, false),
        'places_and_rows' => new PermissionSet(false, false, false, false),
        'price' => new PermissionSet(false, false, false, false),
        'sessions' => new PermissionSet(false, false, false, false),
        'tickets' => new PermissionSet(false, false, false, false))
    );

$appGrants = array('guest' => new PermissionSet(false, false, false, false),
    'defaultUser' => new PermissionSet(true, false, false, false),
    'guest' => new PermissionSet(false, false, false, false),
    'admin' => new AdminPermissionSet());

$dataSourceRecordPermissions = array();

$tableCaptions = array('category_of_places' => 'Category Of Places',
'clients' => 'Clients',
'films' => 'Films',
'halls' => 'Halls',
'places_and_rows' => 'Places And Rows',
'price' => 'Price',
'sessions' => 'Sessions',
'tickets' => 'Tickets');

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
