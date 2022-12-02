<?php

include_once dirname(__FILE__) . '/' . 'components/application.php';
include_once dirname(__FILE__) . '/' . 'components/page/page.php';
include_once dirname(__FILE__) . '/' . 'components/security/permission_set.php';
include_once dirname(__FILE__) . '/' . 'components/security/user_authentication/hard_coded_user_authentication.php';
include_once dirname(__FILE__) . '/' . 'components/security/grant_manager/hard_coded_user_grant_manager.php';
include_once dirname(__FILE__) . '/' . 'components/security/recaptcha.php';
include_once dirname(__FILE__) . '/' . 'components/security/user_identity_storage/user_identity_session_storage.php';

$users = array('redactor' => 'redactor',
    'user' => 'user',
    'admin' => 'admin');

$grants = array('defaultUser' => 
        array('audiences' => new PermissionSet(false, false, false, false),
        'schedule' => new PermissionSet(false, false, false, false),
        'specializations' => new PermissionSet(false, false, false, false),
        'stud_groups' => new PermissionSet(false, false, false, false),
        'students' => new PermissionSet(false, false, false, false),
        'subjects' => new PermissionSet(false, false, false, false),
        'teacher_subject' => new PermissionSet(false, false, false, false),
        'teachers' => new PermissionSet(false, false, false, false),
        'university_buildings' => new PermissionSet(false, false, false, false))
    ,
    'guest' => 
        array('audiences' => new PermissionSet(false, false, false, false),
        'schedule' => new PermissionSet(false, false, false, false),
        'specializations' => new PermissionSet(false, false, false, false),
        'stud_groups' => new PermissionSet(false, false, false, false),
        'students' => new PermissionSet(false, false, false, false),
        'subjects' => new PermissionSet(false, false, false, false),
        'teacher_subject' => new PermissionSet(false, false, false, false),
        'teachers' => new PermissionSet(false, false, false, false),
        'university_buildings' => new PermissionSet(false, false, false, false))
    ,
    'redactor' => 
        array('audiences' => new PermissionSet(false, false, false, false),
        'schedule' => new PermissionSet(false, false, false, false),
        'specializations' => new PermissionSet(false, false, false, false),
        'stud_groups' => new PermissionSet(false, false, false, false),
        'students' => new PermissionSet(false, false, false, false),
        'subjects' => new PermissionSet(false, false, false, false),
        'teacher_subject' => new PermissionSet(false, false, false, false),
        'teachers' => new PermissionSet(false, false, false, false),
        'university_buildings' => new PermissionSet(false, false, false, false))
    ,
    'user' => 
        array('audiences' => new PermissionSet(false, false, false, false),
        'schedule' => new PermissionSet(false, false, false, false),
        'specializations' => new PermissionSet(false, false, false, false),
        'stud_groups' => new PermissionSet(false, false, false, false),
        'students' => new PermissionSet(false, false, false, false),
        'subjects' => new PermissionSet(false, false, false, false),
        'teacher_subject' => new PermissionSet(false, false, false, false),
        'teachers' => new PermissionSet(false, false, false, false),
        'university_buildings' => new PermissionSet(false, false, false, false))
    ,
    'admin' => 
        array('audiences' => new PermissionSet(false, false, false, false),
        'schedule' => new PermissionSet(false, false, false, false),
        'specializations' => new PermissionSet(false, false, false, false),
        'stud_groups' => new PermissionSet(false, false, false, false),
        'students' => new PermissionSet(false, false, false, false),
        'subjects' => new PermissionSet(false, false, false, false),
        'teacher_subject' => new PermissionSet(false, false, false, false),
        'teachers' => new PermissionSet(false, false, false, false),
        'university_buildings' => new PermissionSet(false, false, false, false))
    );

$appGrants = array('defaultUser' => new PermissionSet(true, false, false, false),
    'guest' => new PermissionSet(false, false, false, false),
    'redactor' => new PermissionSet(true, true, false, false),
    'user' => new PermissionSet(true, false, false, false),
    'admin' => new AdminPermissionSet());

$dataSourceRecordPermissions = array();

$tableCaptions = array('audiences' => 'Audiences',
'schedule' => 'Schedule',
'specializations' => 'Specializations',
'stud_groups' => 'Stud Groups',
'students' => 'Students',
'subjects' => 'Subjects',
'teacher_subject' => 'Teacher Subject',
'teachers' => 'Teachers',
'university_buildings' => 'University Buildings');

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
    $userAuthentication = new HardCodedUserAuthentication(new UserIdentitySessionStorage(), true, $hasher, $users);
    $grantManager = new HardCodedUserGrantManager($grants, $appGrants);

    GetApplication()->SetUserAuthentication($userAuthentication);
    GetApplication()->SetUserGrantManager($grantManager);
    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}
