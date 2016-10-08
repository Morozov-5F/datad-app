const app = angular.module('app');

app.factory('Services', function ($http, $q, Users) {
    let get = () => {
        let token = Users.getAccessToken();

        return $http({
            url: 'http://nk5.ru/api/services.get',
            method: 'GET',
            headers: {
                'X-Access-Token': token
            },
            params: { /* TODO */ }
        });
    };

    return {
        get
    };
});