const app = angular.module('app');

app.factory('Providers', function ($http, $q, Users) {
    let get = (searchParams) => {
        if (typeof(searchParams) !== 'object') {
            searchParams = {};
        }
        
        let token = Users.getAccessToken();
        
        return $http({
            url: 'http://nk5.ru/api/providers.get',
            method: 'GET',
            headers: {
                'X-Access-Token': token
            },
            params: searchParams
        });
    };

    return {
        get
    };
});