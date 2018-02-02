var PH = angular.module('PH', [ 'ngDialog', 'tm.pagination']);
PH.config(['ngDialogProvider', ProviderInject]);
function ProviderInject(  ngDialogProvider)
{
    ngDialogProvider.setDefaults({
        className: 'ngdialog-theme-default',
        plain: false,
        showClose: false,
        closeByDocument: true,
        closeByEscape: true,
        appendTo: false,
        preCloseCallback: function () {
            console.log('default pre-close callback');
        }
    });
}
