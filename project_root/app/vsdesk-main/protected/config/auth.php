<?php
$list_roles = array(NULL);
$role_type = CAuthItem::TYPE_ROLE;
$roles = Roles::model()->findAll();
foreach ($roles as $role) {
    $rights = $role->role_rights;
    $children = array(NULL);
    foreach ($rights as $access) {
        if ($access->value == 1)
            $children[] = $access->name;
    }
    $child_arr = array_filter($children);
    $list_roles[$role->value] = array(
        'type' => 'CAuthItem::TYPE_ROLE',
        'description' => $role->name,
        'children' => $child_arr,
        'bizRule' => null,
        'data' => null
    );
}
//============ GLOBAL ROLES ===============//
return array_merge($list_roles, [
    'guest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'bizRule' => null,
        'data' => null
    ],
    'systemUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'System User role'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'System role')
    ],
    'systemManager' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'System Manager role'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'System role')
    ],
    'systemAdmin' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'System Admin role'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'System role')
    ],
//================ CHAT ROLES ================//
    'readChat' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Read chat'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Chat')
    ],
    'adminChat' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Administration chat'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Chat')
    ],

//==============ASTATUS ROLES===================//
    'createAstatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create unit statuses'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit statuses')
    ],
    'listAstatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of unit statuses'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit statuses')
    ],
    'updateAstatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update unit statuses'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit statuses')
    ],
    'deleteAstatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete unit statuses'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit statuses')
    ],
//================ REQUEST ROLES ================//
    'createRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'updateRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'viewRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'listRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'deleteRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'batchUpdateRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch update requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'batchMergeRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch merge requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'batchDeleteRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch delete requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'batchAssignRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch assign requests to another manager'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'batchUpdateStatusRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch update status of requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'uploadFilesRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can attach files to request'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canEditCommentsRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can edit comments in request'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canDeleteCommentsRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can delete comments in request'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canAddCommentsRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can add comments in request'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'viewMyselfRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can see only their own requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'viewMyCompanyRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can see only requests of his company'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'viewCompanyRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Manager can see only requests of his companies'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],

    'viewMyDepartRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can see only requests of his departments'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],

    'viewAllGroupRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Manager can see all requests of his group'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'viewAssignedRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Manager can see assigned to him requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'viewMyAssignedRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Manager can see assigned to him requests and their own'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'updateDatesRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can update requests deadlines'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'updateLeadRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can update request execution time'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canAssignRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Manager can assign request to another manager'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'viewHistoryRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can view ticket history'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canSetUnitRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can select units in ticket form'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canSetObserversRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can select observers in ticket form'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canSetFieldsRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can see fieldsets in ticket form'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canViewFieldsRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can see fieldsets in ticket view'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'unitByUserRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show only the units owned by the user'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'printRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Print ticket'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canSetPriority' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Allow select priority'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canEditContent' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can edit content of ticket'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canAddTemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can add reply template from ticket reply'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canAddKBreply' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can add knowledge base from ticket reply'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'prevnextRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can list prev and next ticket in view'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'liteformRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'The user uses a light form'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'downfieldsRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show additional fields under content'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canViewFieldsRequestList' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'The user sees the add. fields in the list of applications'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canStartTWSession' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'The user can initialize a Team viewer session'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canSuspendRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'The user can suspend request'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canChangeUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Manager can change ticket customer'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canSelectDeadline' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can select deadline and manager in form'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'cantSelectCustomer' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Manager can not select customer in create form'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'doNotSelectServiceCategories' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Do not use service category selection'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canChangeChecklist' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Display and fill out checklists in the view form'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'viewOnlyChecklist' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Read only checklists in the view form'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canEditRequestPlanStart' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Can edit request plan start field'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'canEditRequestPlanEnd' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Can edit request plan end field'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
//==============CRON REQUEST ROLES===================//
    'listCronRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of cron requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'updateCronRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update cron requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'createCronRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create cron requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],
    'deleteCronRequest' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete cron requests'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request')
    ],

//==============PROBLEMS ROLES===================//
    'createProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create problems'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
    'viewProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View problems'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
    'listProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of problems'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
    'updateProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update problems'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
    'deleteProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete problems'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
    'canAssignProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Manager can assign problem to another manager'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
    'uploadFilesProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can attach files to problem'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
    'batchUpdateProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch update problems'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
    'batchDeleteProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch delete problems'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
    'viewHistoryProblem' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can view problem history'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Problem')
    ],
//==============SERVICES ROLES===================//
    'createService' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create services'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service')
    ],
    'viewService' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View services'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service')
    ],
    'listService' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of services'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service')
    ],
    'updateService' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update services'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service')
    ],
    'deleteService' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete services'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service')
    ],
//==============CONTRACTS ROLES===================//
    'createContracts' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create contracts'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Contracts')
    ],
    'viewContracts' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View contracts'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Contracts')
    ],
    'listContracts' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of contracts'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Contracts')
    ],
    'updateContracts' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update contracts'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Contracts')
    ],
    'deleteContracts' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete contracts'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Contracts')
    ],
    'printContracts' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Print contracts'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Contracts')
    ],
    'uploadFilesContracts' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can attach files to contracts'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Contracts')
    ],

//==============SLA ROLES===================//
    'createSla' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create service levels'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Sla')
    ],
    'viewSla' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View service levels'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Sla')
    ],
    'listSla' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of service levels'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Sla')
    ],
    'updateSla' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update service levels'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Sla')
    ],
    'deleteSla' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete service levels'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Sla')
    ],
//==============ASSETS ROLES===================//
    'createAsset' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create assets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset')
    ],
    'viewAsset' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View assets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset')
    ],
    'listAsset' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of assets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset')
    ],
    'updateAsset' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update assets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset')
    ],
    'deleteAsset' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete assets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset')
    ],
    'batchDeleteAsset' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch delete assets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset')
    ],
    'exportAsset' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Export list of assets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset')
    ],
    'printAsset' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Print assets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset')
    ],
    'uploadFilesAsset' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can attach files to asset'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset')
    ],
//==============ASSETS TYPES ROLES===================//
    'createAssetType' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create asset types'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset type')
    ],
    'listAssetType' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of asset types'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset type')
    ],
    'updateAssetType' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update asset types'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset type')
    ],
    'deleteAssetType' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete asset types'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Asset type')
    ],
//==============UNIT ROLES===================//
    'createUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create units'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
    'viewUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View units'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
    'listUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of units'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
    'updateUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update units'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
    'deleteUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete units'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
    'batchDeleteUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch delete units'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
    'exportUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Export list of units'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
    'printUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Print units'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
    'viewMyselfUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can see only their own units'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
    'uploadFilesUnit' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can attach files to unit'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit')
    ],
//==============UNIT TYPES ROLES===================//
    'createUnitType' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create unit types'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit type')
    ],
    'listUnitType' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of unit types'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit type')
    ],
    'updateUnitType' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update unit types'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit type')
    ],
    'deleteUnitType' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete unit types'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Unit type')
    ],
//==============CITIES ROLES===================//
    'createCities' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create cities'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'City')
    ],
    'listCities' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list cities'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'City')
    ],
    'updateCities' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update city'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'City')
    ],
    'deleteCities' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete city'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'City')
    ],
//==============STREETS ROLES===================//
    'createStreets' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create streets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Street')
    ],
    'listStreets' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of streets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Street')
    ],
    'updateStreets' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update streets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Street')
    ],
    'deleteStreets' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete streets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Street')
    ],
//==============KB ROLES===================//
    'createKB' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create knowledgebase records'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase')
    ],
    'viewKB' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View knowledgebase records'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase')
    ],
    'listKB' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of knowledgebase'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase')
    ],
    'updateKB' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update knowledgebase records'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase')
    ],
    'deleteKB' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete knowledgebase records'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase')
    ],
    'uploadFilesKB' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can attach files to knowledgebase records'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase')
    ],
    'setResponsibleKB' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can set responsible for knowledgebase records'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase')
    ],
//==============KB CATS ROLES===================//
    'createKBCat' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create knowledgebase categories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase cats')
    ],
    'listKBCat' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of knowledgebase categories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase cats')
    ],
    'updateKBCat' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update knowledgebase categories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase cats')
    ],
    'deleteKBCat' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete knowledgebase categories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Knowledgebase cats')
    ],
//==============NEWS ROLES===================//
    'createNews' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create news'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'News')
    ],
    'viewNews' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View news'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'News')
    ],
    'listNews' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of news'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'News')
    ],
    'updateNews' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update news'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'News')
    ],
    'deleteNews' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete news'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'News')
    ],
//==============USERS ROLES===================//
    'createUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create users'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'User')
    ],
    'viewUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View users'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'User')
    ],
    'listUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of users'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'User')
    ],
    'updateUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update users'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'User')
    ],
    'deleteUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete users'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'User')
    ],
    'batchDeleteUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch delete users'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'User')
    ],
    'exportUser' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Export list of users'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'User')
    ],
//==============PHONEBOOK ROLES===================//
    'viewPhonebook' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View phonebook users'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Phonebook')
    ],
    'listPhonebook' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View phonebook'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Phonebook')
    ],
    'viewOnlyUserCompanyPhonebook' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View only users company contacts in phonebook'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Phonebook')
    ],
//==============COMPANIES ROLES===================//
    'createCompany' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create companies'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Company')
    ],
    'viewCompany' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View companies'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Company')
    ],
    'listCompany' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of companies'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Company')
    ],
    'updateCompany' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update companies'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Company')
    ],
    'deleteCompany' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete companies'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Company')
    ],
    'batchDeleteCompany' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Batch delete companies'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Company')
    ],
    'fieldsCompany' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Manage companies fields'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Company')
    ],
    'uploadFilesCompany' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'User can attach files to company'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Company')
    ],
//==============DEPART ROLES===================//
    'createDepart' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create departments'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Department')
    ],
    'listDepart' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of departments'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Department')
    ],
    'updateDepart' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update departments'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Department')
    ],
    'deleteDepart' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete departments'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Department')
    ],
//==============GROUPS ROLES===================//
    'createGroup' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create groups'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Group')
    ],
    'listGroup' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of groups'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Group')
    ],
    'updateGroup' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update groups'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Group')
    ],
    'deleteGroup' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete groups'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Group')
    ],
//==============PRIORITY ROLES===================//
    'createPriority' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create priorities'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Priority')
    ],
    'listPriority' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of priorities'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Priority')
    ],
    'updatePriority' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update priorities'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Priority')
    ],
    'deletePriority' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete priorities'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Priority')
    ],
//==============STATUSES ROLES===================//
    'createStatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create statuses'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Status')
    ],
    'listStatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of statuses'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Status')
    ],
    'updateStatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update statuses'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Status')
    ],
    'deleteStatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete statuses'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Status')
    ],
//==============CATEGORIES ROLES===================//
    'createCategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create ticket categories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Category')
    ],
    'listCategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of ticket categories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Category')
    ],
    'updateCategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update ticket categories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Category')
    ],
    'deleteCategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete ticket categories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Category')
    ],
//==============TCATEGORIES ROLES===================//
    'createTcategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create ticket tcategories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Tcategory')
    ],
    'listTcategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of ticket tcategories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Tcategory')
    ],
    'updateTcategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update ticket tcategories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Tcategory')
    ],
    'deleteTcategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete ticket tcategories'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Tcategory')
    ],
//==============EMAIL TEMPLATES ROLES===================//
    'createETemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create Email templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'E-mail templates')
    ],
    'viewETemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View Email templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'E-mail templates')
    ],
    'listETemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of Email templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'E-mail templates')
    ],
    'updateETemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update Email templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'E-mail templates')
    ],
    'deleteETemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete Email templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'E-mail templates')
    ],
//==============SMS TEMPLATES ROLES===================//
    'createSTemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create SMS templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'SMS templates')
    ],
    'viewSTemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View SMS templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'SMS templates')
    ],
    'listSTemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of SMS templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'SMS templates')
    ],
    'updateSTemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update SMS templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'SMS templates')
    ],
    'deleteSTemplate' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete SMS templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'SMS templates')
    ],
//==============FIELDSETS ROLES===================//
    'createFieldsets' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create fieldsets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Fieldsets')
    ],
    'listFieldsets' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of fieldsets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Fieldsets')
    ],
    'updateFieldsets' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update fieldsets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Fieldsets')
    ],
    'deleteFieldsets' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete fieldsets'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Fieldsets')
    ],
//==============REPORTS ROLES===================//
    'usersReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Users report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'customReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Custom report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'companiesReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Companies report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'managersReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Managers report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'serviceReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Services report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'assetReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Assets report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'unitProblemReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Unit problems report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'monthServiceProblemReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Service problems by month report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'monthServiceRequestsReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Service requests by month report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'serviceProblemReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Service problems report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'unitSProblemReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Units summary report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'requestSReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access Request summary report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
    'managersKPIReport' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'KPI report'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reports')
    ],
//==============SETTINGS ROLES===================//
    'rolesSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access roles management'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'mainSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access main settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'mailParserSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access mail parser settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'pushSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Push notification'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'adSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access AD integration settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'smsSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access SMS gate settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'ticketSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access ticket defaults settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'attachSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access attachements settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'appearSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access appearance settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'shedulerSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access sheduler settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'logSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access log'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'backupSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Access backup'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'importSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Import from CSV'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    // 'amiSettings' => [
    //     'type' => CAuthItem::TYPE_ROLE,
    //     'description' => Yii::t('main-ui', 'Asterisk integration'),
    //     'bizRule' => null,
    //     'data' => Yii::t('main-ui', 'Settings')
    // ],
    // 'amiCalls' => [
    //     'type' => CAuthItem::TYPE_ROLE,
    //     'description' => Yii::t('main-ui', 'Asterisk calls'),
    //     'bizRule' => null,
    //     'data' => Yii::t('main-ui', 'Settings')
    // ],
    'tbotSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Telegram bot integration'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'vbotSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Viber bot integration'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'msbotSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Microsoft Bot integration'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'slackSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Slack integration'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'wbotSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'WhatsApp integration'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'widgetSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Site widget settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'twSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'TeamViewer integration'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'jiraSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Jira integration'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
    'portalSettings' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Portal settings'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Settings')
    ],
//==============REPLY TEMPLATES ROLES===================//
    'createTemplates' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create reply templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reply templates')
    ],
    'listTemplates' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of reply templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reply templates')
    ],
    'updateTemplates' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update reply templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reply templates')
    ],
    'deleteTemplates' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete reply templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Reply templates')
    ],
    //==============UNITS TEMPLATES ROLES===================//
    'createUnitTemplates' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create print templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Print form templates')
    ],
    'listUnitTemplates' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of print templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Print form templates')
    ],
    'updateUnitTemplates' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update print templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Print form templates')
    ],
    'deleteUnitTemplates' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete print templates'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Print form templates')
    ],

//==============INTERFACE ROLES===================//
    'showlastNews' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show last news on dashboard'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Interface')
    ],
    'showlastKB' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show last knowledgebase records on dashboard'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Interface')
    ],
    'showSearchKB' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show search knowledgebase field'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Interface')
    ],
    'showTicketCalendar' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show tickets calendar on dashboard'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Interface')
    ],
    'allowSoundNotify' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Allow sound notifications'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Interface')
    ],
    'allowAlertNotify' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Use popup messages (Decrease performance)'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Interface')
    ],
    'mainGraphAllGroupsManagers' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show graphic groups managers'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Graphics')
    ],
    'mainGraphAllUsers' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show graphic users'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Graphics')
    ],
    'mainGraphManagers' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show graphic managers'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Graphics')
    ],
    'mainGraphAllCompanys' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show graphic companys'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Graphics')
    ],
    /*'mainGraphAllContractors' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show graphic contractors'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Graphics')
    ),*/
    'showTicketGraph' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show tickets graph on dashboard'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Graphics')
    ],
    'showProblemGraph' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show problems graph on dashboard'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Graphics')
    ],
    'mainGraphCurentUserStatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show graphic by current user'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Graphics')
    ],
    'mainGraphCompanyCurentUserStatus' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Show graphic by company of current user'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Graphics')
    ],
    //==============API ROLES===================//
    'createAPI' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create APIs'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'API')
    ],
    'updateAPI' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update APIs'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'API')
    ],
    'viewAPI' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View APIs'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'API')
    ],
    'listAPI' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'List APIs'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'API')
    ],
    'deleteAPI' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete APIs'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'API')
    ],
    //==============CALLS ROLES===================//
        'viewCalls' => [
            'type' => CAuthItem::TYPE_ROLE,
            'description' => Yii::t('main-ui', 'View calls'),
            'bizRule' => null,
            'data' => Yii::t('main-ui', 'Calls')
        ],
        'listCalls' => [
            'type' => CAuthItem::TYPE_ROLE,
            'description' => Yii::t('main-ui', 'View list of calls'),
            'bizRule' => null,
            'data' => Yii::t('main-ui', 'Calls')
        ],
        'deleteCalls' => [
            'type' => CAuthItem::TYPE_ROLE,
            'description' => Yii::t('main-ui', 'Delete calls'),
            'bizRule' => null,
            'data' => Yii::t('main-ui', 'Calls')
        ],
    //==============SELECTS ROLES===================//
        'listSelects' => [
            'type' => CAuthItem::TYPE_ROLE,
            'description' => Yii::t('main-ui', 'View list of selects'),
            'bizRule' => null,
            'data' => Yii::t('main-ui', 'Lists')
        ],
        'createSelects' => [
            'type' => CAuthItem::TYPE_ROLE,
            'description' => Yii::t('main-ui', 'Create selects'),
            'bizRule' => null,
            'data' => Yii::t('main-ui', 'Lists')
        ],
        'updateSelects' => [
            'type' => CAuthItem::TYPE_ROLE,
            'description' => Yii::t('main-ui', 'Update selects'),
            'bizRule' => null,
            'data' => Yii::t('main-ui', 'Lists')
        ],
        'deleteSelects' => [
            'type' => CAuthItem::TYPE_ROLE,
            'description' => Yii::t('main-ui', 'Delete selects'),
            'bizRule' => null,
            'data' => Yii::t('main-ui', 'Lists')
        ],
    //==============SERVICES ROLES===================//
    'createServiceCategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create services category'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service category')
    ],
    'viewServiceCategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View services category'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service category')
    ],
    'listServiceCategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of services category'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service category')
    ],
    'updateServiceCategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update services category'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service category')
    ],
    'deleteServiceCategory' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete services category'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Service category')
    ],
    //==============CHECKLIST ROLES===================//
    'createChecklists' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create checklists'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Checklists')
    ],
    'listChecklists' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of checklists'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Checklists')
    ],
    'updateChecklists' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update checklists'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Checklists')
    ],
    'deleteChecklists' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete checklists'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Checklists')
    ],
    //==============REQUEST PROCESSING RULES ROLES===================//
    'createRequestProcessingRules' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Create request processing rules'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request processing rules')
    ],
    'viewRequestProcessingRules' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View request processing rules'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request processing rules')
    ],
    'listRequestProcessingRules' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'View list of request processing rules'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request processing rules')
    ],
    'updateRequestProcessingRules' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Update request processing rules'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request processing rules')
    ],
    'deleteRequestProcessingRules' => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('main-ui', 'Delete request processing rules'),
        'bizRule' => null,
        'data' => Yii::t('main-ui', 'Request processing rules')
    ],
]);
