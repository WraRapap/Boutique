(function () {
    'use strict';

    angular
        .module('PH')
        .service('HttpService', HttpService);

    HttpService.$inject = ['$http', 'Upload'];

    function HttpService($http, Upload) {

        var self = this;

        // 心跳次數到15時，發送心跳包給後端
        self.HeartBetCount = 0;

        // http post no 加解密
        self.HttpPost = function(serviceUrl, serviceType, actionType, dataObj) {

            if (dataObj == undefined) {
                dataObj = null;
            }

            return $http({
                method: 'POST',
                url: serviceUrl,
                data: {
                    ST: serviceType,
                    AT: actionType,
                    data: dataObj
                }
            }).then(function success(rsp) {

                var ret = rsp.data.ret;
                var data = rsp.data.data;
                try {
                    return {Ret:ret, Data:data };
                } catch (ex) {
                    return { Ret: '-1', Message: 'excetion error', Data: null };
                }

            }, function error(rsp) {
                return { Ret: '-1', Message: 'http error', Data: null };
            });
        }

        // http post no 加解密
        self.UploadFile = function(serviceUrl, serviceType, actionType, dataObj, file) {

            if (dataObj == undefined) {
                dataObj = null;
            }
            Upload.upload(
            { 
                url: serviceUrl, 
                data: {
                    ST: serviceType,
                    AT: actionType,
                    data: dataObj
                },
                file: file,
            })
            .progress(function (evt) { 
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total); 
                console.log('progress: ' + progressPercentage + '% ' + evt.config.file.name); 
            })
            .success(function (data, status, headers, config) { 
                console.log('file ' + config.file.name + 'uploaded. Response: ' + data); 
            })
            .error(function (data, status, headers, config) { 
                console.log('error status: ' + status); 
            })
        }
    }
})();
