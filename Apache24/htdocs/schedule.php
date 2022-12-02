<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page_includes.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class schedulePage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Schedule');
            $this->SetMenuLabel('Schedule');
    
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`schedule`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id_schedule', true, true, true),
                    new IntegerField('id_audience', true),
                    new IntegerField('id_group', true),
                    new IntegerField('day_of_week', true),
                    new IntegerField('pair_number', true),
                    new IntegerField('id_teacher', true),
                    new IntegerField('id_subject', true)
                )
            );
            $this->dataset->AddLookupField('id_audience', 'audiences', new IntegerField('id_audience'), new IntegerField('id_building', false, false, false, false, 'id_audience_id_building', 'id_audience_id_building_audiences'), 'id_audience_id_building_audiences');
            $this->dataset->AddLookupField('id_group', 'stud_groups', new IntegerField('id_group'), new StringField('group_name', false, false, false, false, 'id_group_group_name', 'id_group_group_name_stud_groups'), 'id_group_group_name_stud_groups');
            $this->dataset->AddLookupField('id_teacher', 'teachers', new IntegerField('id_teacher'), new StringField('teacher_name', false, false, false, false, 'id_teacher_teacher_name', 'id_teacher_teacher_name_teachers'), 'id_teacher_teacher_name_teachers');
            $this->dataset->AddLookupField('id_subject', 'subjects', new IntegerField('id_subject'), new StringField('subject_name', false, false, false, false, 'id_subject_subject_name', 'id_subject_subject_name_subjects'), 'id_subject_subject_name_subjects');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'id_schedule', 'id_schedule', 'Id Schedule'),
                new FilterColumn($this->dataset, 'id_audience', 'id_audience_id_building', 'Id Audience'),
                new FilterColumn($this->dataset, 'id_group', 'id_group_group_name', 'Id Group'),
                new FilterColumn($this->dataset, 'day_of_week', 'day_of_week', 'Day Of Week'),
                new FilterColumn($this->dataset, 'pair_number', 'pair_number', 'Pair Number'),
                new FilterColumn($this->dataset, 'id_teacher', 'id_teacher_teacher_name', 'Id Teacher'),
                new FilterColumn($this->dataset, 'id_subject', 'id_subject_subject_name', 'Id Subject')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id_schedule'])
                ->addColumn($columns['id_audience'])
                ->addColumn($columns['id_group'])
                ->addColumn($columns['day_of_week'])
                ->addColumn($columns['pair_number'])
                ->addColumn($columns['id_teacher'])
                ->addColumn($columns['id_subject']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->deleteOperationIsAllowed()) {
                $operation = new AjaxOperation(OPERATION_DELETE,
                    $this->GetLocalizerCaptions()->GetMessageString('Delete'),
                    $this->GetLocalizerCaptions()->GetMessageString('Delete'), $this->dataset,
                    $this->GetModalGridDeleteHandler(), $grid
                );
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
            }
            
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for id_schedule field
            //
            $column = new NumberViewColumn('id_schedule', 'id_schedule', 'Id Schedule', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for id_building field
            //
            $column = new NumberViewColumn('id_audience', 'id_audience_id_building', 'Id Audience', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for group_name field
            //
            $column = new TextViewColumn('id_group', 'id_group_group_name', 'Id Group', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for day_of_week field
            //
            $column = new NumberViewColumn('day_of_week', 'day_of_week', 'Day Of Week', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for pair_number field
            //
            $column = new NumberViewColumn('pair_number', 'pair_number', 'Pair Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for teacher_name field
            //
            $column = new TextViewColumn('id_teacher', 'id_teacher_teacher_name', 'Id Teacher', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for subject_name field
            //
            $column = new TextViewColumn('id_subject', 'id_subject_subject_name', 'Id Subject', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for id_schedule field
            //
            $column = new NumberViewColumn('id_schedule', 'id_schedule', 'Id Schedule', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for id_building field
            //
            $column = new NumberViewColumn('id_audience', 'id_audience_id_building', 'Id Audience', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for group_name field
            //
            $column = new TextViewColumn('id_group', 'id_group_group_name', 'Id Group', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for day_of_week field
            //
            $column = new NumberViewColumn('day_of_week', 'day_of_week', 'Day Of Week', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for pair_number field
            //
            $column = new NumberViewColumn('pair_number', 'pair_number', 'Pair Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for teacher_name field
            //
            $column = new TextViewColumn('id_teacher', 'id_teacher_teacher_name', 'Id Teacher', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for subject_name field
            //
            $column = new TextViewColumn('id_subject', 'id_subject_subject_name', 'Id Subject', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for id_audience field
            //
            $editor = new ComboBox('id_audience_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`audiences`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_audience', true, true, true),
                    new IntegerField('id_building', true),
                    new StringField('audience_name', true),
                    new IntegerField('id_spec', true)
                )
            );
            $lookupDataset->setOrderByField('id_building', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Audience', 
                'id_audience', 
                $editor, 
                $this->dataset, 'id_audience', 'id_building', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_group field
            //
            $editor = new ComboBox('id_group_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`stud_groups`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_group', true, true, true),
                    new StringField('group_name', true),
                    new IntegerField('grade_number', true),
                    new IntegerField('number_of_persons', true)
                )
            );
            $lookupDataset->setOrderByField('group_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Group', 
                'id_group', 
                $editor, 
                $this->dataset, 'id_group', 'group_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for day_of_week field
            //
            $editor = new TextEdit('day_of_week_edit');
            $editColumn = new CustomEditColumn('Day Of Week', 'day_of_week', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for pair_number field
            //
            $editor = new TextEdit('pair_number_edit');
            $editColumn = new CustomEditColumn('Pair Number', 'pair_number', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_teacher field
            //
            $editor = new ComboBox('id_teacher_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`teachers`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_teacher', true, true, true),
                    new StringField('teacher_name')
                )
            );
            $lookupDataset->setOrderByField('teacher_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Teacher', 
                'id_teacher', 
                $editor, 
                $this->dataset, 'id_teacher', 'teacher_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_subject field
            //
            $editor = new ComboBox('id_subject_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`subjects`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_subject', true, true, true),
                    new StringField('subject_name', true),
                    new StringField('type', true),
                    new IntegerField('id_spec', true)
                )
            );
            $lookupDataset->setOrderByField('subject_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Subject', 
                'id_subject', 
                $editor, 
                $this->dataset, 'id_subject', 'subject_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for id_audience field
            //
            $editor = new ComboBox('id_audience_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`audiences`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_audience', true, true, true),
                    new IntegerField('id_building', true),
                    new StringField('audience_name', true),
                    new IntegerField('id_spec', true)
                )
            );
            $lookupDataset->setOrderByField('id_building', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Audience', 
                'id_audience', 
                $editor, 
                $this->dataset, 'id_audience', 'id_building', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for id_group field
            //
            $editor = new ComboBox('id_group_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`stud_groups`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_group', true, true, true),
                    new StringField('group_name', true),
                    new IntegerField('grade_number', true),
                    new IntegerField('number_of_persons', true)
                )
            );
            $lookupDataset->setOrderByField('group_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Group', 
                'id_group', 
                $editor, 
                $this->dataset, 'id_group', 'group_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for day_of_week field
            //
            $editor = new TextEdit('day_of_week_edit');
            $editColumn = new CustomEditColumn('Day Of Week', 'day_of_week', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for pair_number field
            //
            $editor = new TextEdit('pair_number_edit');
            $editColumn = new CustomEditColumn('Pair Number', 'pair_number', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for id_teacher field
            //
            $editor = new ComboBox('id_teacher_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`teachers`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_teacher', true, true, true),
                    new StringField('teacher_name')
                )
            );
            $lookupDataset->setOrderByField('teacher_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Teacher', 
                'id_teacher', 
                $editor, 
                $this->dataset, 'id_teacher', 'teacher_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for id_subject field
            //
            $editor = new ComboBox('id_subject_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`subjects`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_subject', true, true, true),
                    new StringField('subject_name', true),
                    new StringField('type', true),
                    new IntegerField('id_spec', true)
                )
            );
            $lookupDataset->setOrderByField('subject_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Subject', 
                'id_subject', 
                $editor, 
                $this->dataset, 'id_subject', 'subject_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddToggleEditColumns(Grid $grid)
        {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for id_audience field
            //
            $editor = new ComboBox('id_audience_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`audiences`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_audience', true, true, true),
                    new IntegerField('id_building', true),
                    new StringField('audience_name', true),
                    new IntegerField('id_spec', true)
                )
            );
            $lookupDataset->setOrderByField('id_building', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Audience', 
                'id_audience', 
                $editor, 
                $this->dataset, 'id_audience', 'id_building', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_group field
            //
            $editor = new ComboBox('id_group_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`stud_groups`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_group', true, true, true),
                    new StringField('group_name', true),
                    new IntegerField('grade_number', true),
                    new IntegerField('number_of_persons', true)
                )
            );
            $lookupDataset->setOrderByField('group_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Group', 
                'id_group', 
                $editor, 
                $this->dataset, 'id_group', 'group_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for day_of_week field
            //
            $editor = new TextEdit('day_of_week_edit');
            $editColumn = new CustomEditColumn('Day Of Week', 'day_of_week', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for pair_number field
            //
            $editor = new TextEdit('pair_number_edit');
            $editColumn = new CustomEditColumn('Pair Number', 'pair_number', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_teacher field
            //
            $editor = new ComboBox('id_teacher_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`teachers`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_teacher', true, true, true),
                    new StringField('teacher_name')
                )
            );
            $lookupDataset->setOrderByField('teacher_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Teacher', 
                'id_teacher', 
                $editor, 
                $this->dataset, 'id_teacher', 'teacher_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_subject field
            //
            $editor = new ComboBox('id_subject_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`subjects`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_subject', true, true, true),
                    new StringField('subject_name', true),
                    new StringField('type', true),
                    new IntegerField('id_spec', true)
                )
            );
            $lookupDataset->setOrderByField('subject_name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Subject', 
                'id_subject', 
                $editor, 
                $this->dataset, 'id_subject', 'subject_name', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        private function AddMultiUploadColumn(Grid $grid)
        {
    
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for id_schedule field
            //
            $column = new NumberViewColumn('id_schedule', 'id_schedule', 'Id Schedule', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for id_building field
            //
            $column = new NumberViewColumn('id_audience', 'id_audience_id_building', 'Id Audience', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for group_name field
            //
            $column = new TextViewColumn('id_group', 'id_group_group_name', 'Id Group', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for day_of_week field
            //
            $column = new NumberViewColumn('day_of_week', 'day_of_week', 'Day Of Week', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for pair_number field
            //
            $column = new NumberViewColumn('pair_number', 'pair_number', 'Pair Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for teacher_name field
            //
            $column = new TextViewColumn('id_teacher', 'id_teacher_teacher_name', 'Id Teacher', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for subject_name field
            //
            $column = new TextViewColumn('id_subject', 'id_subject_subject_name', 'Id Subject', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id_schedule field
            //
            $column = new NumberViewColumn('id_schedule', 'id_schedule', 'Id Schedule', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for id_building field
            //
            $column = new NumberViewColumn('id_audience', 'id_audience_id_building', 'Id Audience', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for group_name field
            //
            $column = new TextViewColumn('id_group', 'id_group_group_name', 'Id Group', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for day_of_week field
            //
            $column = new NumberViewColumn('day_of_week', 'day_of_week', 'Day Of Week', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for pair_number field
            //
            $column = new NumberViewColumn('pair_number', 'pair_number', 'Pair Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for teacher_name field
            //
            $column = new TextViewColumn('id_teacher', 'id_teacher_teacher_name', 'Id Teacher', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for subject_name field
            //
            $column = new TextViewColumn('id_subject', 'id_subject_subject_name', 'Id Subject', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for id_building field
            //
            $column = new NumberViewColumn('id_audience', 'id_audience_id_building', 'Id Audience', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for group_name field
            //
            $column = new TextViewColumn('id_group', 'id_group_group_name', 'Id Group', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for day_of_week field
            //
            $column = new NumberViewColumn('day_of_week', 'day_of_week', 'Day Of Week', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for pair_number field
            //
            $column = new NumberViewColumn('pair_number', 'pair_number', 'Pair Number', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for teacher_name field
            //
            $column = new TextViewColumn('id_teacher', 'id_teacher_teacher_name', 'Id Teacher', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for subject_name field
            //
            $column = new TextViewColumn('id_subject', 'id_subject_subject_name', 'Id Subject', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->setAllowSortingByDialog(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setAllowAddMultipleRecords(false);
            $result->setMultiEditAllowed($this->GetSecurityInfo()->HasEditGrant() && false);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddMultiEditColumns($result);
            $this->AddToggleEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            $this->AddMultiUploadColumn($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setAllowedActions(array('view', 'insert', 'copy', 'edit', 'delete'));
            $this->setPrintListAvailable(false);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(false);
            $this->setAllowPrintSelectedRecords(false);
            $this->setOpenPrintFormInNewTab(false);
            $this->setExportListAvailable(array());
            $this->setExportSelectedRecordsAvailable(array());
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array());
            $this->setOpenExportedPdfInNewTab(false);
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            
            
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomDefaultValues(&$values, &$handled) 
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $oldRowData, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doFileUpload($fieldName, $rowData, &$result, &$accept, $originalFileName, $originalFileExtension, $fileSize, $tempFileName)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPrepareColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function doPrepareFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function doGetSelectionFilters(FixedKeysArray $columns, &$result)
        {
    
        }
    
        protected function doGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
        {
    
        }
    
        protected function doGetCustomColumnGroup(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doCalculateFields($rowData, $fieldName, &$value)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
        protected function doAddEnvironmentVariables(Page $page, &$variables)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new schedulePage("schedule", "schedule.php", GetCurrentUserPermissionsForPage("schedule"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("schedule"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
