function laravelApi() {
    const baseUrl = 'http://127.0.0.1:8000/api';
    const home = `${baseUrl}`;

    return {
        home,
        auth: {
            login: `${home}/auth/login`,
            forgot: `${home}/auth/forgot`,
            newPassword: `${home}/auth/newPassword`,
            logout: `${home}/auth/logout`,
            user: `${home}/auth/user`,
        },

        users: {
            admin: `${home}/auth/users`,
        },
        comments: {
            comment: `${home}/auth/comments`,
        },
        feedbacks: {
            feedback: `${home}/auth/feedbacks`,
        },
    };
}

export default laravelApi();
