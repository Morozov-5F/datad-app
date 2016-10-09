const app = angular.module('app');

app.factory('Categories', function ($http, $q, Users) {
    let get = () => {
        let token = Users.getAccessToken();
        
        return $http({
            url: 'http://nk5.ru/api/getCategories',
            method: 'GET',
            headers: {
                'X-Access-Token': token
            },
            params: { }
        });
    };

    return {
        get
    }
});