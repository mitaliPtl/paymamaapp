var serviceAlias = $('#serviceAlias').val();

var mobilePrepaidAlias = $('#mobilePrepaidAlias').val();
var mobilePostpaidAlias = $('#mobilePostpaidAlias').val();
var dthAlias = $('#dthAlias').val();
var billPaymentsAlias = $('#billPaymentsAlias').val();
var moneyTransferAlias = $('#moneyTransferAlias').val();
var aepsAlias = $('#aepsAlias').val();
var upiTransferAlias = $('#upiTransferAlias').val();
var icicidAlias = $('#icicdAlias').val();
var apAlias = $('#apAlias').val();
var MinistatementAlias = $('#MinistatementAlias').val();

var selectedServiceId = $('#selectedServiceId').val();

var setDataTable = () => {
    // Set table with the below class
    if ($("table").hasClass("is-data-table-pkcm")) {
        if (!$.fn.DataTable.isDataTable('.is-data-table-pkcm')) {
            $('#example').dataTable();
            $('.is-data-table-pkcm').DataTable({
                "pageLength": 50,
                "stateSave": true,
                "lengthMenu": [10, 25, 50, 100],
            }).on('page.dt', function () {
                $('.preloader').css('display', '');
                location.reload();
            }).on('length.dt', function () {
                $('.preloader').css('display', '');
                location.reload();
            });
        }
    }
}

// On change of service type drop down
$('#service_id, #pkg_id,#operator_id').change(function () {
    var selectedServiceAlias = $('#service_id').find('option:selected').attr('data-service_alias');
    if (selectedServiceAlias == mobilePrepaidAlias || selectedServiceAlias == mobilePostpaidAlias || selectedServiceAlias == dthAlias || selectedServiceAlias == billPaymentsAlias) {
        $('#operator_id').prop('disabled', true);
    }
    $('#filter-submit-btn').trigger('click');
});

// Set Commission Type here
var setComTypInp = (comTyp) => {
    var ddCMTypEle = $('#' + comTyp + '-dd').val();
    var inputCMTypEle = $('.' + comTyp + '-inp');
    inputCMTypEle.val(ddCMTypEle).trigger('change');
}

// Handle By Angular
var app = angular.module('myApp', []);
app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%=');
    $interpolateProvider.endSymbol('%>');
});

//Data-table directive
app.directive('setDataTable', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function () {
                    setDataTable();
                });
            }
        }
    }
});

app.controller('packCommDtlsCtrl', function ($scope, $http) {
    // default post header
    $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';

    //
    $scope.mTranAEPSSaveList = [
        {
            from_range: "",
            to_range: "",
            service_id: "",
            pkg_id: "",
            operator_id: "",
            commission_type: "",
            ccf_commission: "",
            ccf_commission_type: "",
            api_charge_commission: "",
            api_charge_commission_type: "",
            admin_commission: "",
            admin_commission_type: "",
            md_commission: "",
            md_commission_type: "",
            distributor_commission_type: "",
            distributor_commission: "",
            retailer_commission: "",
            retailer_commission_type: "",
        }
    ];

    //Only For Mobile/DTH/Bill Payment
    var setEditDataFixedComm = (operatorList, pkCommDtls) => {
        pkCommDtls = JSON.parse(pkCommDtls);
        operatorList = operatorList.map(function (opObj) {
            pkCommDtls.map(function (pkComObj) {
                if (Number(opObj['operator_id']) == Number(pkComObj['operator_id'])) {
                    var operatorName = opObj['operator_name'];
                    opObj = pkComObj;
                    opObj['operator_name'] = operatorName;
                }
            });
            return opObj;
        });
        return operatorList;
    }

    // Only for Money Transfer and AEPS
    var setCommitionTypeDD = (editData) => {
        if (editData) {
            if (editData.hasOwnProperty('operator_id') && editData.operator_id)
                $('#operator_id').val(editData.operator_id);

            if (editData.hasOwnProperty('ccf_commission_type') && editData.ccf_commission_type)
                $('#ccf-type-dd').val(editData.ccf_commission_type);
            if (editData.hasOwnProperty('api_charge_commission_type') && editData.api_charge_commission_type)
                $('#api-charge-type-dd').val(editData.api_charge_commission_type);
            if (editData.hasOwnProperty('admin_commission_type') && editData.admin_commission_type)
                $('#ad-type-dd').val(editData.admin_commission_type);
            if (editData.hasOwnProperty('md_commission_type') && editData.md_commission_type)
                $('#md-type-dd').val(editData.md_commission_type);
            if (editData.hasOwnProperty('distributor_commission_type') && editData.distributor_commission_type)
                $('#dis-type-dd').val(editData.distributor_commission_type);
            if (editData.hasOwnProperty('retailer_commission_type') && editData.retailer_commission_type)
                $('#rt-type-dd').val(editData.retailer_commission_type);
        }
    }

    var hiddenPackageCommDetails = $('#hiddenPackageCommDetails').val();
    var hiddenOperatorsList = $('#hiddenOperatorsList').val();
  
    // Check For Edit Flow to populate data if exist
    if (hiddenPackageCommDetails) {
    //if (hiddenPackageCommDetails && (serviceAlias == moneyTransferAlias || serviceAlias == MiniStatementAlias || serviceAlias == aepsAlias || serviceAlias == upiTransferAlias || serviceAlias == icicidAlias || serviceAlias == apAlias)) {
        hiddenPackageCommDetails = JSON.parse(hiddenPackageCommDetails).filter(function (obj) {
            if (obj['service_id'] == selectedServiceId)
                return obj;
        });
        $scope.mTranAEPSSaveList = hiddenPackageCommDetails.length ? hiddenPackageCommDetails : $scope.mTranAEPSSaveList;
        if (hiddenPackageCommDetails)
            setCommitionTypeDD(hiddenPackageCommDetails[0]);
    } else if (hiddenOperatorsList && (serviceAlias == mobilePrepaidAlias || serviceAlias == mobilePostpaidAlias || serviceAlias == dthAlias || serviceAlias == billPaymentsAlias)) {
        hiddenOperatorsList = JSON.parse(hiddenOperatorsList).map(function (obj) {
            let key = {};
            key['operator_id'] = obj['operator_id'];
            key['service_id'] = obj['service_type'];
            key['operator_name'] = obj['operator_name'];
            return key;
        });

        $scope.mTranAEPSSaveList = hiddenOperatorsList.length ? hiddenOperatorsList : $scope.mTranAEPSSaveList;
        if (hiddenOperatorsList && hiddenPackageCommDetails)
            $scope.mTranAEPSSaveList = setEditDataFixedComm(hiddenOperatorsList, hiddenPackageCommDetails);
    }

    // Add new row for settings on add click
    $scope.addRow = () => {
        if ($('.is-data-table-pkcm').DataTable().clear().destroy())
        $scope.mTranAEPSSaveList.push(
            {
                from_range: "",
                to_range: "",
                service_id: "",
                pkg_id: "",
                operator_id: "",
                commission_type: "",
                ccf_commission: "",
                ccf_commission_type: "",
                api_charge_commission: "",
                api_charge_commission_type: "",
                admin_commission: "",
                admin_commission_type: "",
                md_commission: "",
                md_commission_type: "",
                distributor_commission_type: "",
                distributor_commission: "",
                retailer_commission: "",
                retailer_commission_type: "",
            }
        );
        setTimeout(() => {
            $('.comm-type-dd').trigger('change');
            setDataTable();
        }, 1000);
    }

    // Save Settings with data
    $scope.saveSettings = (saveRowReq) => {
        console.log(saveRowReq);
        if ($("#filterForm").valid())
            savePkgCommSettings(saveRowReq);
    }

    // Save Data to the server
    var savePkgCommSettings = (request) => {

        request['pkg_id'] = $('#pkg_id').val();
        request['service_id'] = $('#service_id').val();
        request['operator_id'] = request['operator_id'] ? request['operator_id'] : $('#operator_id').val();
        request['commission_type'] = request['commission_type'] ? request['commission_type'] : "Range";
        request['api_commission'] = 0;

        if ($("#filterForm").valid()) {
            $http({
                method: "POST",
                url: "save_pk_comm_details",
                data: $.param(request),
            }).then(function success(response) {
                if (request['pkg_commission_id'])
                    toastr.success("Updated Successfully!!");
                else
                    toastr.success("Saved Successfully!!");
                location.reload();
            }, function error(response) {
                alert(JSON.stringify(response));
                
                if (request['pkg_commission_id'])
                    toastr.error("Failed to Update!!");
                else
                    toastr.error("Failed to Save!!");
            });
        }
    }

});