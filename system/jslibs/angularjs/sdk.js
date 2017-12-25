(function(){
	angular.module("PH",[]).service("sdk", sdk);

	sdk.$inject = ["$http"];

	function sdk($http){

		var self = this;


		self.executeApi = function(funName, data, feedback){

			if(feedback != undefined){
				$.ajax({
					type 		: 'POST',
					url			: "/index.php/api/" + funName,
					async		: true,
					data 		: data,
					dataType 	: 'json',
					success		: feedback,
					error		: function(result){
						//your code here
					}
				});
			}
			else{
				var res = [];
				$.ajax({
					type 		: 'POST',
					url			: "/index.php/api/" + funName,
					async		: false,
					data 		: data,
					dataType 	: 'json',
					success		: function(response){
				      res = response;

					},
					error		: function(result){
						//your code here
					}
				});

				
				return res;
			}
			
		};

	};
})();