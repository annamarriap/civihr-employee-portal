<div class="panel-pane pane-block">

    <div class="col-md-2 column1 panel-panel">
        <button id="headcount" class="mainFilter btn btn-primary btn-reports">Headcount</button>
        <button id="gender" class="mainFilter btn btn-primary btn-reports">Gender</button>
        <button id="age" class="mainFilter btn btn-primary btn-reports">Age</button>
    </div>

    <div class="col-md-8 column2 panel-panel">
        <div id="custom-report"></div>
    </div>

    <div class="col-md-8 column1 panel-panel report-x-filters">
        <button id="all" class="subFilter btn btn-primary btn-reports">All</button>
        <button id="location" class="subFilter btn btn-primary btn-reports">Location</button>
        <button id="department" class="subFilter btn btn-primary btn-reports">Department</button>
        <button id="level" class="subFilter btn btn-primary btn-reports">Level</button>
        <button id="contract-type" class="subFilter btn btn-primary btn-reports">Contract Type</button>
        <button id="project-type" class="subFilter btn btn-primary btn-reports">Project Type</button>
    </div>

</div>

<div class="panel-pane custom-data-block">

    <div>
        <div id="custom-report-details"> <?php print $custom_data; ?> </div>
    </div>

</div>

<div class="panel-pane pane-block custom-settings-block">

    <div class="col-md-8 column2 panel-panel">
        <?php print $settings_url; ?>
    </div>

</div>
