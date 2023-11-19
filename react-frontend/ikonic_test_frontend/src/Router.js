import React, { useContext } from 'react';
import axios from 'axios';
import { Navigate, useRoutes } from 'react-router-dom';

import { AuthContext } from './context/AuthContext';
import LoginForm from './pages/auth/login/LoginForm';
import DashboardLayout from './layouts/dashboard';
import LogoOnlyLayout from './layouts/LogoOnlyLayout';
import ForgotPasswordForm from './pages/auth/forgotpassword/ForgotPasswordForm';
import RegisterForm from './pages/auth/register/RegisterForm';
import UserTable from './pages/User/UserTable';
import FeedbackTable from './pages/feedback/FeedbackTable';
import CommentTable from './pages/comment/CommentTable';
import UserForm from './pages/User/UserForm';
import FeedbackForm from './pages/feedback/FeedbackForm';
import GiveFeedBackOnComment from './pages/comment/GiveFeedBackOnComment';


export default function Router() {
    const { token } = useContext(AuthContext);

    axios.defaults.headers.common = {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json',
        'Access-Control-Allow-Origin': '*',
    };

    const allRoutes = [];

    if (token) {
        allRoutes.push(
            {
                path: '/',
                element: <Navigate to="/dashboard/comment" />,
            },
            {
                path: '/dashboard',
                element: <DashboardLayout />,
                children: [
                    { path: 'comment', element: <CommentTable /> },
                    { path: 'user', element: <UserTable /> },
                    { path: 'user_form', element: <UserForm /> },
                    { path: 'feedback_form', element: <FeedbackForm /> },
                    { path: 'feedback', element: <FeedbackTable /> },
                    { path: 'give_feedback_on_comment', element: <GiveFeedBackOnComment /> },
                ],
            }
        );
    }

    allRoutes.push(
        {
            path: '/',
            element: <LogoOnlyLayout />,
            children: [
                { path: '/', element: <Navigate to="/login" /> },
                { path: 'login', element: <LoginForm /> },
                { path: 'forgot_password', element: <ForgotPasswordForm /> },
                { path: 'registor', element: <RegisterForm /> },
                { path: '*', element: <Navigate to="/404" /> }
            ],
        },
        { path: '*', element: <Navigate to="/404" replace /> }
    );

    return useRoutes(allRoutes);
}
