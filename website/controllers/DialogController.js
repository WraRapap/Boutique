(function()
{
	angular
		.module('PH')
		.controller('DialogController', DialogController);

	DialogController.$inject = ['$scope', 'ngDialog'];

	function DialogController($scope, ngDialog)
	{
		$scope.ConfirmClick = function() {

		}

		$scope.CancelClick = function() {
			
		}
	}
})();