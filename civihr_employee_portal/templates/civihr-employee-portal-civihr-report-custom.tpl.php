<div id="civihrReports">
  <ul class="nav nav-tabs nav-justified nav-tabs-header report-tabs">
    <?php if (!empty($jsonUrl)): ?>
      <li role="presentation" class="active">
        <a data-tab="report-builder" href="#report-builder">
          <i class="fa fa-bar-chart"></i>
          Report Builder
        </a>
      </li>
    <?php endif; ?>
    <?php if (!empty($tableUrl)): ?>
      <li role="presentation">
        <a data-tab="view-data" href="#view-data">
          <i class="fa fa-table"></i>
          View Data
        </a>
      </li>
    <?php endif; ?>
  </ul>

  <div class="report-content panel-pane pane-block chr_panel chr_panel--no-padding">
    <?php if (!empty($jsonUrl)): ?>
      <div class="report-block report-builder tab-pane">
        <div id="report-filters" ng-controller="FiltersController as filters">
          <?php print render($filters); ?>
        </div>
        <div id="reportPivotTableConfiguration">
          <form>
            <div class="row form-group">
              <div class="col-md-2">
                <label>Select existing report:</label>
              </div>
              <div class="col-md-5">
                <div class="crm_custom-select crm_custom-select--full">
                  <select name="id" class="report-config-select skip-js-custom-select">
                    <option value=""><?php print t('-- select configuration --'); ?></option>
                    <?php if (!empty($configurationList)): ?>
                      <?php foreach ($configurationList as $key => $value): ?>
                        <option value="<?php print $key; ?>"><?php print $value; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                  <span class="crm_custom-select__arrow"></span>
                </div>
              </div>
              <div class="col-md-5">
                <?php if (user_access('manage hrreports configuration')): ?>
                  <div class="form-item">
                    <input type="button" class="report-config-save-btn btn btn-primary" value="<?php print t('Save Report'); ?>">
                  </div>
                  <div class="form-item">
                    <input type="button" class="report-config-save-new-btn btn btn-primary" value="<?php print t('Save As New'); ?>">
                  </div>
                  <div class="form-item">
                    <input type="button" class="report-config-delete-btn btn btn-danger" value="<?php print t('Delete'); ?>">
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-2">
                <label>Chart Type:</label>
              </div>
              <div class="chart-type-select col-md-5">
              </div>
            </div>
          </form>
        </div>
        <hr />
        <div id="reportPivotTable" class="pvtTable-civi"></div>
      </div>
    <?php endif; ?>
    <?php if (!empty($tableUrl)): ?>
      <div class="report-block view-data pane-content hidden">
        <div id="reportTable"><?php print $table; ?></div>
        <?php if (!empty($exportUrl)): ?>
          <div class="chr_panel__footer">
            <div class="chr_actions-wrapper">
              <a href="<?php print $exportUrl; ?>" id="export-report" class="btn btn-primary">Export</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <script type="text/javascript">
    CRM.$(function () {
      var data = [];
      var initialDerivedAttributes = {};
      <?php if ($reportName === 'people'): ?>
      initialDerivedAttributes = {
        "Employee length of service group": function (row) {
          var los = parseInt(row['Employee length of service'] / 365, 10);

          if (los < 1) {
            return "Under 1 year";
          }

          if (los < 2) {
            return "1 - 2 years";
          }

          if (los < 5) {
            return "2 - 5 years";
          }

          if (los < 10) {
            return "5 - 10 years";
          }

          if (los < 15) {
            return "10 - 15 years";
          }

          if (los < 20) {
            return "15 - 20 years";
          }

          return "Over 20 years";
        }
      };
      <?php endif; ?>
      Drupal.behaviors.civihr_employee_portal_reports.instance.init({
        reportName: '<?php print $reportName; ?>',
        configurationList: <?php print json_encode($configurationList); ?>,
        data: data,
        tableContainer: jQuery('#reportTable'),
        pivotTableContainer: jQuery('#reportPivotTable'),
        derivedAttributes: initialDerivedAttributes,
        tableUrl: '<?php print $tableUrl; ?>',
        jsonUrl: '<?php print $jsonUrl; ?>',
        filters: <?php print !empty($filters) ? 1 : 0; ?>
      });
      Drupal.behaviors.civihr_employee_portal_reports.instance.show();
    });
  </script>
</div>
