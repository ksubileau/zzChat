define(['models/user'], function (UserModel) {
    module("Model :: User");

    test('Can be created with default values for its attributes.', function () {
        expect( 4 );
        var user = new UserModel();
        equal( user.get("nick"), '' );
        equal( user.get("age"), '' );
        equal( user.get("sex"), '' );
        equal( user.get("isActive"), true );
    });

});