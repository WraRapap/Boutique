(function () {
    'use strict';

    angular
        .module('PH')
        .service('LanMgr', LanguageService);

    LanguageService.inject = ['$rootScope'];

    function LanguageService($rootScope) {

        var self = this;

        self.LanguageType = LAN.CH;

        self.SetLan = function(lanType)
        {
            if(self.LanguageType != lanType)
            {
                self.LanguageType = lanType;
                $rootScope.$broadcast(EvDef.LanChange);
            }
        }

        self.Get = function(strObj) 
        {
            if(self.LanguageType == LAN.CH)
            {
                return strObj.CH;
            }
            else if(self.LanguageType == LAN.EN)
            {
                return strObj.EN;
            }
            else if(self.LanguageType == LAN.CN)
            {
                return strObj.CN;
            }
            return strObj.CH;
        }
    }
})();
