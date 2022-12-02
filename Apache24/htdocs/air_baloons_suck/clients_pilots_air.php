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
    
    
    
    class clients_pilots_airPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Clients Pilots Air');
            $this->SetMenuLabel('Clients Pilots Air');
    
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`clients_pilots_air`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id_pilots', false, true),
                    new IntegerField('id_clients', false, true),
                    new IntegerField('id_air_ballon', false, true)
                )
            );
            $this->dataset->AddLookupField('id_pilots', 'pilots', new IntegerField('id_pilots'), new StringField('name', false, false, false, false, 'id_pilots_name', 'id_pilots_name_pilots'), 'id_pilots_name_pilots');
            $this->dataset->AddLookupField('id_clients', 'clients', new IntegerField('id_clients'), new StringField('name_', false, false, false, false, 'id_clients_name_', 'id_clients_name__clients'), 'id_clients_name__clients');
            $this->dataset->AddLookupField('id_air_ballon', 'air_baloons', new IntegerField('id_air_ballon'), new StringField('name_air_baloon', false, false, false, false, 'id_air_ballon_name_air_baloon', 'id_air_ballon_name_air_baloon_air_baloons'), 'id_air_ballon_name_air_baloon_air_baloons');
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
                new FilterColumn($this->dataset, 'id_pilots', 'id_pilots_name', 'Id Pilots'),
                new FilterColumn($this->dataset, 'id_clients', 'id_clients_name_', 'Id Clients'),
                new FilterColumn($this->dataset, 'id_air_ballon', 'id_air_ballon_name_air_baloon', 'Id Air Ballon')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id_pilots'])
                ->addColumn($columns['id_clients'])
                ->addColumn($columns['id_air_ballon']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
    
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('id_pilots', 'id_pilots_name', 'Id Pilots', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for name_ field
            //
            $column = new TextViewColumn('id_clients', 'id_clients_name_', 'Id Clients', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            //
            // View column for name_air_baloon field
            //
            $column = new TextViewColumn('id_air_ballon', 'id_air_ballon_name_air_baloon', 'Id Air Ballon', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('id_pilots', 'id_pilots_name', 'Id Pilots', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_ field
            //
            $column = new TextViewColumn('id_clients', 'id_clients_name_', 'Id Clients', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name_air_baloon field
            //
            $column = new TextViewColumn('id_air_ballon', 'id_air_ballon_name_air_baloon', 'Id Air Ballon', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for id_pilots field
            //
            $editor = new ComboBox('id_pilots_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`pilots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_pilots', true, true),
                    new StringField('name'),
                    new StringField('surname'),
                    new IntegerField('id_car'),
                    new IntegerField('rating')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Pilots', 
                'id_pilots', 
                $editor, 
                $this->dataset, 'id_pilots', 'name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_clients field
            //
            $editor = new ComboBox('id_clients_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`clients`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_clients', true, true),
                    new StringField('name_'),
                    new StringField('surname'),
                    new IntegerField('age'),
                    new IntegerField('weight')
                )
            );
            $lookupDataset->setOrderByField('name_', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Clients', 
                'id_clients', 
                $editor, 
                $this->dataset, 'id_clients', 'name_', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_air_ballon field
            //
            $editor = new ComboBox('id_air_ballon_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`air_baloons`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_air_ballon', true, true),
                    new StringField('name_air_baloon'),
                    new IntegerField('volume'),
                    new StringField('tralier')
                )
            );
            $lookupDataset->setOrderByField('name_air_baloon', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Air Ballon', 
                'id_air_ballon', 
                $editor, 
                $this->dataset, 'id_air_ballon', 'name_air_baloon', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->setAllowListCellEdit(false);
            $editColumn->setAllowSingleViewCellEdit(false);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for id_pilots field
            //
            $editor = new ComboBox('id_pilots_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`pilots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_pilots', true, true),
                    new StringField('name'),
                    new StringField('surname'),
                    new IntegerField('id_car'),
                    new IntegerField('rating')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Pilots', 
                'id_pilots', 
                $editor, 
                $this->dataset, 'id_pilots', 'name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for id_clients field
            //
            $editor = new ComboBox('id_clients_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`clients`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_clients', true, true),
                    new StringField('name_'),
                    new StringField('surname'),
                    new IntegerField('age'),
                    new IntegerField('weight')
                )
            );
            $lookupDataset->setOrderByField('name_', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Clients', 
                'id_clients', 
                $editor, 
                $this->dataset, 'id_clients', 'name_', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for id_air_ballon field
            //
            $editor = new ComboBox('id_air_ballon_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`air_baloons`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_air_ballon', true, true),
                    new StringField('name_air_baloon'),
                    new IntegerField('volume'),
                    new StringField('tralier')
                )
            );
            $lookupDataset->setOrderByField('name_air_baloon', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Air Ballon', 
                'id_air_ballon', 
                $editor, 
                $this->dataset, 'id_air_ballon', 'name_air_baloon', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddToggleEditColumns(Grid $grid)
        {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for id_pilots field
            //
            $editor = new ComboBox('id_pilots_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`pilots`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_pilots', true, true),
                    new StringField('name'),
                    new StringField('surname'),
                    new IntegerField('id_car'),
                    new IntegerField('rating')
                )
            );
            $lookupDataset->setOrderByField('name', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Pilots', 
                'id_pilots', 
                $editor, 
                $this->dataset, 'id_pilots', 'name', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_clients field
            //
            $editor = new ComboBox('id_clients_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`clients`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_clients', true, true),
                    new StringField('name_'),
                    new StringField('surname'),
                    new IntegerField('age'),
                    new IntegerField('weight')
                )
            );
            $lookupDataset->setOrderByField('name_', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Clients', 
                'id_clients', 
                $editor, 
                $this->dataset, 'id_clients', 'name_', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_air_ballon field
            //
            $editor = new ComboBox('id_air_ballon_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`air_baloons`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_air_ballon', true, true),
                    new StringField('name_air_baloon'),
                    new IntegerField('volume'),
                    new StringField('tralier')
                )
            );
            $lookupDataset->setOrderByField('name_air_baloon', 'ASC');
            $editColumn = new LookUpEditColumn(
                'Id Air Ballon', 
                'id_air_ballon', 
                $editor, 
                $this->dataset, 'id_air_ballon', 'name_air_baloon', $lookupDataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        private function AddMultiUploadColumn(Grid $grid)
        {
    
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('id_pilots', 'id_pilots_name', 'Id Pilots', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_ field
            //
            $column = new TextViewColumn('id_clients', 'id_clients_name_', 'Id Clients', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name_air_baloon field
            //
            $column = new TextViewColumn('id_air_ballon', 'id_air_ballon_name_air_baloon', 'Id Air Ballon', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('id_pilots', 'id_pilots_name', 'Id Pilots', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddExportColumn($column);
            
            //
            // View column for name_ field
            //
            $column = new TextViewColumn('id_clients', 'id_clients_name_', 'Id Clients', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddExportColumn($column);
            
            //
            // View column for name_air_baloon field
            //
            $column = new TextViewColumn('id_air_ballon', 'id_air_ballon_name_air_baloon', 'Id Air Ballon', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for name field
            //
            $column = new TextViewColumn('id_pilots', 'id_pilots_name', 'Id Pilots', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddCompareColumn($column);
            
            //
            // View column for name_ field
            //
            $column = new TextViewColumn('id_clients', 'id_clients_name_', 'Id Clients', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $grid->AddCompareColumn($column);
            
            //
            // View column for name_air_baloon field
            //
            $column = new TextViewColumn('id_air_ballon', 'id_air_ballon_name_air_baloon', 'Id Air Ballon', $this->dataset);
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
            $this->setAllowedActions(array());
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
        $Page = new clients_pilots_airPage("clients_pilots_air", "clients_pilots_air.php", GetCurrentUserPermissionsForPage("clients_pilots_air"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("clients_pilots_air"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
