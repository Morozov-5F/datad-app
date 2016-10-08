const app = angular.module('app');

app.factory('Users', function ($http, $q) {
    let accessToken;

    // users.get
    let get = (limit, after) => $http({
        url: 'http://nk5.ru/api/users.get',
        method: 'GET',
        headers: {
            'X-Access-Token': accessToken
        },
        params: { limit, after }
    }); 

    let getByID = (ids) => $http({
        url: 'http://nk5.ru/api/users.getByID',
        method: 'GET',
        headers: {
            'X-Access-Token': accessToken
        },        
        params: { ids: ids.join(',') }       
    });

    let register = (name, email, password) => $http({
        url: 'http://nk5.ru/api/register',
        method: 'POST',
        data: { name, email, password }
    });    

    let login = (email, password) => {
        let defer = $q.defer();
        $http({
            url: 'http://nk5.ru/api/login',
            method: 'POST',
            data: { email, password }
        }).then((result) => {
            if (result && result.data && result.data.access_token) {
                accessToken = result.data.access_token;
                defer.resolve(result);
            } else {
                defer.reject({ error: 'Failed to get access token '});
            }
        }, (result) => defer.reject(result));

        return defer.promise;
    };

    return {
        get,
        getByID,
        register,
        login
    };
});